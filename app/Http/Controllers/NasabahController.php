<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NasabahController extends Controller
{
    /**
     * Display the Data Nasabah page.
     */
    public function index(Request $request): View
    {
        $nasabahs = Nasabah::query()
            ->orderBy('nama')
            ->get()
            ->map(fn (Nasabah $nasabah) => [
                'id' => $nasabah->id,
                'nik' => $nasabah->nik,
                'nama' => $nasabah->nama,
                'tempat_lahir' => $nasabah->tempat_lahir,
                'tanggal_lahir' => optional($nasabah->tanggal_lahir)->format('Y-m-d'),
                'telepon' => $nasabah->telepon,
                'kota' => $nasabah->kota,
                'kelurahan' => $nasabah->kelurahan,
                'kecamatan' => $nasabah->kecamatan,
                'alamat' => $nasabah->alamat,
                'npwp' => $nasabah->npwp,
                'id_lain' => $nasabah->id_lain,
                'nasabah_lama' => $nasabah->nasabah_lama,
                'kode_member' => $nasabah->kode_member,
            ])
            ->values();

        return view('nasabah.data-nasabah', [
            'nasabahs' => $nasabahs,
        ]);
    }

    /**
     * Store a newly created nasabah in storage.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'nik' => ['required', 'string', 'max:50', 'unique:nasabahs,nik'],
            'nama' => ['required', 'string', 'max:255'],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['required', 'date'],
            'telepon' => ['required', 'string', 'max:50'],
            'kota' => ['nullable', 'string', 'max:255'],
            'kelurahan' => ['nullable', 'string', 'max:255'],
            'kecamatan' => ['nullable', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'npwp' => ['nullable', 'string', 'max:50'],
            'id_lain' => ['nullable', 'string', 'max:50'],
            'nasabah_lama' => ['nullable', 'boolean'],
        ]);

        $validated['nasabah_lama'] = $request->boolean('nasabah_lama');
        $validated['kode_member'] = Nasabah::generateKodeMember();

        $nasabah = Nasabah::create($validated);

        $payload = [
            'id' => $nasabah->id,
            'nik' => $nasabah->nik,
            'nama' => $nasabah->nama,
            'tempat_lahir' => $nasabah->tempat_lahir,
            'tanggal_lahir' => optional($nasabah->tanggal_lahir)->format('Y-m-d'),
            'telepon' => $nasabah->telepon,
            'kota' => $nasabah->kota,
            'kelurahan' => $nasabah->kelurahan,
            'kecamatan' => $nasabah->kecamatan,
            'alamat' => $nasabah->alamat,
            'npwp' => $nasabah->npwp,
            'id_lain' => $nasabah->id_lain,
            'nasabah_lama' => $nasabah->nasabah_lama,
            'kode_member' => $nasabah->kode_member,
        ];

        if ($request->wantsJson()) {
            return response()->json([
                'message' => __('Data nasabah berhasil disimpan.'),
                'nasabah' => $payload,
            ], 201);
        }

        return redirect()
            ->route('nasabah.data-nasabah')
            ->with('status', __('Data nasabah berhasil disimpan.'));
    }
}
