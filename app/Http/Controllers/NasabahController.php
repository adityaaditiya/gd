<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use Carbon\Exceptions\InvalidFormatException;
use Closure;
use Illuminate\Database\Eloquent\Builder;
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
        return $this->nasabahListing($request);
    }

    /**
     * Display the Nasabah Baru page, filtered to nasabah_lama = 1.
     */
    public function nasabahBaru(Request $request): View|JsonResponse
    {
        return $this->nasabahListing(
            $request,
            function (Builder $query): void {
                $query->where('nasabah_lama', true);
            },
            [
                'pageTitle' => __('Nasabah Baru'),
                'searchEndpoint' => route('nasabah.nasabah-baru'),
                'showCreateButton' => false,
            ]
        );
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

        $payload = $this->transformNasabah($nasabah);

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

    /**
     * Build the listing response for nasabah resources.
     */
    private function nasabahListing(Request $request, ?Closure $scope = null, array $viewData = []): View|JsonResponse
    {
        $query = Nasabah::query();

        if ($scope) {
            $scope($query);
        }

        [$dateFrom, $dateTo] = $this->extractDateRange($request);

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        if ($request->expectsJson()) {
            $searchTerm = trim((string) $request->query('search', ''));

            if ($searchTerm !== '') {
                $this->applyNasabahSearchFilter($query, $searchTerm);
            }

            $nasabahs = $query
                ->latest()
                ->get()
                ->map(fn (Nasabah $nasabah) => $this->transformNasabah($nasabah))
                ->values();

            return response()->json([
                'data' => $nasabahs,
            ]);
        }

        $nasabahs = $query
            ->latest()
            ->limit(100)
            ->get()
            ->map(fn (Nasabah $nasabah) => $this->transformNasabah($nasabah))
            ->values();

        return view('nasabah.data-nasabah', array_merge([
            'nasabahs' => $nasabahs,
            'showCreateButton' => $viewData['showCreateButton'] ?? true,
            'activeDateFrom' => $dateFrom,
            'activeDateTo' => $dateTo,
        ], $viewData));
    }

    /**
     * Apply search filters for nasabah listing queries.
     */
    private function applyNasabahSearchFilter(Builder $query, string $searchTerm): void
    {
        $booleanTerm = strtolower($searchTerm);
        $dateTerm = null;

        if (preg_match('/^(\d{1,2})[\/-](\d{1,2})[\/-](\d{2,4})$/', $searchTerm, $matches)) {
            $year = strlen($matches[3]) === 2 ? '20' . $matches[3] : $matches[3];
            $dateTerm = sprintf('%04d-%02d-%02d', (int) $year, (int) $matches[2], (int) $matches[1]);
        }

        $query->where(function (Builder $builder) use ($searchTerm, $booleanTerm, $dateTerm) {
            $likeTerm = "%{$searchTerm}%";

            $builder
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
                $builder->orWhereDate('tanggal_lahir', $dateTerm);
            } else {
                $builder->orWhere('tanggal_lahir', 'like', $likeTerm);
            }

            if (in_array($booleanTerm, ['ya', 'tidak'], true)) {
                $builder->orWhere('nasabah_lama', $booleanTerm === 'ya');
            }
        });
    }

    /**
     * Transform a nasabah model for responses.
     */
    private function transformNasabah(Nasabah $nasabah): array
    {
        return [
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
            'created_at' => optional($nasabah->created_at)->toIso8601String(),
            'edit_url' => route('nasabah.edit', $nasabah),
            'delete_url' => route('nasabah.destroy', $nasabah),
        ];
    }

    /**
     * Resolve sanitized date range filter values from the request.
     *
     * @return array{0: string|null, 1: string|null}
     */
    private function extractDateRange(Request $request): array
    {
        $dateFrom = $this->normalizeDate((string) $request->query('date_from', ''));
        $dateTo = $this->normalizeDate((string) $request->query('date_to', ''));

        if ($dateFrom && $dateTo && $dateFrom > $dateTo) {
            [$dateFrom, $dateTo] = [$dateTo, $dateFrom];
        }

        return [$dateFrom, $dateTo];
    }

    private function normalizeDate(?string $value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        try {
            return Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d');
        } catch (InvalidFormatException) {
            return null;
        }
    }
}
