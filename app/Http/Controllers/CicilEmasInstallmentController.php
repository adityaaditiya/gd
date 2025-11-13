<?php

namespace App\Http\Controllers;

use App\Models\CicilEmasInstallment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class CicilEmasInstallmentController extends Controller
{
    public function index(): View
    {
        $installments = CicilEmasInstallment::with(['transaction.nasabah'])
            ->orderBy('due_date')
            ->orderBy('sequence')
            ->get();

        return view('cicil-emas.angsuran-rutin', [
            'installments' => $installments,
            'lateFeePercentagePerDay' => (float) config('cicil_emas.late_fee_percentage_per_day', 0.5),
        ]);
    }

    public function pay(Request $request, CicilEmasInstallment $installment): RedirectResponse
    {
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

        return redirect()
            ->route('cicil-emas.angsuran-rutin')
            ->with('status', __("Pembayaran angsuran ke-:sequence berhasil dicatat. Denda keterlambatan: Rp :penalty", [
                'sequence' => $installment->sequence,
                'penalty' => number_format($penaltyAmount, 2, ',', '.'),
            ]));
    }
}
