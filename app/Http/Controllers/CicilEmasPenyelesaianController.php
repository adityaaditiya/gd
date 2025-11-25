<?php

namespace App\Http\Controllers;

use App\Models\CicilEmasTransaction;
use App\Models\MutasiKas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CicilEmasPenyelesaianController extends Controller
{
    public function index(Request $request): View
    {
        $cutoffDate = Carbon::now()->subDays(30)->startOfDay();

        $transactions = CicilEmasTransaction::query()
            ->with([
                'nasabah',
                'installments' => fn ($query) => $query->orderBy('sequence'),
            ])
            ->where('status', CicilEmasTransaction::STATUS_ACTIVE)
            ->get()
            ->filter(function (CicilEmasTransaction $transaction) use ($cutoffDate) {
                $oldestUnpaid = $transaction->installments
                    ->filter(function ($installment) {
                        $paidAmount = (float) ($installment->paid_amount ?? 0);

                        return $installment->paid_at === null || $paidAmount + 0.001 < (float) $installment->amount;
                    })
                    ->sortBy('due_date')
                    ->first();

                return $oldestUnpaid && $oldestUnpaid->due_date?->lte($cutoffDate);
            })
            ->values();

        return view('cicil-emas.penyelesaian-cicilan', [
            'transactions' => $transactions,
            'cutoffDate' => $cutoffDate,
            'lateFeePercentagePerDay' => (float) config('cicil_emas.late_fee_percentage_per_day', 0.5),
            'today' => Carbon::now()->startOfDay(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'transaction_id' => ['required', 'exists:cicil_emas_transactions,id'],
            'penilaian_harga_pasar_emas' => ['required', 'numeric', 'min:0'],
            'harga_beli_emas' => ['required', 'numeric', 'min:0'],
            'nominal_denda' => ['required', 'numeric', 'min:0'],
            'uang_muka' => ['required', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        /** @var CicilEmasTransaction $transaction */
        $transaction = CicilEmasTransaction::with(['installments' => fn ($query) => $query->orderBy('sequence')])
            ->findOrFail($validated['transaction_id']);

        if ($transaction->status !== CicilEmasTransaction::STATUS_ACTIVE) {
            return redirect()
                ->route('cicil-emas.penyelesaian-cicilan')
                ->with('error', __('Hanya cicilan aktif yang dapat diselesaikan.'));
        }

        $oldestUnpaid = $transaction->installments
            ->filter(function ($installment) {
                $paidAmount = (float) ($installment->paid_amount ?? 0);

                return $installment->paid_at === null || $paidAmount + 0.001 < (float) $installment->amount;
            })
            ->sortBy('due_date')
            ->first();

        $cutoffDate = Carbon::now()->subDays(30)->startOfDay();

        if (! $oldestUnpaid || ! $oldestUnpaid->due_date?->lte($cutoffDate)) {
            return redirect()
                ->route('cicil-emas.penyelesaian-cicilan')
                ->with('error', __('Cicilan belum memenuhi kriteria tunggakan lebih dari 30 hari.'));
        }

        $pokokPembiayaanBersih = max(0, (float) $validated['harga_beli_emas'] - (float) $validated['uang_muka']);
        $totalHargaJual = $pokokPembiayaanBersih + (float) $transaction->margin_amount;
        $sisaUtang = $transaction->installments->sum(function ($installment) {
            $paidAmount = (float) ($installment->paid_amount ?? 0);

            return max(0, (float) $installment->amount - $paidAmount);
        });
        $totalKewajibanBersih = $sisaUtang + (float) $validated['nominal_denda'];
        $surplusDefisit = (float) $validated['penilaian_harga_pasar_emas'] - $totalKewajibanBersih;
        $kewajibanPengembalianSurplus = $surplusDefisit > 0 ? $surplusDefisit : 0.0;

        $completedAt = Carbon::now();

        DB::transaction(function () use ($transaction, $validated, $pokokPembiayaanBersih, $totalHargaJual, $surplusDefisit, $kewajibanPengembalianSurplus, $completedAt) {
            $transaction->penyelesaian_completed_at = $completedAt;
            $transaction->penyelesaian_market_price = $validated['penilaian_harga_pasar_emas'];
            $transaction->penyelesaian_purchase_price = $validated['harga_beli_emas'];
            $transaction->penyelesaian_penalty_amount = $validated['nominal_denda'];
            $transaction->penyelesaian_down_payment = $validated['uang_muka'];
            $transaction->penyelesaian_pokok_bersih = $pokokPembiayaanBersih;
            $transaction->penyelesaian_total_margin = $transaction->margin_amount;
            $transaction->penyelesaian_total_harga_jual = $totalHargaJual;
            $transaction->penyelesaian_surplus_defisit = $surplusDefisit;
            $transaction->penyelesaian_kewajiban_pengembalian = $kewajibanPengembalianSurplus;
            $transaction->penyelesaian_keterangan = $validated['keterangan'] ?? null;
            $transaction->status = CicilEmasTransaction::STATUS_COMPLETED;
            $transaction->save();

            $this->recordCashLedgerEntries(
                $transaction,
                $completedAt,
                (float) $validated['penilaian_harga_pasar_emas'],
                (float) $kewajibanPengembalianSurplus
            );
        });

        return redirect()
            ->route('cicil-emas.penyelesaian-cicilan')
            ->with('status', __('Penyelesaian cicilan berhasil dicatat dan status ditandai SELESAI.'));
    }

    public function cancel(Request $request, CicilEmasTransaction $transaction): RedirectResponse
    {
        if ($transaction->status !== CicilEmasTransaction::STATUS_COMPLETED) {
            return redirect()
                ->route('cicil-emas.daftar-cicilan')
                ->with('error', __('Pembatalan hanya tersedia untuk cicilan yang telah diselesaikan.'));
        }

        DB::transaction(function () use ($transaction) {
            $transaction->penyelesaian_market_price = null;
            $transaction->penyelesaian_purchase_price = null;
            $transaction->penyelesaian_penalty_amount = null;
            $transaction->penyelesaian_down_payment = null;
            $transaction->penyelesaian_pokok_bersih = null;
            $transaction->penyelesaian_total_margin = null;
            $transaction->penyelesaian_total_harga_jual = null;
            $transaction->penyelesaian_surplus_defisit = null;
            $transaction->penyelesaian_kewajiban_pengembalian = null;
            $transaction->penyelesaian_keterangan = null;
            $transaction->penyelesaian_completed_at = null;
            $transaction->status = CicilEmasTransaction::STATUS_ACTIVE;
            $transaction->save();

            $this->deleteCashLedgerEntries($transaction);
        });

        return redirect()
            ->route('cicil-emas.daftar-cicilan')
            ->with('status', __('Penyelesaian cicilan dibatalkan dan status dikembalikan menjadi aktif.'));
    }

    private function recordCashLedgerEntries(
        CicilEmasTransaction $transaction,
        Carbon $completedAt,
        float $marketPrice,
        float $refundObligation
    ): void {
        $transaction->loadMissing('nasabah');

        $marketPrice = round(max(0, $marketPrice), 2);
        $refundObligation = round(max(0, $refundObligation), 2);

        $baseReference = __('Penyelesaian Cicil Emas :nomor', [
            'nomor' => $transaction->nomor_cicilan ?? $transaction->id,
        ]);

        if ($marketPrice > 0) {
            MutasiKas::updateOrCreate(
                [
                    'cicil_emas_transaction_id' => $transaction->id,
                    'referensi' => $baseReference,
                ],
                [
                    'tanggal' => $completedAt->toDateString(),
                    'tipe' => 'masuk',
                    'jumlah' => number_format($marketPrice, 2, '.', ''),
                    'sumber' => __('Penyelesaian Cicil Emas'),
                    'keterangan' => __('Pencairan aset untuk penyelesaian cicilan :nasabah', [
                        'nasabah' => $transaction->nasabah?->nama ?? __('Nasabah tidak diketahui'),
                    ]),
                ]
            );
        }

        if ($refundObligation > 0) {
            $refundReference = __('Pengembalian Dana ke Nasabah (Netting Pelunasan) :nomor', [
                'nomor' => $transaction->nomor_cicilan ?? $transaction->id,
            ]);

            MutasiKas::updateOrCreate(
                [
                    'cicil_emas_transaction_id' => $transaction->id,
                    'referensi' => $refundReference,
                ],
                [
                    'tanggal' => $completedAt->toDateString(),
                    'tipe' => 'keluar',
                    'jumlah' => number_format($refundObligation, 2, '.', ''),
                    'sumber' => __('Penyelesaian Cicil Emas'),
                    'keterangan' => __('Pengembalian surplus hasil proses netting kepada :nasabah', [
                        'nasabah' => $transaction->nasabah?->nama ?? __('Nasabah tidak diketahui'),
                    ]),
                ]
            );
        }
    }

    private function deleteCashLedgerEntries(CicilEmasTransaction $transaction): void
    {
        $transaction->loadMissing('nasabah');

        $references = [
            __('Penyelesaian Cicil Emas :nomor', [
                'nomor' => $transaction->nomor_cicilan ?? $transaction->id,
            ]),
            __('Pengembalian Dana ke Nasabah (Netting Pelunasan) :nomor', [
                'nomor' => $transaction->nomor_cicilan ?? $transaction->id,
            ]),
            __('Pengembalian Surplus Penyelesaian Cicil Emas :nomor', [
                'nomor' => $transaction->nomor_cicilan ?? $transaction->id,
            ]),
        ];

        MutasiKas::query()
            ->where('cicil_emas_transaction_id', $transaction->id)
            ->whereIn('referensi', $references)
            ->delete();
    }
}

