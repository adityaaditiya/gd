<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterKodeGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MasterKodeGroupController extends Controller
{
    public function index(): View
    {
        $masterKodeGroups = MasterKodeGroup::query()
            ->orderBy('kode_group')
            ->get(['id', 'kode_group', 'harga', 'updated_at']);

        return view('admin.master-kode-group.index', [
            'masterKodeGroups' => $masterKodeGroups,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_group' => ['required', 'string', 'max:191', 'unique:master_kode_groups,kode_group'],
            'harga' => ['required', 'numeric', 'min:0'],
        ]);

        MasterKodeGroup::query()->create($validated);

        return redirect()
            ->route('admin.master-kode-group.index')
            ->with('status', __('Data kode group berhasil ditambahkan.'));
    }

    public function update(Request $request, MasterKodeGroup $masterKodeGroup): RedirectResponse
    {
        $validated = $request->validate([
            'kode_group' => ['required', 'string', 'max:191', Rule::unique('master_kode_groups', 'kode_group')->ignore($masterKodeGroup->id)],
            'harga' => ['required', 'numeric', 'min:0'],
        ]);

        $masterKodeGroup->update($validated);

        return redirect()
            ->route('admin.master-kode-group.index')
            ->with('status', __('Data kode group berhasil diperbarui.'));
    }

    public function destroy(MasterKodeGroup $masterKodeGroup): RedirectResponse
    {
        $masterKodeGroup->delete();

        return redirect()
            ->route('admin.master-kode-group.index')
            ->with('status', __('Data kode group berhasil dihapus.'));
    }
}
