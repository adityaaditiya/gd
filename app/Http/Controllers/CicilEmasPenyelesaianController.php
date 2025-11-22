<?php

namespace App\Http\Controllers;

use App\Models\CicilEmasTransaction;
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
        $surplusDefisit = (float) $validated['penilaian_harga_pasar_emas'] - $totalHargaJual;
        $kewajibanPengembalianSurplus = $surplusDefisit > 0 ? $surplusDefisit : 0.0;

        DB::transaction(function () use ($transaction, $validated, $pokokPembiayaanBersih, $totalHargaJual, $surplusDefisit, $kewajibanPengembalianSurplus) {
            $transaction->penyelesaian_completed_at = Carbon::now();
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
        });

        return redirect()
            ->route('cicil-emas.penyelesaian-cicilan')
            ->with('status', __('Penyelesaian cicilan berhasil dicatat dan status ditandai SELESAI.'));
    }
}

