<?php

namespace App\Support\CicilEmas;

use App\Models\CicilEmasInstallment;
use App\Models\CicilEmasTransaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;

class TransactionInsight
{
    public static function summarize(CicilEmasTransaction $transaction, ?Collection $barangMap = null): array
    {
        $installments = $transaction->relationLoaded('installments')
            ? $transaction->installments->sortBy('sequence')->values()
            : collect();

        $items = $transaction->relationLoaded('items')
            ? $transaction->items->sortBy('id')->values()
            : collect();

        $itemsSummary = $items->map(function ($item) use ($barangMap) {
            $barang = $item->barang_id ? $barangMap?->get($item->barang_id) : null;

            return [
                'model' => $item,
                'nama_barang' => $item->nama_barang,
                'kode' => $item->kode_baki ?? $item->kode_intern,
                'berat' => (float) ($item->berat ?? 0),
                'harga' => (float) ($item->harga ?? 0),
                'current_barang' => $barang,
                'current_harga' => (float) ($barang?->harga ?? $item->harga ?? 0),
            ];
        });

        $now = Carbon::now();
        $today = $now->copy()->startOfDay();

        $totalFinanced = (float) $installments->sum('amount');
        $totalPenaltyAccrued = (float) $installments->sum(function (CicilEmasInstallment $installment) {
            return $installment->penalty_amount ?? 0.0;
        });
        $totalPaid = (float) $installments->sum(function (CicilEmasInstallment $installment) {
            return $installment->paid_amount ?? 0.0;
        });

        $administrationFee = (float) ($transaction->administrasi ?? 0.0);

        $principalWithoutMargin = (float) ($transaction->pokok_pembiayaan
            ?? max($transaction->harga_emas - $transaction->estimasi_uang_muka, 0));

        if ($principalWithoutMargin <= 0 && $transaction->harga_emas > 0) {
            $principalWithoutMargin = max($totalFinanced - $administrationFee - ($transaction->margin_amount ?? 0), 0);
        }

        $outstandingPrincipal = (float) $installments->sum(function (CicilEmasInstallment $installment) {
            $paid = $installment->paid_amount ?? 0.0;

            return max($installment->amount - $paid, 0.0);
        });

        $marginAmount = (float) ($transaction->margin_amount ?? ($totalFinanced - $administrationFee - $principalWithoutMargin));
        if ($marginAmount < 0) {
            $marginAmount = 0.0;
        }

        $marginPercentage = (float) ($transaction->margin_percentage ?? (
            $principalWithoutMargin > 0
                ? round(($marginAmount / $principalWithoutMargin) * 100, 2)
                : 0.0
        ));

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

        $completionRatio = $totalFinanced > 0
            ? round(min(($totalPaid / $totalFinanced) * 100, 100), 2)
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

        $currentGoldValue = $itemsSummary->isNotEmpty()
            ? (float) $itemsSummary->sum('current_harga')
            : (float) ($transaction->harga_emas ?? 0);
        $goldDelta = $currentGoldValue - (float) ($transaction->harga_emas ?? 0);

        $representativeBarang = $itemsSummary->count() === 1
            ? $itemsSummary->first()['current_barang']
            : null;

        return [
            'model' => $transaction,
            'barang' => $representativeBarang,
            'items' => $itemsSummary,
            'status' => $status,
            'status_style' => $statusStyle,
            'completion_ratio' => $completionRatio,
            'total_principal' => $principalWithoutMargin,
            'principal_without_margin' => $principalWithoutMargin,
            'total_financed' => $totalFinanced,
            'total_paid' => $totalPaid,
            'total_penalty' => $totalPenaltyAccrued,
            'outstanding_principal' => max($outstandingPrincipal, 0.0),
            'outstanding_balance' => max($outstandingPrincipal, 0.0),
            'margin_amount' => $marginAmount,
            'margin_percentage' => $marginPercentage,
            'administrasi' => $administrationFee,
            'overdue_installments' => $overdueInstallments,
            'next_installment' => $nextInstallment,
            'last_payment' => $lastPayment,
            'current_gold_value' => $currentGoldValue,
            'gold_delta' => $goldDelta,
            'installments' => $installments,
        ];
    }
}
