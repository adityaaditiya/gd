<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterPerhitunganGadai;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MasterPerhitunganGadaiController extends Controller
{
    public function index(): View
    {
        $perhitunganList = MasterPerhitunganGadai::query()
            ->orderBy('range_awal')
            ->get();

        return view('admin.master-perhitungan-gadai.index', [
            'perhitunganList' => $perhitunganList,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);

        MasterPerhitunganGadai::query()->create($validated);

        return redirect()
            ->route('admin.master-perhitungan-gadai.index')
            ->with('status', __('Data perhitungan gadai berhasil ditambahkan.'));
    }

    public function update(Request $request, MasterPerhitunganGadai $masterPerhitunganGadai): RedirectResponse
    {
        $validated = $this->validateData($request, $masterPerhitunganGadai->id);

        $masterPerhitunganGadai->update($validated);

        return redirect()
            ->route('admin.master-perhitungan-gadai.index')
            ->with('status', __('Data perhitungan gadai berhasil diperbarui.'));
    }

    public function destroy(MasterPerhitunganGadai $masterPerhitunganGadai): RedirectResponse
    {
        $masterPerhitunganGadai->delete();

        return redirect()
            ->route('admin.master-perhitungan-gadai.index')
            ->with('status', __('Data perhitungan gadai berhasil dihapus.'));
    }

    private function validateData(Request $request, ?int $id = null): array
    {
        $typeRule = [
            'required',
            'string',
            'max:191',
            // Rule::unique('master_perhitungan_gadai', 'type')->ignore($id),
        ];

        $rules = [
            'type' => $typeRule,
            'range_awal' => ['required', 'numeric', 'min:0'],
            'range_akhir' => ['required', 'numeric', 'gt:range_awal'],
            'tarif_bunga_harian' => ['required', 'numeric', 'min:0', 'max:1'],
            'tenor_hari' => ['required', 'integer', 'min:1'],
            'jatuh_tempo_awal' => ['required', 'integer', 'min:1'],
            'biaya_admin' => ['required', 'numeric', 'min:0'],
        ];

        $messages = [
            'range_akhir.gt' => __('Range akhir harus lebih besar dari range awal.'),
        ];

        $validator = validator($request->all(), $rules, $messages);

        if ($id) {
            return $validator->validateWithBag('updateMasterPerhitungan_'.$id);
        }

        return $validator->validate();
    }
}
