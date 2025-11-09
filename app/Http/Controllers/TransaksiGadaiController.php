<?php

namespace App\Http\Controllers;

use App\Models\BarangJaminan;
use App\Models\Nasabah;
use App\Models\TransaksiGadai;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TransaksiGadaiController extends Controller
{
    public function index(Request $request): View
    {
        $transaksiList = TransaksiGadai::query()
            ->with('nasabah')
            ->latest('tanggal_gadai')
            ->latest('created_at')
            ->get();

        return view('transaksi-gadai.index', [
            'transaksiList' => $transaksiList,
        ]);
    }

    public function create(Request $request): View
    {
        $barangSiapGadai = BarangJaminan::query()
            ->whereNull('transaksi_id')
            ->orderBy('jenis_barang')
            ->orderBy('merek')
            ->get();

        $nasabahList = Nasabah::query()
            ->orderBy('nama')
            ->get();

        return view('transaksi-gadai.create', [
            'barangSiapGadai' => $barangSiapGadai,
            'nasabahList' => $nasabahList,
            'defaultTanggalGadai' => now()->toDateString(),
            'defaultJatuhTempo' => now()->addDays(30)->toDateString(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'no_sbg' => ['required', 'string', 'max:255', Rule::unique('transaksi_gadai', 'no_sbg')],
            'nasabah_id' => ['required', 'exists:nasabahs,id'],
            'barang_id' => ['required', 'exists:barang_jaminan,barang_id'],
            'tanggal_gadai' => ['required', 'date'],
            'jatuh_tempo_awal' => ['required', 'date', 'after_or_equal:tanggal_gadai'],
            'uang_pinjaman' => ['required', 'string'],
            'biaya_admin' => ['nullable', 'string'],
        ], [], [
            'no_sbg' => __('Nomor Kontrak'),
            'nasabah_id' => __('Nasabah'),
            'barang_id' => __('Barang Jaminan'),
            'tanggal_gadai' => __('Tanggal Gadai'),
            'jatuh_tempo_awal' => __('Jatuh Tempo Awal'),
            'uang_pinjaman' => __('Uang Pinjaman'),
            'biaya_admin' => __('Biaya Admin'),
        ]);

        $barang = BarangJaminan::query()
            ->whereNull('transaksi_id')
            ->find($data['barang_id']);

        if (!$barang) {
            throw ValidationException::withMessages([
                'barang_id' => __('Barang jaminan yang dipilih tidak tersedia untuk digadaikan.'),
            ]);
        }

        $nilaiTaksiran = (float) $barang->nilai_taksiran;
        $uangPinjaman = (float) $this->normalizeDecimalInput($data['uang_pinjaman']);
        $biayaAdmin = (float) $this->normalizeDecimalInput($data['biaya_admin'] ?? '0');

        $maksPinjaman = $nilaiTaksiran * 0.94;

        if ($uangPinjaman <= 0) {
            throw ValidationException::withMessages([
                'uang_pinjaman' => __('Nominal pinjaman harus lebih besar dari 0.'),
            ]);
        }

        if ($uangPinjaman > $maksPinjaman + 0.0001) {
            throw ValidationException::withMessages([
                'uang_pinjaman' => __('Nominal pinjaman melebihi batas maksimum :maks.', [
                    'maks' => number_format($maksPinjaman, 2, ',', '.'),
                ]),
            ]);
        }

        $tanggalGadai = Carbon::parse($data['tanggal_gadai'])->toDateString();
        $jatuhTempoAwal = Carbon::parse($data['jatuh_tempo_awal'])->toDateString();

        $transaksi = null;

        DB::transaction(function () use (
            &$transaksi,
            $data,
            $uangPinjaman,
            $biayaAdmin,
            $tanggalGadai,
            $jatuhTempoAwal,
            $barang
        ) {
            $barangTerpilih = BarangJaminan::query()
                ->whereKey($barang->getKey())
                ->lockForUpdate()
                ->first();

            if (!$barangTerpilih || $barangTerpilih->transaksi_id !== null) {
                throw ValidationException::withMessages([
                    'barang_id' => __('Barang jaminan dipakai pada transaksi lain. Muat ulang halaman dan pilih ulang.'),
                ]);
            }

            $transaksi = TransaksiGadai::query()->create([
                'no_sbg' => $data['no_sbg'],
                'nasabah_id' => $data['nasabah_id'],
                'pegawai_kasir_id' => Auth::id(),
                'tanggal_gadai' => $tanggalGadai,
                'jatuh_tempo_awal' => $jatuhTempoAwal,
                'uang_pinjaman' => $uangPinjaman,
                'biaya_admin' => $biayaAdmin,
                'status_transaksi' => 'Aktif',
            ]);

            $barangTerpilih->update([
                'transaksi_id' => $transaksi->transaksi_id,
            ]);
        });

        return redirect()
            ->route('transaksi-gadai.index')
            ->with('status', __('Kontrak gadai berhasil dibuat. Nomor kontrak: :no.', ['no' => $transaksi->no_sbg]));
    }

    private function normalizeDecimalInput(?string $value): string
    {
        $value = $value ?? '0';
        $value = trim($value);

        if ($value === '') {
            return '0';
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

        return $value;
    }
}
