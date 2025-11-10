<?php

namespace App\Http\Controllers;

use App\Models\TransaksiGadai;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LaporanPembatalanGadaiController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));

        $transaksiBatal = TransaksiGadai::with([
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
            ->latest('tanggal_batal')
            ->paginate(15)
            ->withQueryString();

        return view('laporan.batal-gadai', [
            'transaksiBatal' => $transaksiBatal,
            'search' => $search,
        ]);
    }
}
