<?php

namespace App\Http\Controllers;

use App\Models\TransaksiGadai;
use App\Support\LatestLimitedPaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LaporanPembatalanGadaiController extends Controller
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
            'pembatal',
            'barangJaminan',
        ])
            ->where('status_transaksi', 'Batal')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('no_sbg', 'like', "%{$search}%")
                        ->orWhereHas('nasabah', function ($nasabahQuery) use ($search) {
                            $nasabahQuery->where('nama', 'like', "%{$search}%")
                                ->orWhere('kode_member', 'like', "%{$search}%")
                                ->orWhere('telepon', 'like', "%{$search}%");
                        });
                });
            })
            ->latest('tanggal_batal');

        $transaksiBatal = LatestLimitedPaginator::fromQuery($transaksiQuery, $request);

        return view('laporan.batal-gadai', [
            'transaksiBatal' => $transaksiBatal,
            'search' => $search,
            'perPageOptions' => LatestLimitedPaginator::PER_PAGE_OPTIONS,
        ]);
    }
}
