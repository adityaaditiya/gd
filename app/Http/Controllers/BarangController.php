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

    public function create(): View
    {
        return view('barang.create');
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

    public function edit(Barang $barang): View
    {
        return view('barang.edit', [
            'barang' => $barang,
        ]);
    }

    public function update(Request $request, Barang $barang): RedirectResponse
    {
        $validated = $request->validate([
            'kode_barcode' => ['required', 'string', 'max:191', 'unique:barangs,kode_barcode,' . $barang->id],
            'nama_barang' => ['required', 'string', 'max:191'],
            'kode_intern' => ['required', 'string', 'max:191', 'unique:barangs,kode_intern,' . $barang->id],
            'kode_group' => ['required', 'string', 'max:191'],
            'berat' => ['required', 'numeric', 'min:0'],
            'harga' => ['required', 'numeric', 'min:0'],
        ]);

        $barang->update($validated);

        return redirect()
            ->route('barang.data-barang')
            ->with('status', __('Data barang berhasil diperbarui.'));
    }

    public function destroy(Barang $barang): RedirectResponse
    {
        $barang->delete();

        return redirect()
            ->route('barang.data-barang')
            ->with('status', __('Data barang berhasil dihapus.'));
    }
}
