<?php

namespace App\Http\Controllers;

use App\Models\CicilEmasTransaction;
use App\Models\MutasiKas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CicilEmasPelunasanController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $transaction = null;
        $summary = null;

        if ($search !== '') {
            $transaction = CicilEmasTransaction::query()
                ->with([
                    'nasabah',
                    'installments' => fn ($query) => $query->orderBy('sequence'),
                ])
                ->where('nomor_cicilan', 'like', "%{$search}%")
                ->orderByDesc('id')
                ->first();

            if ($transaction) {
                $summary = $this->buildSettlementSummary($transaction);
            }
        }

        $previewNumber = $this->generateSettlementNumber(Carbon::now());

        return view('cicil-emas.pelunasan-cicilan', [
            'search' => $search,
            'transaction' => $transaction,
            'summary' => $summary,
            'previewNumber' => $previewNumber,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => ['required', 'exists:cicil_emas_transactions,id'],
            'biaya_ongkos_kirim' => ['nullable', 'numeric', 'min:0'],
        ]);

        $transaction = CicilEmasTransaction::with([
            'installments' => fn ($query) => $query->orderBy('sequence'),
        ])->findOrFail($validated['transaction_id']);

        if ($transaction->status === CicilEmasTransaction::STATUS_CANCELLED) {
            return back()
                ->with('error', __('Transaksi cicilan sudah dibatalkan dan tidak dapat dilunasi.'))
                ->withInput();
        }

        if ($transaction->status === CicilEmasTransaction::STATUS_SETTLED) {
            return back()
                ->with('error', __('Transaksi cicilan sudah berstatus lunas.'))
                ->withInput();
        }

        $summary = $this->buildSettlementSummary($transaction);

        if ($summary['remainingAmount'] <= 0) {
            return back()
                ->with('error', __('Seluruh angsuran sudah terbayar. Tidak ada tagihan tersisa untuk dilunasi.'))
                ->withInput();
        }

        $settlementDate = Carbon::now();

        DB::transaction(function () use ($transaction, $settlementDate, $summary, $validated) {
            $nomorPelunasan = $this->generateSettlementNumber($settlementDate, true);
            $cashInAmount = round($summary['remainingAmount'] + (float) ($validated['biaya_ongkos_kirim'] ?? 0), 2);

            foreach ($transaction->installments as $installment) {
                $remaining = max(0, (float) $installment->amount - (float) ($installment->paid_amount ?? 0));

                if ($remaining > 0) {
                    $installment->paid_amount = $installment->amount;
                    $installment->paid_at = $settlementDate;
                    $installment->save();
                }
            }

            $transaction->nomor_pelunasan = $nomorPelunasan;
            $transaction->tanggal_pelunasan = $settlementDate;
            $transaction->biaya_ongkos_kirim = $validated['biaya_ongkos_kirim'] ?? null;
            $transaction->pelunasan_dipercepat = $summary['isAccelerated'];
            $transaction->status = CicilEmasTransaction::STATUS_SETTLED;
            $transaction->save();

            $this->recordCashLedgerEntry(
                $transaction,
                $settlementDate,
                $cashInAmount,
                $nomorPelunasan,
                $summary['isAccelerated']
            );
        });

        return redirect()
            ->route('cicil-emas.pelunasan-cicilan', ['search' => $transaction->nomor_cicilan])
            ->with('status', __('Pelunasan cicilan emas berhasil disimpan dengan status LUNAS.'));
    }

    private function buildSettlementSummary(CicilEmasTransaction $transaction): array
    {
        $installments = $transaction->installments ?? collect();
        $sortedInstallments = $installments->sortBy('sequence');
        $remainingInstallments = $sortedInstallments->filter(function ($installment) {
            $paidAmount = (float) ($installment->paid_amount ?? 0);

            return $installment->paid_at === null || $paidAmount + 0.001 < (float) $installment->amount;
        });

        $totalScheduled = $sortedInstallments->sum(fn ($installment) => (float) $installment->amount);
        $totalPaid = $sortedInstallments->sum(fn ($installment) => (float) ($installment->paid_amount ?? 0));
        $remainingAmount = $remainingInstallments->sum(function ($installment) {
            $paidAmount = (float) ($installment->paid_amount ?? 0);

            return max(0, (float) $installment->amount - $paidAmount);
        });

        $nextUnpaid = $remainingInstallments->first();
        $isAccelerated = $nextUnpaid && $nextUnpaid->due_date?->isFuture();

        return [
            'totalScheduled' => $totalScheduled,
            'totalPaid' => $totalPaid,
            'remainingAmount' => $remainingAmount,
            'isAccelerated' => (bool) $isAccelerated,
            'nextDueDate' => $nextUnpaid?->due_date,
            'lastSequence' => $sortedInstallments->max('sequence'),
            'paidInstallments' => $sortedInstallments->count() - $remainingInstallments->count(),
            'totalInstallments' => $sortedInstallments->count(),
        ];
    }

    private function generateSettlementNumber(Carbon $date, bool $lock = false): string
    {
        $date = $date->copy()->startOfDay();
        $prefix = 'PE03';
        $datePart = $date->format('ymd');
        $base = $prefix.$datePart;

        $query = CicilEmasTransaction::query()
            ->where(function ($builder) use ($date) {
                $builder->whereDate('tanggal_pelunasan', $date->toDateString());
            })
            ->where('nomor_pelunasan', 'like', $base.'%')
            ->orderByDesc('nomor_pelunasan');

        if ($lock || DB::transactionLevel() > 0) {
            $query->lockForUpdate();
        }

        $latestNumber = $query->value('nomor_pelunasan');

        $sequence = 1;

        if ($latestNumber) {
            $sequencePart = substr($latestNumber, -3);

            if (ctype_digit($sequencePart)) {
                $sequence = (int) $sequencePart + 1;
            }
        }

        return $base.str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
    }

    private function recordCashLedgerEntry(
        CicilEmasTransaction $transaction,
        Carbon $settlementDate,
        float $cashInAmount,
        string $nomorPelunasan,
        bool $isAccelerated
    ): void {
        $transaction->loadMissing('nasabah');

        if ($cashInAmount <= 0) {
            return;
        }

        $reference = __('Pelunasan Cicil Emas :nomor', ['nomor' => $nomorPelunasan ?: $transaction->nomor_cicilan]);

        MutasiKas::updateOrCreate(
            [
                'cicil_emas_transaction_id' => $transaction->id,
                'referensi' => $reference,
            ],
            [
                'tanggal' => $settlementDate->toDateString(),
                'tipe' => 'masuk',
                'jumlah' => number_format($cashInAmount, 2, '.', ''),
                'sumber' => __('Pelunasan Cicil Emas'),
                'keterangan' => $isAccelerated
                    ? __('Pelunasan dipercepat untuk :nasabah', [
                        'nasabah' => $transaction->nasabah?->nama ?? __('Nasabah tidak diketahui'),
                    ])
                    : __('Pelunasan akhir kontrak untuk :nasabah', [
                        'nasabah' => $transaction->nasabah?->nama ?? __('Nasabah tidak diketahui'),
                    ]),
            ]
        );
    }
}
