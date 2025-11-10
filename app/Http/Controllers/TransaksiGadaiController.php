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
use Illuminate\Validation\Rule;

class TransaksiGadaiController extends Controller
{
    public function index(): View
    {
        $transaksiGadai = TransaksiGadai::with([
            'nasabah',
            'kasir',
            'barangJaminan',
        ])->latest('tanggal_gadai')->paginate(15);

        return view('gadai.lihat-gadai', [
            'transaksiGadai' => $transaksiGadai,
        ]);
    }

    public function create(): View
    {
        $barangSiapGadai = BarangJaminan::query()
            ->whereNull('transaksi_id')
            ->orderBy('jenis_barang')
            ->orderBy('merek')
            ->get();

        $nasabahList = Nasabah::query()
            ->orderBy('nama')
            ->get();

        return view('gadai.pemberian-kredit', [
            'barangSiapGadai' => $barangSiapGadai,
            'nasabahList' => $nasabahList,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        $barang = BarangJaminan::query()
            ->whereNull('transaksi_id')
            ->findOrFail($data['barang_id']);

        $nilaiTaksiran = (float) $barang->nilai_taksiran;
        $uangPinjaman = (float) $data['uang_pinjaman'];
        $maxPinjaman = round($nilaiTaksiran * 0.94, 2);

        if ($nilaiTaksiran > 0 && $uangPinjaman - $maxPinjaman > 0.00001) {
            return back()
                ->withInput()
                ->withErrors([
                    'uang_pinjaman' => __('Nominal pinjaman melebihi batas 94% dari nilai taksiran barang (maksimal :amount).', [
                        'amount' => $this->formatCurrency($maxPinjaman),
                    ]),
                ]);
        }

        $kasirId = Auth::id();

        if (!$kasirId) {
            abort(403, 'Kasir tidak dikenali.');
        }

        $tanggalGadai = Carbon::parse($data['tanggal_gadai']);
        $jatuhTempo = Carbon::parse($data['jatuh_tempo_awal']);

        $tenorHari = max(1, $tanggalGadai->diffInDays($jatuhTempo));
        $tarifBungaHarian = 0.0015; // 0.15% per hari
        $totalBunga = $this->formatDecimal($uangPinjaman * $tarifBungaHarian * $tenorHari);

        $transaksi = TransaksiGadai::create([
            'no_sbg' => $data['no_sbg'],
            'nasabah_id' => $data['nasabah_id'],
            'pegawai_kasir_id' => $kasirId,
            'tanggal_gadai' => $data['tanggal_gadai'],
            'jatuh_tempo_awal' => $data['jatuh_tempo_awal'],
            'tenor_hari' => $tenorHari,
            'tarif_bunga_harian' => $this->formatDecimal($tarifBungaHarian, 4),
            'total_bunga' => $totalBunga,
            'uang_pinjaman' => $data['uang_pinjaman'],
            'biaya_admin' => $data['biaya_admin'],
            'status_transaksi' => 'Aktif',
        ]);

        $barang->transaksi_id = $transaksi->transaksi_id;
        $barang->save();

        return redirect()
            ->route('gadai.lihat-gadai')
            ->with('status', __('Kontrak gadai berhasil diterbitkan dan barang dikunci.'));
    }

    private function validateData(Request $request): array
    {
        $validated = $request->validate([
            'barang_id' => [
                'required',
                Rule::exists('barang_jaminan', 'barang_id')->whereNull('transaksi_id'),
            ],
            'no_sbg' => ['required', 'string', 'max:50', 'unique:transaksi_gadai,no_sbg'],
            'nasabah_id' => ['required', 'exists:nasabahs,id'],
            'tanggal_gadai' => ['required', 'date'],
            'jatuh_tempo_awal' => ['required', 'date', 'after_or_equal:tanggal_gadai'],
            'uang_pinjaman' => ['required', 'string'],
            'biaya_admin' => ['nullable', 'string'],
        ]);

        $validated['uang_pinjaman'] = $this->toDecimalString($validated['uang_pinjaman']);
        $validated['biaya_admin'] = $this->toDecimalString($validated['biaya_admin'] ?? '0');

        return $validated;
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

    private function formatCurrency(float $value): string
    {
        return 'Rp ' . number_format($value, 2, ',', '.');
    }

    private function formatDecimal(float $value, int $precision = 2): string
    {
        return number_format($value, $precision, '.', '');
    }
}
