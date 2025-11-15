<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\MasterSku;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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
                'kode_baki',
                'kode_jenis',
                'berat',
                'harga',
                'kadar',
                'sku',
                'created_at',
            ]);

        return view('barang.data-barang', [
            'barangs' => $barangs,
        ]);
    }

    public function create(): View
    {
        $masterSkus = MasterSku::query()
            ->orderBy('sku')
            ->get(['id', 'sku', 'harga']);

        return view('barang.create', [
            'masterSkus' => $masterSkus,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_barcode' => ['required', 'string', 'max:191', 'unique:barangs,kode_barcode'],
            'nama_barang' => ['required', 'string', 'max:191'],
            'kode_intern' => ['required', 'string', 'max:191', 'unique:barangs,kode_intern'],
            'kode_baki' => ['required', 'string', 'max:191'],
            'kode_jenis' => ['required', 'string', 'max:191'],
            'berat' => ['required', 'numeric', 'min:0'],
            'kadar' => ['nullable', 'numeric', 'min:0'],
            'sku' => [
                'required',
                'string',
                'max:191',
                Rule::exists('master_skus', 'sku'),
                Rule::unique('barangs', 'sku'),
            ],
        ]);

        $masterSku = MasterSku::query()->firstWhere('sku', $validated['sku']);

        if (! $masterSku) {
            throw ValidationException::withMessages([
                'sku' => __('SKU tidak ditemukan pada master data.'),
            ]);
        }

        $validated['harga'] = $masterSku->harga;

        Barang::create($validated);

        return redirect()
            ->route('barang.data-barang')
            ->with('status', __('Data barang berhasil disimpan.'));
    }

    public function edit(Barang $barang): View
    {
        $masterSkus = MasterSku::query()
            ->orderBy('sku')
            ->get(['id', 'sku', 'harga']);

        return view('barang.edit', [
            'barang' => $barang,
            'masterSkus' => $masterSkus,
        ]);
    }

    public function update(Request $request, Barang $barang): RedirectResponse
    {
        $validated = $request->validate([
            'kode_barcode' => ['required', 'string', 'max:191', 'unique:barangs,kode_barcode,' . $barang->id],
            'nama_barang' => ['required', 'string', 'max:191'],
            'kode_intern' => ['required', 'string', 'max:191', 'unique:barangs,kode_intern,' . $barang->id],
            'kode_baki' => ['required', 'string', 'max:191'],
            'kode_jenis' => ['required', 'string', 'max:191'],
            'berat' => ['required', 'numeric', 'min:0'],
            'kadar' => ['nullable', 'numeric', 'min:0'],
            'sku' => [
                'required',
                'string',
                'max:191',
                Rule::exists('master_skus', 'sku'),
                Rule::unique('barangs', 'sku')->ignore($barang->id),
            ],
        ]);

        $masterSku = MasterSku::query()->firstWhere('sku', $validated['sku']);

        if (! $masterSku) {
            throw ValidationException::withMessages([
                'sku' => __('SKU tidak ditemukan pada master data.'),
            ]);
        }

        $validated['harga'] = $masterSku->harga;

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
