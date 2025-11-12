<?php

namespace App\Http\Controllers;

use App\Models\TransaksiGadai;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LaporanSaldoKasController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));

        $transaksiQuery = TransaksiGadai::with([
            'nasabah',
            'kasir',
        ])->where(function ($query) {
            $query->whereNull('status_transaksi')
                ->orWhere('status_transaksi', '!=', 'Batal');
        });

        if ($search !== '') {
            $transaksiQuery->where(function ($query) use ($search) {
                $query->where('no_sbg', 'like', "%{$search}%")
                    ->orWhereHas('nasabah', function ($nasabahQuery) use ($search) {
                        $nasabahQuery->where('nama', 'like', "%{$search}%")
                            ->orWhere('kode_member', 'like', "%{$search}%")
                            ->orWhere('telepon', 'like', "%{$search}%");
                    })
                    ->orWhereHas('kasir', function ($kasirQuery) use ($search) {
                        $kasirQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $transaksiGadai = (clone $transaksiQuery)
            ->latest('tanggal_gadai')
            ->paginate(15)
            ->withQueryString();

        $totalKasKeluar = (clone $transaksiQuery)->sum('uang_pinjaman');
        $totalKasMasuk = (clone $transaksiQuery)
            ->whereNotNull('total_pelunasan')
            ->sum('total_pelunasan');

        return view('laporan.saldo-kas', [
            'transaksiGadai' => $transaksiGadai,
            'totalKasKeluar' => $totalKasKeluar,
            'totalKasMasuk' => $totalKasMasuk,
            'search' => $search,
        ]);
    }
}
