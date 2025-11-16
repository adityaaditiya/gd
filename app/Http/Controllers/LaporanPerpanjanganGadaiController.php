<?php

namespace App\Http\Controllers;

use App\Models\PerpanjanganGadai;
use App\Support\LatestLimitedPaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LaporanPerpanjanganGadaiController extends Controller
{
    public function index(Request $request): View
    {
        $validator = Validator::make($request->all(), [
            'search' => ['nullable', 'string', 'max:255'],
            'tanggal_dari' => ['nullable', 'date'],
            'tanggal_sampai' => ['nullable', 'date', 'after_or_equal:tanggal_dari'],
            'per_page' => ['nullable', 'integer', Rule::in(LatestLimitedPaginator::PER_PAGE_OPTIONS)],
        ]);

        $filters = $validator->safe()->only(['search', 'tanggal_dari', 'tanggal_sampai']);
        $search = trim((string) ($filters['search'] ?? ''));
        $tanggalDari = $filters['tanggal_dari'] ?? null;
        $tanggalSampai = $filters['tanggal_sampai'] ?? null;

        $riwayatQuery = PerpanjanganGadai::query()
            ->with([
                'transaksi.nasabah',
                'transaksi.kasir',
                'petugas',
                'pembatal',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('transaksi', function ($transaksiQuery) use ($search) {
                    $transaksiQuery
                        ->where('no_sbg', 'like', "%{$search}%")
                        ->orWhereHas('nasabah', function ($nasabahQuery) use ($search) {
                            $nasabahQuery->where('nama', 'like', "%{$search}%")
                                ->orWhere('kode_member', 'like', "%{$search}%")
                                ->orWhere('telepon', 'like', "%{$search}%");
                        });
                });
            })
            ->when($tanggalDari, function ($query) use ($tanggalDari) {
                $query->whereDate('tanggal_perpanjangan', '>=', $tanggalDari);
            })
            ->when($tanggalSampai, function ($query) use ($tanggalSampai) {
                $query->whereDate('tanggal_perpanjangan', '<=', $tanggalSampai);
            })
            ->latest('tanggal_perpanjangan');

        $riwayat = LatestLimitedPaginator::fromQuery($riwayatQuery, $request);

        return view('laporan.perpanjangan-gadai', [
            'riwayat' => $riwayat,
            'search' => $search,
            'tanggalDari' => $tanggalDari,
            'tanggalSampai' => $tanggalSampai,
            'perPageOptions' => LatestLimitedPaginator::PER_PAGE_OPTIONS,
        ]);
    }
}
