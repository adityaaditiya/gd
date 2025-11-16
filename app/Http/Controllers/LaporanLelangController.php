<?php

namespace App\Http\Controllers;

use App\Models\JadwalLelang;
use App\Support\LatestLimitedPaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class LaporanLelangController extends Controller
{
    private const STATUS_OPTIONS = ['Siap Lelang', 'Selesai', 'Butuh Penjadwalan Ulang'];

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'status' => ['nullable', Rule::in(self::STATUS_OPTIONS)],
            'tanggal_dari' => ['nullable', 'date'],
            'tanggal_sampai' => ['nullable', 'date', 'after_or_equal:tanggal_dari'],
            'search' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'in:10,25,50,100'],
        ]);

        $status = $validated['status'] ?? null;
        $tanggalDari = $validated['tanggal_dari'] ?? null;
        $tanggalSampai = $validated['tanggal_sampai'] ?? null;
        $search = trim((string) ($validated['search'] ?? ''));
        $perPage = (int) ($validated['per_page'] ?? LatestLimitedPaginator::PER_PAGE_OPTIONS[0]);

        $jadwalQuery = JadwalLelang::query()
            ->with(['barang.transaksi.nasabah'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($tanggalDari, function ($query) use ($tanggalDari) {
                $query->whereDate('tanggal_rencana', '>=', $tanggalDari);
            })
            ->when($tanggalSampai, function ($query) use ($tanggalSampai) {
                $query->whereDate('tanggal_rencana', '<=', $tanggalSampai);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('transaksi', function ($transactionQuery) use ($search) {
                        $transactionQuery->where('no_sbg', 'like', "%{$search}%");
                    })
                        ->orWhereHas('barang', function ($barangQuery) use ($search) {
                            $barangQuery->where('jenis_barang', 'like', "%{$search}%")
                                ->orWhere('merek', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('tanggal_rencana')
            ->latest('created_at');

        $jadwalLelang = LatestLimitedPaginator::fromQuery($jadwalQuery, $request, $perPage);

        $summary = [
            'total_harga_laku' => $jadwalLelang->getCollection()->sum('harga_laku'),
            'total_biaya_lelang' => $jadwalLelang->getCollection()->sum('biaya_lelang'),
            'total_distribusi_perusahaan' => $jadwalLelang->getCollection()->sum('distribusi_perusahaan'),
            'total_distribusi_nasabah' => $jadwalLelang->getCollection()->sum('distribusi_nasabah'),
            'total_piutang_sisa' => $jadwalLelang->getCollection()->sum('piutang_sisa'),
        ];

        return view('laporan.lelang', [
            'jadwalLelang' => $jadwalLelang,
            'summary' => $summary,
            'statusOptions' => self::STATUS_OPTIONS,
            'filters' => array_merge(
                Arr::only($validated, ['status', 'tanggal_dari', 'tanggal_sampai', 'search', 'per_page']),
                ['per_page' => $jadwalLelang->perPage()],
            ),
            'perPageOptions' => LatestLimitedPaginator::PER_PAGE_OPTIONS,
        ]);
    }
}
