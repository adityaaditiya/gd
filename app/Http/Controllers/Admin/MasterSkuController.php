<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterSku;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MasterSkuController extends Controller
{
    public function index(): View
    {
        $masterSkus = MasterSku::query()
            ->orderBy('sku')
            ->get(['id', 'sku', 'harga', 'updated_at']);

        return view('admin.master-sku.index', [
            'masterSkus' => $masterSkus,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:191', 'unique:master_skus,sku'],
            'harga' => ['required', 'numeric', 'min:0'],
        ]);

        MasterSku::query()->create($validated);

        return redirect()
            ->route('admin.master-sku.index')
            ->with('status', __('Data SKU berhasil ditambahkan.'));
    }

    public function update(Request $request, MasterSku $masterSku): RedirectResponse
    {
        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:191', Rule::unique('master_skus', 'sku')->ignore($masterSku->id)],
            'harga' => ['required', 'numeric', 'min:0'],
        ]);

        $masterSku->update($validated);

        return redirect()
            ->route('admin.master-sku.index')
            ->with('status', __('Data SKU berhasil diperbarui.'));
    }

    public function destroy(MasterSku $masterSku): RedirectResponse
    {
        $masterSku->delete();

        return redirect()
            ->route('admin.master-sku.index')
            ->with('status', __('Data SKU berhasil dihapus.'));
    }
}
