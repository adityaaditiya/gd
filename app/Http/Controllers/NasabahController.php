<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class NasabahController extends Controller
{
    /**
     * Show the form for creating a new nasabah.
     */
    public function create(): View
    {
        return view('nasabah.tambah-nasabah');
    }

    /**
     * Display the Data Nasabah page.
     */
    public function index(Request $request): View|JsonResponse
    {
        $transform = fn (Nasabah $nasabah) => [
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
            'edit_url' => route('nasabah.edit', $nasabah),
            'delete_url' => route('nasabah.destroy', $nasabah),
        ];

        $query = Nasabah::query();

        if ($request->expectsJson()) {
            $searchTerm = trim((string) $request->query('search', ''));

            $nasabahs = $query
                ->when($searchTerm !== '', function ($builder) use ($searchTerm) {
                    $booleanTerm = strtolower($searchTerm);
                    $dateTerm = null;

                    if (preg_match('/^(\d{1,2})[\/-](\d{1,2})[\/-](\d{2,4})$/', $searchTerm, $matches)) {
                        $year = strlen($matches[3]) === 2 ? '20' . $matches[3] : $matches[3];
                        $dateTerm = sprintf('%04d-%02d-%02d', (int) $year, (int) $matches[2], (int) $matches[1]);
                    }

                    $builder->where(function ($query) use ($searchTerm, $booleanTerm, $dateTerm) {
                        $likeTerm = "%{$searchTerm}%";

                        $query
                            ->where('nik', 'like', $likeTerm)
                            ->orWhere('nama', 'like', $likeTerm)
                            ->orWhere('tempat_lahir', 'like', $likeTerm)
                            ->orWhere('telepon', 'like', $likeTerm)
                            ->orWhere('kota', 'like', $likeTerm)
                            ->orWhere('kelurahan', 'like', $likeTerm)
                            ->orWhere('kecamatan', 'like', $likeTerm)
                            ->orWhere('alamat', 'like', $likeTerm)
                            ->orWhere('npwp', 'like', $likeTerm)
                            ->orWhere('id_lain', 'like', $likeTerm)
                            ->orWhere('kode_member', 'like', $likeTerm);

                        if ($dateTerm) {
                            $query->orWhereDate('tanggal_lahir', $dateTerm);
                        } else {
                            $query->orWhere('tanggal_lahir', 'like', $likeTerm);
                        }

                        if (in_array($booleanTerm, ['ya', 'tidak'], true)) {
                            $query->orWhere('nasabah_lama', $booleanTerm === 'ya');
                        }
                    });
                })
                ->latest()
                ->get()
                ->map($transform)
                ->values();

            return response()->json([
                'data' => $nasabahs,
            ]);
        }

        $nasabahs = $query
            ->latest()
            ->limit(100)
            ->get()
            ->map($transform)
            ->values();

        return view('nasabah.data-nasabah', [
            'nasabahs' => $nasabahs,
        ]);
    }

    /**
     * Display the Nasabah Baru page with read-only data and filters.
     */
    public function nasabahBaru(Request $request): View|JsonResponse
    {
        $transform = fn (Nasabah $nasabah) => [
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
            'tanggal_pendaftaran' => optional($nasabah->created_at)->format('Y-m-d'),
        ];

        $sanitizeDate = static function (?string $value): ?Carbon {
            $value = trim((string) $value);

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                return null;
            }

            try {
                return Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
            } catch (\Throwable) {
                return null;
            }
        };

        $dateFrom = $sanitizeDate($request->query('date_from'));
        $dateTo = $sanitizeDate($request->query('date_to'));
        $searchTerm = trim((string) $request->query('search', ''));

        $query = Nasabah::query()
            ->where('nasabah_lama', false)
            ->when($dateFrom, fn ($builder) => $builder->whereDate('created_at', '>=', $dateFrom->toDateString()))
            ->when($dateTo, fn ($builder) => $builder->whereDate('created_at', '<=', $dateTo->toDateString()))
            ->when($searchTerm !== '', function ($builder) use ($searchTerm) {
                $likeTerm = "%{$searchTerm}%";
                $dateTerm = null;

                if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $searchTerm, $matches)) {
                    $dateTerm = sprintf('%04d-%02d-%02d', (int) $matches[1], (int) $matches[2], (int) $matches[3]);
                } elseif (preg_match('/^(\d{1,2})[\/-](\d{1,2})[\/-](\d{2,4})$/', $searchTerm, $matches)) {
                    $year = strlen($matches[3]) === 2 ? '20' . $matches[3] : $matches[3];
                    $dateTerm = sprintf('%04d-%02d-%02d', (int) $year, (int) $matches[2], (int) $matches[1]);
                }

                $builder->where(function ($query) use ($likeTerm, $dateTerm) {
                    $query
                        ->where('nik', 'like', $likeTerm)
                        ->orWhere('nama', 'like', $likeTerm)
                        ->orWhere('tempat_lahir', 'like', $likeTerm)
                        ->orWhere('telepon', 'like', $likeTerm)
                        ->orWhere('kota', 'like', $likeTerm)
                        ->orWhere('kelurahan', 'like', $likeTerm)
                        ->orWhere('kecamatan', 'like', $likeTerm)
                        ->orWhere('alamat', 'like', $likeTerm)
                        ->orWhere('npwp', 'like', $likeTerm)
                        ->orWhere('id_lain', 'like', $likeTerm)
                        ->orWhere('kode_member', 'like', $likeTerm);

                    if ($dateTerm) {
                        $query
                            ->orWhereDate('tanggal_lahir', $dateTerm)
                            ->orWhereDate('created_at', $dateTerm);
                    } else {
                        $query
                            ->orWhere('tanggal_lahir', 'like', $likeTerm)
                            ->orWhere('created_at', 'like', $likeTerm);
                    }
                });
            });

        if ($request->expectsJson()) {
            $nasabahs = $query
                ->latest('created_at')
                ->limit(200)
                ->get()
                ->map($transform)
                ->values();

            return response()->json([
                'data' => $nasabahs,
            ]);
        }

        $nasabahs = $query
            ->latest('created_at')
            ->limit(100)
            ->get()
            ->map($transform)
            ->values();

        return view('nasabah.nasabah-baru', [
            'nasabahs' => $nasabahs,
            'filters' => [
                'date_from' => optional($dateFrom)?->toDateString(),
                'date_to' => optional($dateTo)?->toDateString(),
                'search' => $searchTerm,
            ],
        ]);
    }

    /**
     * Store a newly created nasabah in storage.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $ageLimit = now()->subYears(17)->format('Y-m-d');

        $validated = $request->validate([
            'nik' => ['required', 'string', 'max:50', 'unique:nasabahs,nik'],
            'nama' => ['required', 'string', 'max:255'],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:' . $ageLimit],
            'telepon' => ['required', 'string', 'max:50'],
            'kota' => ['required', 'string', 'max:255'],
            'kelurahan' => ['required', 'string', 'max:255'],
            'kecamatan' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'npwp' => ['nullable', 'string', 'max:50'],
            'id_lain' => ['nullable', 'string', 'max:50'],
            'nasabah_lama' => ['nullable', 'boolean'],
        ], [
            'tanggal_lahir.before_or_equal' => __('Data tidak bisa disimpan karena usia nasabah minimal 17 tahun.'),
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
            'edit_url' => route('nasabah.edit', $nasabah),
            'delete_url' => route('nasabah.destroy', $nasabah),
        ];

        if ($request->wantsJson()) {
            return response()->json([
                'message' => __('Data nasabah berhasil disimpan.'),
                'nasabah' => $payload,
            ], 201);
        }

        $redirectTo = $request->input('redirect_to');
        $flash = [
            'status' => __('Data nasabah berhasil disimpan.'),
            'kode_member' => $nasabah->kode_member,
        ];

        if ($redirectTo && Route::has($redirectTo)) {
            return redirect()->route($redirectTo)->with($flash);
        }

        return redirect()
            ->route('nasabah.data-nasabah')
            ->with($flash);
    }

    /**
     * Show the form for editing the specified nasabah.
     */
    public function edit(Nasabah $nasabah): View
    {
        return view('nasabah.edit-nasabah', [
            'nasabah' => $nasabah,
        ]);
    }

    /**
     * Update the specified nasabah in storage.
     */
    public function update(Request $request, Nasabah $nasabah): RedirectResponse
    {
        $ageLimit = now()->subYears(17)->format('Y-m-d');

        $validated = $request->validate([
            'nik' => ['required', 'string', 'max:50', 'unique:nasabahs,nik,' . $nasabah->id],
            'nama' => ['required', 'string', 'max:255'],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:' . $ageLimit],
            'telepon' => ['required', 'string', 'max:50'],
            'kota' => ['required', 'string', 'max:255'],
            'kelurahan' => ['required', 'string', 'max:255'],
            'kecamatan' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'npwp' => ['nullable', 'string', 'max:50'],
            'id_lain' => ['nullable', 'string', 'max:50'],
            'nasabah_lama' => ['nullable', 'boolean'],
        ], [
            'tanggal_lahir.before_or_equal' => __('Data tidak bisa disimpan karena usia nasabah minimal 17 tahun.'),
        ]);

        $validated['nasabah_lama'] = $request->boolean('nasabah_lama');

        $nasabah->update($validated);

        return redirect()
            ->route('nasabah.data-nasabah')
            ->with('status', __('Data nasabah berhasil diperbarui.'));
    }

    /**
     * Remove the specified nasabah from storage.
     */
    public function destroy(Nasabah $nasabah): RedirectResponse
    {
        $nasabah->delete();

        return redirect()
            ->route('nasabah.data-nasabah')
            ->with('status', __('Data nasabah berhasil dihapus.'));
    }
}
