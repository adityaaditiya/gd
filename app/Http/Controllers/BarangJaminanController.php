<?php

namespace App\Http\Controllers;

use App\Models\BarangJaminan;
use App\Models\TransaksiGadai;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'transaksiList' => $this->getTransaksiOptions(),
            'penaksirList' => $this->getPenaksirOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        $barangJaminan = new BarangJaminan($data);
        $this->handlePhotoUploads($request, $barangJaminan);
        $barangJaminan->save();

        return redirect()
            ->route('gadai.lihat-barang-gadai')
            ->with('status', __('Barang jaminan berhasil ditambahkan.'));
    }

    public function edit(BarangJaminan $barangJaminan): View
    {
        return view('gadai.barang-jaminan.edit', [
            'barangJaminan' => $barangJaminan,
            'transaksiList' => $this->getTransaksiOptions(),
            'penaksirList' => $this->getPenaksirOptions(),
        ]);
    }

    public function update(Request $request, BarangJaminan $barangJaminan): RedirectResponse
    {
        $data = $this->validateData($request);

        $barangJaminan->fill($data);
        $this->handlePhotoUploads($request, $barangJaminan, true);
        $barangJaminan->save();

        return redirect()
            ->route('gadai.lihat-barang-gadai')
            ->with('status', __('Barang jaminan berhasil diperbarui.'));
    }

    public function destroy(BarangJaminan $barangJaminan): RedirectResponse
    {
        $this->deleteAllPhotos($barangJaminan);
        $barangJaminan->delete();

        return redirect()
            ->route('gadai.lihat-barang-gadai')
            ->with('status', __('Barang jaminan berhasil dihapus.'));
    }

    private function validateData(Request $request): array
    {
        $validated = $request->validate([
            'transaksi_id' => ['required', 'exists:transaksi_gadai,transaksi_id'],
            'pegawai_penaksir_id' => ['nullable', 'exists:users,id'],
            'jenis_barang' => ['required', 'string', 'max:255'],
            'merek' => ['required', 'string', 'max:255'],
            'usia_barang_thn' => ['nullable', 'integer', 'min:0', 'max:120'],
            'hps' => ['required', 'string'],
            'nilai_taksiran' => ['required', 'string'],
            'kondisi_fisik' => ['nullable', 'string'],
            'foto_1' => ['nullable', 'image', 'max:2048'],
            'foto_2' => ['nullable', 'image', 'max:2048'],
            'foto_3' => ['nullable', 'image', 'max:2048'],
            'foto_4' => ['nullable', 'image', 'max:2048'],
            'foto_5' => ['nullable', 'image', 'max:2048'],
            'foto_6' => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['hps'] = $this->toDecimalString($validated['hps']);
        $validated['nilai_taksiran'] = $this->toDecimalString($validated['nilai_taksiran']);

        if (($validated['pegawai_penaksir_id'] ?? null) === '') {
            $validated['pegawai_penaksir_id'] = null;
        }

        foreach (range(1, 6) as $index) {
            unset($validated['foto_' . $index]);
        }

        return $validated;
    }

    private function handlePhotoUploads(Request $request, BarangJaminan $barangJaminan, bool $replaceExisting = false): void
    {
        foreach (range(1, 6) as $index) {
            $key = 'foto_' . $index;

            if ($request->hasFile($key)) {
                if ($replaceExisting) {
                    $this->deletePhoto($barangJaminan->{$key});
                }

                $storedPath = $request->file($key)->store('barang-jaminan', 'public');
                $barangJaminan->{$key} = $storedPath;
            }
        }
    }

    private function deleteAllPhotos(BarangJaminan $barangJaminan): void
    {
        foreach (range(1, 6) as $index) {
            $this->deletePhoto($barangJaminan->{'foto_' . $index});
        }
    }

    private function deletePhoto(?string $path): void
    {
        if (!$path) {
            return;
        }

        $diskPath = $this->normalizeStoragePath($path);

        if ($diskPath !== null) {
            Storage::disk('public')->delete($diskPath);
        }
    }

    private function normalizeStoragePath(string $path): ?string
    {
        $path = trim($path);

        if ($path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            $parsed = parse_url($path, PHP_URL_PATH);
            $path = $parsed ?: $path;
        }

        if (str_starts_with($path, '/')) {
            $path = ltrim($path, '/');
        }

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        return $path !== '' ? $path : null;
    }

    private function toDecimalString(string $value): string
    {
        $value = trim($value);

        if ($value === '') {
            return '0.00';
        }

        $value = preg_replace('/[^0-9,.-]/', '', $value) ?? '0';
        $lastComma = strrpos($value, ',');
        $lastDot = strrpos($value, '.');

        if ($lastComma !== false && $lastDot !== false) {
            if ($lastComma > $lastDot) {
                $value = str_replace('.', '', $value);
                $value = str_replace(',', '.', $value);
            } else {
                $value = str_replace(',', '', $value);
            }
        } elseif ($lastComma !== false) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        } else {
            $value = str_replace(',', '', $value);
        }

        return number_format((float) $value, 2, '.', '');
    }

    private function getTransaksiOptions()
    {
        return TransaksiGadai::with('nasabah')
            ->orderByDesc('tanggal_gadai')
            ->orderByDesc('created_at')
            ->get();
    }

    private function getPenaksirOptions()
    {
        return User::query()
            ->where('role', User::ROLE_PENAKSIR)
            ->orderBy('name')
            ->get();
    }
}
