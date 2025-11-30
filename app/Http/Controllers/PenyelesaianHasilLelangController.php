<?php

namespace App\Http\Controllers;

use App\Models\JadwalLelang;
use App\Support\LatestLimitedPaginator;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PenyelesaianHasilLelangController extends Controller
{
    private const SURPLUS_STATUSES = ['Belum Diambil', 'Sudah Diambil', 'Dialihkan ke Dana Sosial'];
    private const DEFISIT_STATUSES = ['Belum Lunas', 'Sudah Lunas'];

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string'],
            'status_pembayaran' => ['nullable', 'string'],
            'per_page' => ['nullable', 'integer', Rule::in(LatestLimitedPaginator::PER_PAGE_OPTIONS)],
        ]);

        $perPage = (int) ($validated['per_page'] ?? LatestLimitedPaginator::PER_PAGE_OPTIONS[0]);
        $search = $validated['search'] ?? '';
        $statusPembayaran = $validated['status_pembayaran'] ?? '';

        $jadwalQuery = JadwalLelang::query()
            ->with(['transaksi.nasabah', 'barang'])
            ->where('hasil_status', 'laku')
            ->where(function ($query) {
                $query->where('distribusi_nasabah', '>', 0)->orWhere('piutang_sisa', '>', 0);
            })
            ->latest('tanggal_selesai');

        if ($search !== '') {
            $jadwalQuery->where(function ($query) use ($search) {
                $query
                    ->whereHas('transaksi', function ($transaksiQuery) use ($search) {
                        $transaksiQuery
                            ->where('no_sbg', 'like', "%{$search}%")
                            ->orWhereHas('nasabah', function ($nasabahQuery) use ($search) {
                                $nasabahQuery->where('nama', 'like', "%{$search}%");
                            });
                    })
                    ->orWhereHas('barang', function ($barangQuery) use ($search) {
                        $barangQuery->where('jenis_barang', 'like', "%{$search}%");
                    });
            });
        }

        if ($statusPembayaran !== '') {
            $jadwalQuery->where('status_pembayaran_nasabah', $statusPembayaran);
        }

        $jadwalLelang = LatestLimitedPaginator::fromQuery($jadwalQuery, $request, $perPage);

        return view('gadai.penyelesaian-hasil-lelang', [
            'jadwalLelang' => $jadwalLelang,
            'search' => $search,
            'statusPembayaran' => $statusPembayaran,
            'perPage' => $perPage,
            'perPageOptions' => LatestLimitedPaginator::PER_PAGE_OPTIONS,
            'surplusStatuses' => self::SURPLUS_STATUSES,
            'defisitStatuses' => self::DEFISIT_STATUSES,
        ]);
    }

    public function update(Request $request, JadwalLelang $jadwalLelang): RedirectResponse
    {
        $resultType = $this->determineResultType($jadwalLelang);

        if ($resultType === null) {
            return back()->withErrors([
                'status_pembayaran_nasabah' => __('Status hasil lelang belum ditentukan untuk jadwal ini.'),
            ]);
        }

        if (filled($jadwalLelang->status_pembayaran_nasabah)) {
            return back()->withErrors([
                'status_pembayaran_nasabah' => __('Status sudah dikunci. Gunakan Batalkan Simpan untuk mengubahnya.'),
            ]);
        }

        $allowedStatuses = $resultType === 'surplus' ? self::SURPLUS_STATUSES : self::DEFISIT_STATUSES;

        $data = $request->validate([
            'status_pembayaran_nasabah' => ['required', Rule::in($allowedStatuses)],
        ]);

        $statusPembayaran = $data['status_pembayaran_nasabah'];

        DB::transaction(function () use ($jadwalLelang, $statusPembayaran, $resultType) {
            $jadwalLelang->forceFill([
                'status_pembayaran_nasabah' => $statusPembayaran,
            ])->save();

            if ($resultType === 'surplus') {
                $this->syncSurplusCashflow($jadwalLelang, $statusPembayaran);
            } else {
                $this->syncDeficitCashflow($jadwalLelang, $statusPembayaran);
            }
        });

        return back()->with('status', __('Status pembayaran nasabah berhasil diperbarui.'));
    }

    private function determineResultType(JadwalLelang $jadwalLelang): ?string
    {
        if ((float) $jadwalLelang->distribusi_nasabah > 0) {
            return 'surplus';
        }

        if ((float) $jadwalLelang->piutang_sisa > 0) {
            return 'defisit';
        }

        return null;
    }

    public function reset(JadwalLelang $jadwalLelang): RedirectResponse
    {
        $resultType = $this->determineResultType($jadwalLelang);

        if ($resultType === null) {
            return back()->withErrors([
                'status_pembayaran_nasabah' => __('Status hasil lelang belum ditentukan untuk jadwal ini.'),
            ]);
        }

        DB::transaction(function () use ($jadwalLelang, $resultType) {
            $this->clearCashflow($jadwalLelang, $resultType);

            $jadwalLelang->forceFill([
                'status_pembayaran_nasabah' => null,
            ])->save();
        });

        return back()->with('status', __('Status pembayaran dibatalkan. Anda dapat memilih status kembali.'));
    }

    private function syncDeficitCashflow(JadwalLelang $jadwalLelang, string $statusPembayaran): void
    {
        $amount = (float) $jadwalLelang->piutang_sisa;

        if ($amount <= 0) {
            return;
        }

        $jadwalLelang
            ->mutasiKas()
            ->where('sumber', 'pelunasan defisit lelang')
            ->delete();

        if ($statusPembayaran !== 'Sudah Lunas') {
            return;
        }

        $referensi = 'Lelang #' . $jadwalLelang->id;
        $tanggalMutasi = Carbon::now()->toDateString();

        $jadwalLelang->mutasiKas()->create([
            'tanggal' => $tanggalMutasi,
            'referensi' => $referensi,
            'tipe' => 'masuk',
            'jumlah' => $this->formatDecimal($amount),
            'sumber' => 'pelunasan defisit lelang',
            'keterangan' => __('Pelunasan kekurangan hasil lelang oleh nasabah'),
        ]);
    }

    private function syncSurplusCashflow(JadwalLelang $jadwalLelang, string $statusPembayaran): void
    {
        $amount = (float) $jadwalLelang->distribusi_nasabah;

        if ($amount <= 0) {
            return;
        }

        $jadwalLelang
            ->mutasiKas()
            ->whereIn('sumber', ['pengembalian nasabah', 'dana sosial lelang'])
            ->delete();

        if ($statusPembayaran === 'Belum Diambil') {
            return;
        }

        $referensi = 'Lelang #' . $jadwalLelang->id;
        $tanggalMutasi = Carbon::now()->toDateString();
        $sumber = $statusPembayaran === 'Dialihkan ke Dana Sosial' ? 'dana sosial lelang' : 'pengembalian nasabah';
        $keterangan = $statusPembayaran === 'Dialihkan ke Dana Sosial'
            ? __('Pengalihan surplus lelang ke dana sosial')
            : __('Pengembalian sisa hasil lelang kepada nasabah');

        $jadwalLelang->mutasiKas()->create([
            'tanggal' => $tanggalMutasi,
            'referensi' => $referensi,
            'tipe' => 'keluar',
            'jumlah' => $this->formatDecimal($amount),
            'sumber' => $sumber,
            'keterangan' => $keterangan,
        ]);
    }

    private function clearCashflow(JadwalLelang $jadwalLelang, string $resultType): void
    {
        if ($resultType === 'surplus') {
            $jadwalLelang
                ->mutasiKas()
                ->whereIn('sumber', ['pengembalian nasabah', 'dana sosial lelang'])
                ->delete();

            return;
        }

        $jadwalLelang
            ->mutasiKas()
            ->where('sumber', 'pelunasan defisit lelang')
            ->delete();
    }

    private function formatDecimal($value): ?string
    {
        if ($value === null) {
            return null;
        }

        return number_format((float) $value, 2, '.', '');
    }
}
