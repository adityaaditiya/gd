<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BarangController extends Controller
{
    public function index(): View
    {
        $barangs = Barang::query()
            ->orderBy('nama_barang')
            ->get([
                'id',
                'kode_barcode',
                'nama_barang',
                'kode_intern',
                'kode_group',
                'berat',
                'harga',
                'created_at',
            ]);

        return view('barang.data-barang', [
            'barangs' => $barangs,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_barcode' => ['required', 'string', 'max:191', 'unique:barangs,kode_barcode'],
            'nama_barang' => ['required', 'string', 'max:191'],
            'kode_intern' => ['required', 'string', 'max:191', 'unique:barangs,kode_intern'],
            'kode_group' => ['required', 'string', 'max:191'],
            'berat' => ['required', 'numeric', 'min:0'],
            'harga' => ['required', 'numeric', 'min:0'],
        ]);

        Barang::create($validated);

        return redirect()
            ->route('barang.data-barang')
            ->with('status', __('Data barang berhasil disimpan.'));
    }
}
