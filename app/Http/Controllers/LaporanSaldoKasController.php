<?php

namespace App\Http\Controllers;

use App\Models\MutasiKas;
use App\Support\LatestLimitedPaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class LaporanSaldoKasController extends Controller
{
    private const TIPE_OPTIONS = ['masuk', 'keluar'];

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'tanggal_dari' => ['nullable', 'date'],
            'tanggal_sampai' => ['nullable', 'date', 'after_or_equal:tanggal_dari'],
            'tipe' => ['nullable', Rule::in(self::TIPE_OPTIONS)],
            'search' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'in:10,25,50,100'],
        ]);

        $tanggalDari = $validated['tanggal_dari'] ?? null;
        $tanggalSampai = $validated['tanggal_sampai'] ?? null;
        $tipe = $validated['tipe'] ?? null;
        $search = trim((string) ($validated['search'] ?? ''));
        $perPage = (int) ($validated['per_page'] ?? LatestLimitedPaginator::PER_PAGE_OPTIONS[0]);

        $mutasiQuery = MutasiKas::query()
            ->with([
                'jadwalLelang.barang.transaksi.nasabah',
                'transaksiGadai.nasabah',
                'cicilEmasTransaction.nasabah',
            ])
            ->when($tanggalDari, fn ($query) => $query->whereDate('tanggal', '>=', $tanggalDari))
            ->when($tanggalSampai, fn ($query) => $query->whereDate('tanggal', '<=', $tanggalSampai))
            ->when($tipe, fn ($query) => $query->where('tipe', $tipe))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('referensi', 'like', "%{$search}%")
                        ->orWhere('keterangan', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('tanggal')
            ->orderByDesc('id');

        $mutasiKas = LatestLimitedPaginator::fromQuery($mutasiQuery, $request, $perPage);

        $totalMasuk = $mutasiKas->getCollection()->where('tipe', 'masuk')->sum('jumlah');
        $totalKeluar = $mutasiKas->getCollection()->where('tipe', 'keluar')->sum('jumlah');
        $saldo = $totalMasuk - $totalKeluar;

        return view('laporan.saldo-kas', [
            'mutasiKas' => $mutasiKas,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'saldo' => $saldo,
            'tipeOptions' => self::TIPE_OPTIONS,
            'filters' => array_merge(
                Arr::only($validated, ['tanggal_dari', 'tanggal_sampai', 'tipe', 'search', 'per_page']),
                ['per_page' => $mutasiKas->perPage()],
            ),
            'perPageOptions' => LatestLimitedPaginator::PER_PAGE_OPTIONS,
        ]);
    }
}
