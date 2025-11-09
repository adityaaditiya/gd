<?php

namespace App\Http\Controllers;

use App\Models\BarangJaminan;
use Illuminate\Contracts\View\View;

class BarangJaminanController extends Controller
{
    public function index(): View
    {
        $barangJaminan = BarangJaminan::with([
            'transaksi.nasabah',
            'transaksi.kasir',
            'penaksir',
        ])->latest('created_at')->paginate(15);

        return view('gadai.lihat-barang-gadai', [
            'barangJaminan' => $barangJaminan,
        ]);
    }
}
