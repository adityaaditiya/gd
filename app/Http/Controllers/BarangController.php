<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\MasterKodeGroup;
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
            ->select([
                'id',
                'kode_barcode',
                'nama_barang',
                'kode_intern',
                'kode_baki',
                'kode_jenis',
                'berat',
                'harga',
                'kadar',
                'kode_group',
                'created_at',
            ])
            ->withCount([
                'cicilEmasItems as active_cicil_emas_usage_count' => fn ($query) => $query
                    ->whereHas('transaction', fn ($transaction) => $transaction->whereNull('dibatalkan_pada')),
            ])
            ->orderBy('nama_barang')
            ->get();

        return view('barang.data-barang', [
            'barangs' => $barangs,
        ]);
    }

    public function create(): View
    {
        $masterKodeGroups = MasterKodeGroup::query()
            ->orderBy('kode_group')
            ->get(['id', 'kode_group', 'harga']);

        return view('barang.create', [
            'masterKodeGroups' => $masterKodeGroups,
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
                Rule::exists('master_kode_groups', 'kode_group'),
            ],
        ]);

        $masterKodeGroup = MasterKodeGroup::query()->firstWhere('kode_group', $validated['kode_group']);

        if (! $masterKodeGroup) {
            throw ValidationException::withMessages([
                'kode_group' => __('Kode group tidak ditemukan pada master data.'),
            ]);
        }

        $validated['harga'] = $masterKodeGroup->harga;

        Barang::create($validated);

        return redirect()
            ->route('barang.data-barang')
            ->with('status', __('Data barang berhasil disimpan.'));
    }

    public function edit(Barang $barang): View|RedirectResponse
    {
        if ($barang->is_locked) {
            return redirect()
                ->route('barang.data-barang')
                ->with('error', __('Barang ini sudah digunakan dalam transaksi emas dan tidak dapat diubah.'));
        }

        $masterKodeGroups = MasterKodeGroup::query()
            ->orderBy('kode_group')
            ->get(['id', 'kode_group', 'harga']);

        return view('barang.edit', [
            'barang' => $barang,
            'masterKodeGroups' => $masterKodeGroups,
        ]);
    }

    public function update(Request $request, Barang $barang): RedirectResponse
    {
        if ($barang->is_locked) {
            return redirect()
                ->route('barang.data-barang')
                ->with('error', __('Barang ini sudah digunakan dalam transaksi emas dan tidak dapat diubah.'));
        }

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
                Rule::exists('master_kode_groups', 'kode_group'),
            ],
        ]);

        $masterKodeGroup = MasterKodeGroup::query()->firstWhere('kode_group', $validated['kode_group']);

        if (! $masterKodeGroup) {
            throw ValidationException::withMessages([
                'kode_group' => __('Kode group tidak ditemukan pada master data.'),
            ]);
        }

        $validated['harga'] = $masterKodeGroup->harga;

        $barang->update($validated);

        return redirect()
            ->route('barang.data-barang')
            ->with('status', __('Data barang berhasil diperbarui.'));
    }

    public function destroy(Barang $barang): RedirectResponse
    {
        if ($barang->is_locked) {
            return redirect()
                ->route('barang.data-barang')
                ->with('error', __('Barang ini sudah digunakan dalam transaksi emas dan tidak dapat dihapus.'));
        }

        $barang->delete();

        return redirect()
            ->route('barang.data-barang')
            ->with('status', __('Data barang berhasil dihapus.'));
    }
}
