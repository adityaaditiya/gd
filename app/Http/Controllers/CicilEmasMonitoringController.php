<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\CicilEmasTransaction;
use App\Support\CicilEmas\TransactionInsight;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class CicilEmasMonitoringController extends Controller
{
    public function index(): View
    {
        $transactions = CicilEmasTransaction::with([
            'nasabah',
            'installments' => fn ($query) => $query->orderBy('sequence'),
        ])->orderByDesc('created_at')->get();

        $barangIds = $transactions
            ->map(fn (CicilEmasTransaction $transaction) => TransactionInsight::extractBarangId($transaction->package_id))
            ->filter()
            ->unique();

        $barangMap = Barang::whereIn('id', $barangIds)
            ->get()
            ->keyBy('id');

        $insights = $transactions->map(function (CicilEmasTransaction $transaction) use ($barangMap) {
            $barangId = TransactionInsight::extractBarangId($transaction->package_id);
            $barang = $barangId ? $barangMap->get($barangId) : null;

            return TransactionInsight::summarize($transaction, $barang);
        });

        $portfolio = $this->buildPortfolioMetrics($insights);

        return view('cicil-emas.riwayat-cicilan', [
            'insights' => $insights,
            'portfolio' => $portfolio,
        ]);
    }

    private function buildPortfolioMetrics(Collection $insights): array
    {
        $totalPrincipal = (float) $insights->sum('total_principal');
        $totalOutstanding = (float) $insights->sum('outstanding_principal');
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
            'total_outstanding' => $totalOutstanding,
            'total_penalty' => $totalPenalty,
            'total_paid' => $totalPaid,
            'average_completion' => $averageCompletion,
            'status_buckets' => $statusBuckets,
        ];
    }
}
