<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\CicilEmasTransaction;
use App\Support\CicilEmas\TransactionInsight;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class LaporanCicilEmasController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'status' => ['nullable', 'in:Aktif,Menunggak,Lunas'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $query = CicilEmasTransaction::with([
            'nasabah',
            'installments' => fn ($builder) => $builder->orderBy('sequence'),
        ])->orderByDesc('created_at');

        if (! empty($validated['start_date'])) {
            $query->whereDate('created_at', '>=', $validated['start_date']);
        }

        if (! empty($validated['end_date'])) {
            $query->whereDate('created_at', '<=', $validated['end_date']);
        }

        $transactions = $query->get();

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

        if (! empty($validated['status'])) {
            $insights = $insights->filter(function (array $insight) use ($validated) {
                return ($insight['status'] ?? null) === $validated['status'];
            })->values();
        }

        $metrics = $this->buildMetrics($insights);
        $statusBuckets = $metrics['status_buckets'];

        return view('laporan.cicil-emas', [
            'insights' => $insights,
            'filters' => $validated,
            'metrics' => $metrics,
            'statusBuckets' => $statusBuckets,
        ]);
    }

    private function buildMetrics(Collection $insights): array
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

        $lateRatio = $count > 0
            ? round((($statusBuckets['Menunggak'] ?? 0) / $count) * 100, 2)
            : 0.0;

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
            'late_ratio' => $lateRatio,
            'status_buckets' => $statusBuckets,
        ];
    }
}
