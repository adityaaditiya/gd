<?php

namespace App\Http\Controllers;

use App\Models\BarangJaminan;
use App\Models\TransaksiGadai;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    public function create(): View
    {
        return view('gadai.barang-jaminan.create', [
            'penaksirList' => $this->penaksirList(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateForm($request);

        $transaksi = TransaksiGadai::where('no_sbg', $validated['no_sbg'])->first();

        if (! $transaksi) {
            return back()
                ->withInput()
                ->with('error', __('Nomor SBG tidak ditemukan pada daftar transaksi gadai.'));
        }

        BarangJaminan::create($this->mapAttributes($validated, $transaksi->getKey()));

        return redirect()
            ->route('gadai.lihat-barang-gadai')
            ->with('status', __('Barang jaminan berhasil ditambahkan.'));
    }

    public function edit(BarangJaminan $barangJaminan): View
    {
        $barangJaminan->load('transaksi');

        return view('gadai.barang-jaminan.edit', [
            'barangJaminan' => $barangJaminan,
            'penaksirList' => $this->penaksirList(),
        ]);
    }

    public function update(Request $request, BarangJaminan $barangJaminan): RedirectResponse
    {
        $validated = $this->validateForm($request);

        $transaksi = TransaksiGadai::where('no_sbg', $validated['no_sbg'])->first();

        if (! $transaksi) {
            return back()
                ->withInput()
                ->with('error', __('Nomor SBG tidak ditemukan pada daftar transaksi gadai.'));
        }

        $barangJaminan->update($this->mapAttributes($validated, $transaksi->getKey()));

        return redirect()
            ->route('gadai.lihat-barang-gadai')
            ->with('status', __('Barang jaminan berhasil diperbarui.'));
    }

    /**
     * @return \Illuminate\Support\Collection<int, \App\Models\User>
     */
    protected function penaksirList()
    {
        return User::query()
            ->where('role', User::ROLE_PENAKSIR)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    protected function mapAttributes(array $validated, int $transaksiId): array
    {
        $hps = round((float) $validated['hps'], 2);
        $nilaiTaksiran = round($hps * 0.94, 2);

        return [
            'transaksi_id' => $transaksiId,
            'pegawai_penaksir_id' => $validated['pegawai_penaksir_id'] ?? null,
            'jenis_barang' => $validated['jenis_barang'],
            'merek' => $validated['merek'],
            'usia_barang_thn' => $validated['usia_barang_thn'] ?? null,
            'hps' => $hps,
            'nilai_taksiran' => $nilaiTaksiran,
            'kondisi_fisik' => $validated['kondisi_fisik'] ?? null,
            'foto_1' => $validated['foto_1'] ?? null,
            'foto_2' => $validated['foto_2'] ?? null,
            'foto_3' => $validated['foto_3'] ?? null,
            'foto_4' => $validated['foto_4'] ?? null,
            'foto_5' => $validated['foto_5'] ?? null,
            'foto_6' => $validated['foto_6'] ?? null,
        ];
    }

    protected function validateForm(Request $request): array
    {
        return $request->validate([
            'no_sbg' => [
                'required',
                'string',
                'max:191',
                Rule::exists('transaksi_gadai', 'no_sbg'),
            ],
            'jenis_barang' => ['required', 'string', 'max:191'],
            'merek' => ['required', 'string', 'max:191'],
            'usia_barang_thn' => ['nullable', 'integer', 'min:0', 'max:100'],
            'hps' => ['required', 'numeric', 'min:0'],
            'pegawai_penaksir_id' => [
                'nullable',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', User::ROLE_PENAKSIR)),
            ],
            'kondisi_fisik' => ['nullable', 'string'],
            'foto_1' => ['nullable', 'string', 'max:255'],
            'foto_2' => ['nullable', 'string', 'max:255'],
            'foto_3' => ['nullable', 'string', 'max:255'],
            'foto_4' => ['nullable', 'string', 'max:255'],
            'foto_5' => ['nullable', 'string', 'max:255'],
            'foto_6' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
