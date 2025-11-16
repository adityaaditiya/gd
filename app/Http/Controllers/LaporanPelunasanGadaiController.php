<?php

namespace App\Http\Controllers;

use App\Models\TransaksiGadai;
use App\Support\LatestLimitedPaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LaporanPelunasanGadaiController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', Rule::in(LatestLimitedPaginator::PER_PAGE_OPTIONS)],
        ]);

        $search = trim((string) ($validated['search'] ?? ''));

        $transaksiQuery = TransaksiGadai::with([
            'nasabah',
            'kasir',
            'barangJaminan',
            'petugasPelunasan',
        ])
            ->where('status_transaksi', 'Lunas')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('no_sbg', 'like', "%{$search}%");
            })
            ->latest('tanggal_pelunasan');

        $transaksiLunas = LatestLimitedPaginator::fromQuery($transaksiQuery, $request);

        return view('laporan.pelunasan-gadai', [
            'transaksiLunas' => $transaksiLunas,
            'search' => $search,
            'perPageOptions' => LatestLimitedPaginator::PER_PAGE_OPTIONS,
        ]);
    }
}
