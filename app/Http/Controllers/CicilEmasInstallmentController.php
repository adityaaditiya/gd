<?php

namespace App\Http\Controllers;

use App\Models\CicilEmasInstallment;
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

        $query = CicilEmasInstallment::with(['transaction.nasabah'])
            ->whereHas('transaction')
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
                    $transactionQuery->where('pabrikan', 'like', "%{$search}%")
                        ->orWhere('option_label', 'like', "%{$search}%");
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
            ->route('cicil-emas.angsuran-rutin', $request->query())
            ->with('status', __("Pembayaran angsuran ke-:sequence berhasil dicatat. Denda keterlambatan: Rp :penalty", [
                'sequence' => $installment->sequence,
                'penalty' => number_format($penaltyAmount, 2, ',', '.'),
            ]));
    }
}
