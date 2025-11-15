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
                'kode_group',
                'berat',
                'harga',
                'kadar',
                'created_at',
            ]);

        return view('barang.data-barang', [
            'barangs' => $barangs,
        ]);
    }

    public function create(): View
    {
        $masterSkus = MasterSku::query()
            ->orderBy('kode_group')
            ->get(['id', 'kode_group', 'harga']);

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
            'kode_group' => [
                'required',
                'string',
                'max:191',
                Rule::exists('master_skus', 'kode_group'),
            ],
        ]);

        $masterSku = MasterSku::query()->firstWhere('kode_group', $validated['kode_group']);

        if (! $masterSku) {
            throw ValidationException::withMessages([
                'kode_group' => __('Kode group tidak ditemukan pada master data.'),
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
            ->orderBy('kode_group')
            ->get(['id', 'kode_group', 'harga']);

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
            'kode_group' => [
                'required',
                'string',
                'max:191',
                Rule::exists('master_skus', 'kode_group'),
            ],
        ]);

        $masterSku = MasterSku::query()->firstWhere('kode_group', $validated['kode_group']);

        if (! $masterSku) {
            throw ValidationException::withMessages([
                'kode_group' => __('Kode group tidak ditemukan pada master data.'),
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
