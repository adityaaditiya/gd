<?php

namespace App\Support\CicilEmas;

use App\Models\Barang;
use App\Models\CicilEmasInstallment;
use App\Models\CicilEmasTransaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TransactionInsight
{
    public static function extractBarangId(?string $packageId): ?int
    {
        if (! $packageId) {
            return null;
        }

        if (! Str::startsWith($packageId, 'barang-')) {
            return null;
        }

        $id = (int) Str::after($packageId, 'barang-');

        return $id > 0 ? $id : null;
    }

    public static function summarize(CicilEmasTransaction $transaction, ?Barang $barang = null): array
    {
        $installments = $transaction->relationLoaded('installments')
            ? $transaction->installments->sortBy('sequence')->values()
            : collect();

        $now = Carbon::now();
        $today = $now->copy()->startOfDay();

        $totalPrincipal = (float) $installments->sum('amount');
        $totalPenaltyAccrued = (float) $installments->sum(function (CicilEmasInstallment $installment) {
            return $installment->penalty_amount ?? 0.0;
        });
        $totalPaid = (float) $installments->sum(function (CicilEmasInstallment $installment) {
            return $installment->paid_amount ?? 0.0;
        });

        $outstandingPrincipal = (float) $installments->sum(function (CicilEmasInstallment $installment) {
            $paid = $installment->paid_amount ?? 0.0;

            return max($installment->amount - $paid, 0.0);
        });

        $overdueInstallments = $installments->filter(function (CicilEmasInstallment $installment) use ($today) {
            if ($installment->paid_at) {
                return false;
            }

            return $installment->due_date->lt($today);
        });

        $isCompleted = $outstandingPrincipal <= 0.0 && $installments->count() > 0;
        $hasOverdue = $overdueInstallments->isNotEmpty();

        $status = 'Aktif';
        $statusStyle = 'info';

        if ($isCompleted) {
            $status = 'Lunas';
            $statusStyle = 'success';
        } elseif ($hasOverdue) {
            $status = 'Menunggak';
            $statusStyle = 'danger';
        }

        $completionRatio = $totalPrincipal > 0
            ? round(min(($totalPaid / $totalPrincipal) * 100, 100), 2)
            : 0.0;

        $nextInstallment = $installments
            ->filter(function (CicilEmasInstallment $installment) use ($today) {
                if ($installment->paid_at) {
                    return false;
                }

                return $installment->due_date->gte($today);
            })
            ->sortBy('due_date')
            ->first();

        $lastPayment = $installments
            ->filter(fn (CicilEmasInstallment $installment) => $installment->paid_at !== null)
            ->sortByDesc('paid_at')
            ->first();

        $currentGoldValue = $barang?->harga ?? $transaction->harga_emas;
        $goldDelta = $currentGoldValue - $transaction->harga_emas;

        return [
            'model' => $transaction,
            'barang' => $barang,
            'status' => $status,
            'status_style' => $statusStyle,
            'completion_ratio' => $completionRatio,
            'total_principal' => $totalPrincipal,
            'total_paid' => $totalPaid,
            'total_penalty' => $totalPenaltyAccrued,
            'outstanding_principal' => max($outstandingPrincipal, 0.0),
            'overdue_installments' => $overdueInstallments,
            'next_installment' => $nextInstallment,
            'last_payment' => $lastPayment,
            'current_gold_value' => $currentGoldValue,
            'gold_delta' => $goldDelta,
            'installments' => $installments,
        ];
    }
}
