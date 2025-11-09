<?php

namespace App\Http\Controllers;

use App\Models\BarangJaminan;
use App\Models\Nasabah;
use App\Models\TransaksiGadai;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TransaksiGadaiController extends Controller
{
    public function index(): View
    {
        $transaksiList = TransaksiGadai::with('nasabah')
            ->latest('tanggal_gadai')
            ->latest('created_at')
            ->paginate(15);

        return view('transaksi-gadai.index', [
            'transaksiList' => $transaksiList,
        ]);
    }

    public function create(): View
    {
        return view('transaksi-gadai.create', [
            'nasabahList' => Nasabah::orderBy('nama')->get(['id', 'nama', 'kode_member']),
            'barangSiapGadai' => BarangJaminan::query()
                ->whereNull('transaksi_id')
                ->orderBy('jenis_barang')
                ->get(['barang_id', 'jenis_barang', 'merek', 'nilai_taksiran']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'no_sbg' => ['required', 'string', 'max:255', Rule::unique('transaksi_gadai', 'no_sbg')],
            'nasabah_id' => ['required', 'exists:nasabahs,id'],
            'barang_id' => [
                'required',
                'exists:barang_jaminan,barang_id',
            ],
            'tanggal_gadai' => ['required', 'date'],
            'jatuh_tempo_awal' => ['required', 'date', 'after_or_equal:tanggal_gadai'],
            'uang_pinjaman' => ['required', 'string'],
            'biaya_admin' => ['required', 'string'],
        ]);

        $barang = BarangJaminan::query()
            ->where('barang_id', $validated['barang_id'])
            ->whereNull('transaksi_id')
            ->first();

        if (!$barang) {
            return back()
                ->withErrors([
                    'barang_id' => __('Barang jaminan tidak tersedia untuk proses gadai.'),
                ])
                ->withInput();
        }

        $uangPinjaman = $this->toDecimalString($validated['uang_pinjaman']);
        $biayaAdmin = $this->toDecimalString($validated['biaya_admin']);

        $nilaiTaksiranCents = (int) round((float) $barang->nilai_taksiran * 100);
        $maxPinjamanCents = (int) floor($nilaiTaksiranCents * 94 / 100);
        $uangPinjamanCents = (int) round((float) $uangPinjaman * 100);

        if ($uangPinjamanCents > $maxPinjamanCents) {
            $maxPinjaman = number_format($maxPinjamanCents / 100, 2, ',', '.');
            return back()
                ->withErrors([
                    'uang_pinjaman' => __('Nilai pinjaman melebihi batas maksimal 94% dari nilai taksiran (:max).', [
                        'max' => $maxPinjaman,
                    ]),
                ])
                ->withInput();
        }

        $transaksi = null;

        DB::transaction(function () use ($validated, $barang, $uangPinjaman, $biayaAdmin, &$transaksi) {
            $transaksi = TransaksiGadai::create([
                'no_sbg' => $validated['no_sbg'],
                'nasabah_id' => $validated['nasabah_id'],
                'pegawai_kasir_id' => Auth::id(),
                'tanggal_gadai' => $validated['tanggal_gadai'],
                'jatuh_tempo_awal' => $validated['jatuh_tempo_awal'],
                'uang_pinjaman' => $uangPinjaman,
                'biaya_admin' => $biayaAdmin,
                'status_transaksi' => 'Aktif',
            ]);

            $barang->update([
                'transaksi_id' => $transaksi->transaksi_id,
            ]);
        });

        return redirect()
            ->route('transaksi-gadai.index')
            ->with('status', __('Transaksi gadai berhasil dibuat.'));
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
}
