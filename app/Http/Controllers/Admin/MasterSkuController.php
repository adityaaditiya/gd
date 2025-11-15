<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MasterSkuController extends Controller
{
    public function index(): View
    {
        $barangs = Barang::query()
            ->orderBy('nama_barang')
            ->get([
                'id',
                'nama_barang',
                'kode_intern',
                'sku',
                'harga',
            ]);

        return view('admin.master-sku.index', [
            'barangs' => $barangs,
        ]);
    }

    public function update(Request $request, Barang $barang): RedirectResponse
    {
        $validated = $request->validate([
            'sku' => ['nullable', 'string', 'max:191', 'unique:barangs,sku,' . $barang->id],
            'harga' => ['required', 'numeric', 'min:0'],
        ]);

        $barang->update($validated);

        return redirect()
            ->route('admin.master-sku.index')
            ->with('status', __('SKU dan harga barang berhasil diperbarui.'));
    }
}
