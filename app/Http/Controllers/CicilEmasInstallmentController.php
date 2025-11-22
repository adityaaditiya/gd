<?php

namespace App\Http\Controllers;

use App\Models\CicilEmasInstallment;
use App\Models\CicilEmasTransaction;
use App\Models\MutasiKas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CicilEmasInstallmentController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string'],
            'status' => ['nullable', Rule::in(['paid', 'overdue', 'upcoming'])],
            'due_from' => ['nullable', 'date'],
            'due_until' => ['nullable', 'date'],
        ]);

        $today = Carbon::now()->startOfDay();
        $todayDateString = $today->toDateString();

        $search = trim($filters['search'] ?? '');
        $filters['search'] = $search;
        $status = $filters['status'] ?? null;
        $hasFilters = filled($search)
            || filled($status)
            || filled($filters['due_from'] ?? null)
            || filled($filters['due_until'] ?? null);

        $query = CicilEmasInstallment::with([
            'transaction.nasabah',
            'transaction.installments' => function ($query) {
                $query->select('id', 'cicil_emas_transaction_id', 'sequence', 'paid_at');
            },
        ])
            ->whereHas('transaction', function ($query) {
                $query->where('status', '!=', CicilEmasTransaction::STATUS_CANCELLED);
            })
            ->orderBy('due_date')
            ->orderBy('sequence');

        if (! $hasFilters) {
            $query->whereDate('due_date', '=', $todayDateString);
        }

        if (filled($search)) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('transaction.nasabah', function ($nasabahQuery) use ($search) {
                    $nasabahQuery->where('nama', 'like', "%{$search}%")
                        ->orWhere('kode_member', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%");
                })->orWhereHas('transaction', function ($transactionQuery) use ($search) {
                    $transactionQuery->where(function ($transactionQuery) use ($search) {
                        $transactionQuery->where('nomor_cicilan', 'like', "%{$search}%")
                            ->orWhere('pabrikan', 'like', "%{$search}%")
                            ->orWhere('option_label', 'like', "%{$search}%");
                    });
                });
            });
        }

        $query->when($status === 'paid', function ($query) {
            $query->whereNotNull('paid_at');
        })->when($status === 'overdue', function ($query) use ($todayDateString) {
            $query->whereNull('paid_at')
                ->whereDate('due_date', '<', $todayDateString);
        })->when($status === 'upcoming', function ($query) use ($todayDateString) {
            $query->whereNull('paid_at')
                ->whereDate('due_date', '>=', $todayDateString);
        });

        if (filled($filters['due_from'] ?? null)) {
            $from = Carbon::parse($filters['due_from'])->toDateString();
            $query->whereDate('due_date', '>=', $from);
        }

        if (filled($filters['due_until'] ?? null)) {
            $until = Carbon::parse($filters['due_until'])->toDateString();
            $query->whereDate('due_date', '<=', $until);
        }

        $installments = $query->paginate(25)->withQueryString();

        return view('cicil-emas.angsuran-rutin', [
            'installments' => $installments,
            'filters' => $filters,
            'hasFilters' => $hasFilters,
            'lateFeePercentagePerDay' => (float) config('cicil_emas.late_fee_percentage_per_day', 0.5),
            'today' => $today,
            'isDefaultingToToday' => ! $hasFilters,
        ]);
    }

    public function pay(Request $request, CicilEmasInstallment $installment): RedirectResponse
    {
        $installment->loadMissing('transaction');

        $previousUnpaidInstallment = CicilEmasInstallment::query()
            ->where('cicil_emas_transaction_id', $installment->cicil_emas_transaction_id)
            ->where('sequence', '<', $installment->sequence)
            ->whereNull('paid_at')
            ->orderBy('sequence')
            ->first();

        if ($previousUnpaidInstallment) {
            return redirect()
                ->route('cicil-emas.angsuran-rutin', $request->query())
                ->with('error', __('Selesaikan pembayaran angsuran ke-:sequence sebelum mencatat angsuran ke-:current.', [
                    'sequence' => $previousUnpaidInstallment->sequence,
                    'current' => $installment->sequence,
                ]));
        }

        $validated = $request->validate([
            'payment_date' => ['required', 'date'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
        ]);

        $paymentDate = Carbon::parse($validated['payment_date'])->endOfDay();
        $dueDate = $installment->due_date->copy()->endOfDay();
        $penaltyRate = $installment->penalty_rate ?? (float) config('cicil_emas.late_fee_percentage_per_day', 0.5);

        $daysLate = max(0, $dueDate->diffInDays($paymentDate, false));
        $penaltyAmount = round($installment->amount * ($penaltyRate / 100) * $daysLate, 2);
        $paidAmount = round((float) $validated['paid_amount'], 2);

        $installment->update([
            'paid_at' => $paymentDate,
            'paid_amount' => $paidAmount,
            'penalty_rate' => $penaltyRate,
            'penalty_amount' => $penaltyAmount,
        ]);

        $this->recordCashLedgerEntry($installment, $paymentDate, $paidAmount, $penaltyAmount);

        return redirect()
            ->route('cicil-emas.angsuran-rutin', $request->query())
            ->with('status', __("Pembayaran angsuran ke-:sequence berhasil dicatat. Denda keterlambatan: Rp :penalty", [
                'sequence' => $installment->sequence,
                'penalty' => number_format($penaltyAmount, 2, ',', '.'),
            ]));
    }

    public function cancelPayment(Request $request, CicilEmasInstallment $installment): RedirectResponse
    {
        $installment->loadMissing('transaction.nasabah');

        if (blank($installment->paid_at)) {
            return redirect()
                ->route('cicil-emas.angsuran-rutin', $request->query())
                ->with('error', __('Pembayaran angsuran ini belum dicatat.'));
        }

        $installment->update([
            'paid_at' => null,
            'paid_amount' => null,
            'penalty_rate' => 0,
            'penalty_amount' => 0,
        ]);

        $this->deleteCashLedgerEntry($installment);

        return redirect()
            ->route('cicil-emas.angsuran-rutin', $request->query())
            ->with('status', __('Pembayaran angsuran ke-:sequence telah dibatalkan.', [
                'sequence' => $installment->sequence,
            ]));
    }

    private function recordCashLedgerEntry(
        CicilEmasInstallment $installment,
        Carbon $paymentDate,
        float $paidAmount,
        float $penaltyAmount
    ): void {
        $installment->loadMissing('transaction.nasabah');
        $transaction = $installment->transaction;

        if (! $transaction) {
            return;
        }

        $totalCashIn = round($paidAmount + $penaltyAmount, 2);

        if ($totalCashIn <= 0) {
            return;
        }

        $reference = $this->getLedgerReference($installment);

        MutasiKas::updateOrCreate(
            [
                'cicil_emas_transaction_id' => $transaction->id,
                'referensi' => $reference,
            ],
            [
                'tanggal' => $paymentDate->toDateString(),
                'tipe' => 'masuk',
                'jumlah' => number_format($totalCashIn, 2, '.', ''),
                'sumber' => __('Angsuran Cicil Emas'),
                'keterangan' => __('Pembayaran angsuran ke-:sequence untuk :nasabah', [
                    'sequence' => $installment->sequence,
                    'nasabah' => $transaction->nasabah?->nama ?? __('Nasabah tidak diketahui'),
                ]),
            ]
        );
    }

    private function deleteCashLedgerEntry(CicilEmasInstallment $installment): void
    {
        $installment->loadMissing('transaction');
        $transaction = $installment->transaction;

        if (! $transaction) {
            return;
        }

        $reference = $this->getLedgerReference($installment);

        MutasiKas::where('cicil_emas_transaction_id', $transaction->id)
            ->where('referensi', $reference)
            ->delete();
    }

    private function getLedgerReference(CicilEmasInstallment $installment): string
    {
        $installment->loadMissing('transaction');
        $transaction = $installment->transaction;

        $nomor = $transaction?->nomor_cicilan
            ?? $transaction?->id
            ?? $installment->cicil_emas_transaction_id;

        return __('Angsuran Cicil Emas :nomor ke-:sequence', [
            'nomor' => $nomor,
            'sequence' => $installment->sequence,
        ]);
    }
}
