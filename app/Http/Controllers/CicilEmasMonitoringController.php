<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\CicilEmasTransaction;
use App\Support\CicilEmas\TransactionInsight;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class CicilEmasMonitoringController extends Controller
{
    public function index(Request $request): View
    {
        $filters = [
            'status' => $request->string('status')->trim()->lower()->value(),
            'query' => $request->string('q')->trim()->value(),
        ];

        $hasQuery = filled($filters['query']);

        if (! $hasQuery) {
            return view('cicil-emas.riwayat-cicilan', [
                'insights' => collect(),
                'portfolio' => $this->buildPortfolioMetrics(collect()),
                'filters' => $filters,
                'totalTransactions' => 0,
                'hasQuery' => false,
            ]);
        }

        $transactionsQuery = CicilEmasTransaction::with([
            'nasabah',
            'installments' => fn ($query) => $query->orderBy('sequence'),
        ])->orderByDesc('created_at');

        $transactionsQuery->whereHas('nasabah', function ($query) use ($filters) {
            $query->where('nama', 'like', '%' . $filters['query'] . '%')
                ->orWhere('kode_member', 'like', '%' . $filters['query'] . '%');
        });

        if ($filters['status']) {
            $today = now()->startOfDay();

            $transactionsQuery->where(function ($query) use ($filters, $today) {
                switch ($filters['status']) {
                    case 'lunas':
                        $query->whereDoesntHave('installments', function ($installmentsQuery) {
                            $installmentsQuery
                                ->whereNull('paid_at')
                                ->orWhereColumn('paid_amount', '<', 'amount');
                        });
                        break;
                    case 'menunggak':
                        $query->whereHas('installments', function ($installmentsQuery) use ($today) {
                            $installmentsQuery
                                ->where(function ($subQuery) {
                                    $subQuery
                                        ->whereNull('paid_at')
                                        ->orWhereColumn('paid_amount', '<', 'amount');
                                })
                                ->whereDate('due_date', '<', $today);
                        });
                        break;
                    case 'aktif':
                        $query->whereHas('installments', function ($installmentsQuery) {
                            $installmentsQuery->where(function ($subQuery) {
                                $subQuery
                                    ->whereNull('paid_at')
                                    ->orWhereColumn('paid_amount', '<', 'amount');
                            });
                        })->whereDoesntHave('installments', function ($installmentsQuery) use ($today) {
                            $installmentsQuery
                                ->where(function ($subQuery) {
                                    $subQuery
                                        ->whereNull('paid_at')
                                        ->orWhereColumn('paid_amount', '<', 'amount');
                                })
                                ->whereDate('due_date', '<', $today);
                        });
                        break;
                }
            });
        }

        $allTransactions = (clone $transactionsQuery)->get();

        $paginatedTransactions = $transactionsQuery->paginate(6)->withQueryString();

        $barangIds = $allTransactions
            ->map(fn (CicilEmasTransaction $transaction) => TransactionInsight::extractBarangId($transaction->package_id))
            ->filter()
            ->unique();

        $barangMap = Barang::whereIn('id', $barangIds)
            ->get()
            ->keyBy('id');

        $allInsights = $allTransactions
            ->mapWithKeys(function (CicilEmasTransaction $transaction) use ($barangMap) {
                $barangId = TransactionInsight::extractBarangId($transaction->package_id);
                $barang = $barangId ? $barangMap->get($barangId) : null;

                return [$transaction->getKey() => TransactionInsight::summarize($transaction, $barang)];
            });

        $insights = $paginatedTransactions->through(function (CicilEmasTransaction $transaction) use ($allInsights) {
            return $allInsights->get($transaction->getKey());
        });

        $portfolio = $this->buildPortfolioMetrics($allInsights->values());

        return view('cicil-emas.riwayat-cicilan', [
            'insights' => $insights,
            'portfolio' => $portfolio,
            'filters' => $filters,
            'totalTransactions' => $allTransactions->count(),
            'hasQuery' => $hasQuery,
        ]);
    }

    private function buildPortfolioMetrics(Collection $insights): array
    {
        $totalPrincipal = (float) $insights->sum('principal_without_margin');
        $totalFinanced = (float) $insights->sum('total_financed');
        $totalMargin = (float) $insights->sum('margin_amount');
        $totalOutstanding = (float) $insights->sum(function ($insight) {
            return (float) ($insight['outstanding_balance'] ?? $insight['outstanding_principal'] ?? 0);
        });
        $totalPenalty = (float) $insights->sum('total_penalty');
        $totalPaid = (float) $insights->sum('total_paid');
        $count = $insights->count();

        $statusBuckets = [
            'Aktif' => 0,
            'Menunggak' => 0,
            'Lunas' => 0,
        ];

        foreach ($insights as $insight) {
            $status = $insight['status'] ?? 'Aktif';
            if (! array_key_exists($status, $statusBuckets)) {
                $statusBuckets[$status] = 0;
            }

            $statusBuckets[$status]++;
        }

        $averageCompletion = $count > 0
            ? round($insights->avg('completion_ratio'), 2)
            : 0.0;

        return [
            'total_transactions' => $count,
            'total_principal' => $totalPrincipal,
            'total_financed' => $totalFinanced,
            'total_margin' => $totalMargin,
            'total_outstanding' => $totalOutstanding,
            'total_penalty' => $totalPenalty,
            'total_paid' => $totalPaid,
            'average_completion' => $averageCompletion,
            'status_buckets' => $statusBuckets,
        ];
    }
}
