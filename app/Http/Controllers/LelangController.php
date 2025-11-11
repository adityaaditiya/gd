<?php

namespace App\Http\Controllers;

use App\Models\JadwalLelang;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LelangController extends Controller
{
    private const STATUS_OPTIONS = ['Siap Lelang', 'Selesai', 'Butuh Penjadwalan Ulang'];

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'status' => ['nullable', Rule::in(self::STATUS_OPTIONS)],
            'search' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'in:10,25,50,100'],
        ]);

        $status = $validated['status'] ?? null;
        $search = trim((string) ($validated['search'] ?? ''));
        $perPage = (int) ($validated['per_page'] ?? 10);

        $jadwalLelang = JadwalLelang::query()
            ->with([
                'barang.transaksi.nasabah',
                'barang.transaksi',
            ])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('transaksi', function ($transactionQuery) use ($search) {
                        $transactionQuery->where('no_sbg', 'like', "%{$search}%")
                            ->orWhereHas('nasabah', function ($nasabahQuery) use ($search) {
                                $nasabahQuery->where('nama', 'like', "%{$search}%")
                                    ->orWhere('kode_member', 'like', "%{$search}%");
                            });
                    })
                        ->orWhereHas('barang', function ($barangQuery) use ($search) {
                            $barangQuery->where('jenis_barang', 'like', "%{$search}%")
                                ->orWhere('merek', 'like', "%{$search}%");
                        });
                });
            })
            ->orderByRaw('ISNULL(tanggal_rencana)')
            ->orderBy('tanggal_rencana')
            ->latest('created_at')
            ->paginate($perPage > 0 ? $perPage : 10)
            ->withQueryString();

        return view('gadai.lihat-data-lelang', [
            'jadwalLelang' => $jadwalLelang,
            'statusOptions' => self::STATUS_OPTIONS,
            'statusFilter' => $status,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function updateSchedule(Request $request, JadwalLelang $jadwalLelang): RedirectResponse
    {
        $data = $request->validate([
            'tanggal_rencana' => ['nullable', 'date'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'petugas' => ['nullable', 'string', 'max:255'],
            'harga_limit' => ['nullable', 'numeric', 'min:0'],
            'estimasi_biaya' => ['nullable', 'numeric', 'min:0'],
            'catatan' => ['nullable', 'string'],
        ]);

        $payload = [
            'tanggal_rencana' => $data['tanggal_rencana'] ?? null,
            'lokasi' => $this->nullIfEmpty($data['lokasi'] ?? null),
            'petugas' => $this->nullIfEmpty($data['petugas'] ?? null),
            'harga_limit' => $this->formatDecimal($data['harga_limit'] ?? null),
            'estimasi_biaya' => $this->formatDecimal($data['estimasi_biaya'] ?? null),
            'catatan' => $this->nullIfEmpty($data['catatan'] ?? null),
        ];

        if ($jadwalLelang->status === 'Butuh Penjadwalan Ulang') {
            $payload['status'] = 'Siap Lelang';
            $payload['hasil_status'] = null;
            $payload['tanggal_selesai'] = null;
        }

        $jadwalLelang->fill($payload)->save();

        return back()->with('status', __('Jadwal lelang berhasil diperbarui.'));
    }

    public function finalize(Request $request, JadwalLelang $jadwalLelang): RedirectResponse
    {
        $data = $request->validate([
            'hasil_status' => ['required', Rule::in(['laku', 'tidak_laku'])],
            'harga_laku' => ['required_if:hasil_status,laku', 'nullable', 'numeric', 'min:0'],
            'biaya_lelang' => ['nullable', 'numeric', 'min:0'],
            'catatan_hasil' => ['nullable', 'string'],
        ]);

        $hasilStatus = $data['hasil_status'];
        $catatan = $this->nullIfEmpty($data['catatan_hasil'] ?? null);
        $now = Carbon::now();

        if ($hasilStatus === 'tidak_laku') {
            $jadwalLelang->mutasiKas()->delete();

            $jadwalLelang->forceFill([
                'status' => 'Butuh Penjadwalan Ulang',
                'hasil_status' => 'tidak_laku',
                'harga_laku' => null,
                'biaya_lelang' => null,
                'distribusi_perusahaan' => null,
                'distribusi_nasabah' => null,
                'piutang_sisa' => null,
                'catatan' => $catatan,
                'tanggal_selesai' => $now,
            ])->save();

            return back()->with('status', __('Status lelang diperbarui: barang belum laku dan perlu dijadwalkan ulang.'));
        }

        $hargaLaku = (float) ($data['harga_laku'] ?? 0);
        $biayaLelang = (float) ($data['biaya_lelang'] ?? 0);

        $transaksi = $jadwalLelang->transaksi;

        if (!$transaksi) {
            return back()->withErrors(['hasil_status' => __('Transaksi gadai tidak ditemukan untuk jadwal ini.')]);
        }

        DB::transaction(function () use ($jadwalLelang, $transaksi, $hargaLaku, $biayaLelang, $catatan, $now) {
            $transaksi->refreshBungaTerutangRiil($now);

            $principal = (float) ($transaksi->uang_pinjaman ?? 0);
            $interest = (float) ($transaksi->computeBungaTerutangRiil($now) ?? 0);
            $totalKewajiban = $transaksi->hitungKewajibanLelang($biayaLelang, $now);

            $distribusiPerusahaan = min($hargaLaku, round($principal + $interest, 2));
            $distribusiNasabah = 0.0;
            $piutangSisa = 0.0;

            if ($hargaLaku >= $totalKewajiban) {
                $distribusiNasabah = round($hargaLaku - $totalKewajiban, 2);
            } else {
                $piutangSisa = round($totalKewajiban - $hargaLaku, 2);
            }

            $jadwalLelang->mutasiKas()->delete();

            $jadwalLelang->forceFill([
                'status' => 'Selesai',
                'hasil_status' => 'laku',
                'harga_laku' => $this->formatDecimal($hargaLaku),
                'biaya_lelang' => $this->formatDecimal($biayaLelang),
                'distribusi_perusahaan' => $this->formatDecimal($distribusiPerusahaan),
                'distribusi_nasabah' => $this->formatDecimal($distribusiNasabah),
                'piutang_sisa' => $this->formatDecimal($piutangSisa),
                'catatan' => $catatan,
                'tanggal_selesai' => $now,
            ])->save();

            $transaksi->forceFill([
                'status_transaksi' => 'Lelang',
                'bunga_terutang_riil' => $this->formatDecimal($transaksi->computeBungaTerutangRiil($now)),
            ])->saveQuietly();

            $deskripsiBarang = $jadwalLelang->barang?->jenis_barang;
            $referensi = 'Lelang #' . $jadwalLelang->id;
            $tanggalMutasi = $now->toDateString();

            $jadwalLelang->mutasiKas()->create([
                'tanggal' => $tanggalMutasi,
                'referensi' => $referensi,
                'tipe' => 'masuk',
                'jumlah' => $this->formatDecimal($hargaLaku),
                'sumber' => 'lelang',
                'keterangan' => __('Penerimaan hasil lelang :barang', [
                    'barang' => $deskripsiBarang ?? __('barang gadai'),
                ]),
            ]);

            if ($biayaLelang > 0) {
                $jadwalLelang->mutasiKas()->create([
                    'tanggal' => $tanggalMutasi,
                    'referensi' => $referensi,
                    'tipe' => 'keluar',
                    'jumlah' => $this->formatDecimal($biayaLelang),
                    'sumber' => 'biaya lelang',
                    'keterangan' => __('Pembayaran biaya lelang'),
                ]);
            }

            if ($distribusiNasabah > 0) {
                $jadwalLelang->mutasiKas()->create([
                    'tanggal' => $tanggalMutasi,
                    'referensi' => $referensi,
                    'tipe' => 'keluar',
                    'jumlah' => $this->formatDecimal($distribusiNasabah),
                    'sumber' => 'pengembalian nasabah',
                    'keterangan' => __('Pengembalian sisa hasil lelang kepada nasabah'),
                ]);
            }
        });

        return back()->with('status', __('Hasil lelang berhasil dicatat dan distribusi dana telah diperbarui.'));
    }

    private function nullIfEmpty(?string $value): ?string
    {
        $trimmed = $value !== null ? trim($value) : null;

        return $trimmed === '' ? null : $trimmed;
    }

    private function formatDecimal($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return number_format((float) $value, 2, '.', '');
    }
}
