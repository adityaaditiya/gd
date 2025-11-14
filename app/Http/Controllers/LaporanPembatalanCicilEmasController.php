<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\CicilEmasTransaction;
use App\Support\CicilEmas\TransactionInsight;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class LaporanPembatalanCicilEmasController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'petugas' => ['nullable', 'string', 'max:100'],
            'query' => ['nullable', 'string', 'max:100'],
        ]);

        $transactionsQuery = CicilEmasTransaction::with([
            'nasabah',
            'pembatal',
            'items',
            'installments' => fn ($query) => $query->orderBy('sequence'),
        ])->whereNotNull('dibatalkan_pada')->orderByDesc('dibatalkan_pada');

        if (! empty($filters['start_date'])) {
            $transactionsQuery->whereDate('dibatalkan_pada', '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $transactionsQuery->whereDate('dibatalkan_pada', '<=', $filters['end_date']);
        }

        if (! empty($filters['petugas'])) {
            $transactionsQuery->whereHas('pembatal', function ($query) use ($filters) {
                $query->where('name', 'like', '%' . $filters['petugas'] . '%');
            });
        }

        if (! empty($filters['query'])) {
            $transactionsQuery->whereHas('nasabah', function ($query) use ($filters) {
                $query->where('nama', 'like', '%' . $filters['query'] . '%')
                    ->orWhere('kode_member', 'like', '%' . $filters['query'] . '%');
            });
        }

        $transactions = $transactionsQuery->get();

        $barangIds = $transactions
            ->flatMap(fn (CicilEmasTransaction $transaction) => $transaction->items->pluck('barang_id'))
            ->filter()
            ->unique();

        $barangMap = Barang::whereIn('id', $barangIds)
            ->get()
            ->keyBy('id');

        $insights = $transactions->map(function (CicilEmasTransaction $transaction) use ($barangMap) {
            return TransactionInsight::summarize($transaction, $barangMap);
        });

        $metrics = $this->buildMetrics($insights, $transactions);

        return view('laporan.batal-cicil-emas', [
            'insights' => $insights,
            'filters' => $filters,
            'metrics' => $metrics,
        ]);
    }

    private function buildMetrics(Collection $insights, Collection $transactions): array
    {
        $count = $insights->count();

        $reasonBuckets = $transactions
            ->groupBy(function (CicilEmasTransaction $transaction) {
                $reason = trim((string) ($transaction->alasan_pembatalan ?? ''));

                return $reason !== '' ? $reason : __('Tanpa alasan');
            })
            ->map->count()
            ->sortDesc();

        $officerBuckets = $transactions
            ->groupBy(function (CicilEmasTransaction $transaction) {
                return $transaction->pembatal?->name ?? __('Tidak diketahui');
            })
            ->map->count()
            ->sortDesc();

        $timeline = $transactions
            ->groupBy(function (CicilEmasTransaction $transaction) {
                return optional($transaction->dibatalkan_pada)->toDateString();
            })
            ->filter(fn ($group, $date) => $date !== null)
            ->sortKeys()
            ->map(function (Collection $group) {
                $totalFinanced = (float) $group->sum(function (CicilEmasTransaction $transaction) {
                    if (! is_null($transaction->total_pembiayaan)) {
                        return (float) $transaction->total_pembiayaan;
                    }

                    return (float) $transaction->installments->sum('amount');
                });

                return [
                    'count' => $group->count(),
                    'total_financed' => $totalFinanced,
                ];
            });

        return [
            'total_transactions' => $count,
            'total_principal' => (float) $insights->sum('principal_without_margin'),
            'total_financed' => (float) $insights->sum('total_financed'),
            'total_margin' => (float) $insights->sum('margin_amount'),
            'total_administration' => (float) $insights->sum('administrasi'),
            'average_completion' => $count > 0
                ? round($insights->avg('completion_ratio'), 2)
                : 0.0,
            'reason_buckets' => $reasonBuckets,
            'officer_buckets' => $officerBuckets,
            'timeline' => $timeline,
        ];
    }
}
