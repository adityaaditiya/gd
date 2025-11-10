<?php

namespace App\Http\Controllers;

use App\Models\TransaksiGadai;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LaporanPelunasanGadaiController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));

        $transaksiLunas = TransaksiGadai::with([
            'nasabah',
            'kasir',
            'barangJaminan',
        ])
            ->where('status_transaksi', 'Lunas')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('no_sbg', 'like', "%{$search}%");
            })
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('laporan.pelunasan-gadai', [
            'transaksiLunas' => $transaksiLunas,
            'search' => $search,
        ]);
    }
}
