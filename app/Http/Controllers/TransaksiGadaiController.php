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
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TransaksiGadaiController extends Controller
{
    public function index(Request $request): View
    {
        $perPageOptions = [10, 25, 50, 100];
        $perPage = (int) $request->query('per_page', 10);

        if (!in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }

        $filterValidator = Validator::make($request->query(), [
            'search' => ['nullable', 'string', 'max:255'],
            'tanggal_dari' => ['nullable', 'date'],
            'tanggal_sampai' => ['nullable', 'date', 'after_or_equal:tanggal_dari'],
        ]);

        $filters = $filterValidator->safe()->only(['search', 'tanggal_dari', 'tanggal_sampai']);

        $search = trim((string) ($filters['search'] ?? ''));
        $tanggalDari = $filters['tanggal_dari'] ?? null;
        $tanggalSampai = $filters['tanggal_sampai'] ?? null;

        $today = Carbon::today()->toDateString();
        $shouldAutoSubmit = !$request->has('tanggal_dari') && !$request->has('tanggal_sampai');

        if (!$tanggalDari) {
            $tanggalDari = $today;
        }

        if (!$tanggalSampai) {
            $tanggalSampai = $today;
        }

        $transaksiGadai = TransaksiGadai::with([
            'nasabah',
            'kasir',
            'barangJaminan',
        ])
            ->where(function ($query) {
                $query->whereNull('status_transaksi')
                    ->orWhere('status_transaksi', '!=', 'Batal');
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('no_sbg', 'like', "%{$search}%")
                        ->orWhereHas('nasabah', function ($nasabahQuery) use ($search) {
                            $nasabahQuery->where('nama', 'like', "%{$search}%")
                                ->orWhere('kode_member', 'like', "%{$search}%")
                                ->orWhere('telepon', 'like', "%{$search}%");
                        });
                });
            })
            ->when($tanggalDari, function ($query) use ($tanggalDari) {
                $query->whereDate('tanggal_gadai', '>=', $tanggalDari);
            })
            ->when($tanggalSampai, function ($query) use ($tanggalSampai) {
                $query->whereDate('tanggal_gadai', '<=', $tanggalSampai);
            })
            ->latest('tanggal_gadai')
            ->paginate($perPage > 0 ? $perPage : 10)
            ->withQueryString();

        $todayDate = Carbon::today();

        $transaksiGadai->getCollection()->transform(function ($transaksi) use ($todayDate) {
            $tanggalGadai = $transaksi->tanggal_gadai ? Carbon::parse($transaksi->tanggal_gadai) : null;
            $hariBerjalan = $tanggalGadai ? max(0, $tanggalGadai->diffInDays($todayDate, false)) : 0;
            $tenor = is_numeric($transaksi->tenor_hari) ? (int) $transaksi->tenor_hari : 0;
            $tarif = is_numeric($transaksi->tarif_bunga_harian) ? (float) $transaksi->tarif_bunga_harian : 0.0;
            $pinjaman = is_numeric($transaksi->uang_pinjaman) ? (float) $transaksi->uang_pinjaman : 0.0;

            $hariEfektif = $tenor > 0 ? min($hariBerjalan, $tenor) : $hariBerjalan;
            $bungaTerhitung = $pinjaman * $tarif * $hariEfektif;

            $totalBungaTersimpan = is_numeric($transaksi->total_bunga) ? (float) $transaksi->total_bunga : null;

            if ($totalBungaTersimpan !== null) {
                $bungaTerhitung = min($bungaTerhitung, $totalBungaTersimpan);
            }

            $transaksi->setAttribute('bunga_terakumulasi_harian', $bungaTerhitung);

            return $transaksi;
        });

        return view('gadai.lihat-gadai', [
            'transaksiGadai' => $transaksiGadai,
            'search' => $search,
            'tanggalDari' => $tanggalDari,
            'tanggalSampai' => $tanggalSampai,
            'perPage' => $perPage,
            'perPageOptions' => $perPageOptions,
            'shouldAutoSubmitFilters' => $shouldAutoSubmit,
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
            ->latest('created_at')
            ->limit(100)
            ->get();

        return view('gadai.pemberian-kredit', [
            'barangSiapGadai' => $barangSiapGadai,
            'nasabahList' => $nasabahList,
            'today' => Carbon::today()->toDateString(),
            'defaultNoSbg' => $this->nextNoSbg(Carbon::today()),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'tanggal_gadai' => Carbon::today()->toDateString(),
        ]);

        $data = $this->validateData($request);

        $barangIds = array_map('intval', $data['barang_ids']);

        $barangCollection = BarangJaminan::query()
            ->whereNull('transaksi_id')
            ->whereIn('barang_id', $barangIds)
            ->get();

        if ($barangCollection->count() !== count($barangIds)) {
            return back()
                ->withInput()
                ->withErrors([
                    'barang_ids' => __('Salah satu barang yang dipilih tidak tersedia atau sudah terikat kontrak.'),
                ]);
        }

        $nilaiTaksiran = (float) $barangCollection->sum('nilai_taksiran');
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

        $tenorHari = max(1, $tanggalGadai->diffInDays($jatuhTempo) + 1);
        $tarifBungaHarian = 0.0015; // 0.15% per hari
        $totalBunga = $this->formatDecimal($uangPinjaman * $tarifBungaHarian * $tenorHari);

        DB::transaction(function () use ($barangCollection, $kasirId, $data, $tenorHari, $tarifBungaHarian, $totalBunga, $tanggalGadai) {
            $noSbg = $this->nextNoSbg($tanggalGadai, true);

            $transaksi = TransaksiGadai::create([
                'no_sbg' => $noSbg,
                'nasabah_id' => $data['nasabah_id'],
                'pegawai_kasir_id' => $kasirId,
                'tanggal_gadai' => $data['tanggal_gadai'],
                'jatuh_tempo_awal' => $data['jatuh_tempo_awal'],
                'tenor_hari' => $tenorHari,
                'tarif_bunga_harian' => $this->formatDecimal($tarifBungaHarian, 4),
                'total_bunga' => $totalBunga,
                'uang_pinjaman' => $data['uang_pinjaman'],
                'biaya_admin' => $data['biaya_admin'],
                'premi' => $data['premi'],
                'status_transaksi' => 'Aktif',
            ]);

            foreach ($barangCollection as $barang) {
                $barang->transaksi_id = $transaksi->transaksi_id;
                $barang->save();
            }
        });

        return redirect()
            ->route('gadai.lihat-gadai')
            ->with('status', __('Kontrak gadai berhasil diterbitkan dan barang dikunci.'));
    }

    public function cancel(Request $request, TransaksiGadai $transaksi): RedirectResponse
    {
        $request->validate([
            'alasan_batal' => ['required', 'string', 'max:1000'],
        ]);

        $status = $transaksi->status_transaksi;

        if (in_array($status, ['Lunas', 'Perpanjang', 'Lelang', 'Batal'], true)) {
            $message = __('Transaksi dengan status :status tidak dapat dibatalkan.', ['status' => $status ?? '—']);

            return redirect()
                ->back()
                ->withInput($request->all())
                ->withErrors([
                    'alasan_batal' => $message,
                ])
                ->with('error', $message);
        }

        $alasan = trim((string) $request->input('alasan_batal'));

        $pembatalId = Auth::id();

        if (!$pembatalId) {
            abort(403, 'Pengguna tidak dikenali.');
        }

        DB::transaction(function () use ($transaksi, $alasan, $pembatalId) {
            $transaksi->loadMissing('barangJaminan');

            foreach ($transaksi->barangJaminan as $barang) {
                $barang->transaksi_id = null;
                $barang->save();
            }

            $transaksi->status_transaksi = 'Batal';
            $transaksi->tanggal_batal = Carbon::now();
            $transaksi->alasan_batal = $alasan;
            $transaksi->pegawai_pembatal_id = $pembatalId;
            $transaksi->save();
        });

        return redirect()
            ->route('gadai.lihat-gadai', $request->only(['search', 'tanggal_dari', 'tanggal_sampai', 'page', 'per_page']))
            ->with('status', __('Transaksi gadai berhasil dibatalkan.'));
    }

    public function showSettlementForm(Request $request, TransaksiGadai $transaksi): View
    {
        $status = $transaksi->status_transaksi;

        if (in_array($status, ['Lunas', 'Lelang', 'Batal'], true)) {
            $message = __('Transaksi dengan status :status tidak dapat dilunasi.', ['status' => $status ?? '—']);

            return redirect()
                ->route('gadai.lihat-gadai', $this->extractListingQuery($request))
                ->with('error', $message);
        }

        $transaksi->loadMissing(['nasabah', 'kasir', 'barangJaminan']);

        $pelunasanBiayaSaran = (float) $transaksi->biaya_admin + (float) $transaksi->premi;
        $pelunasanTotalSaran = (float) $transaksi->uang_pinjaman + (float) $transaksi->total_bunga + $pelunasanBiayaSaran;

        return view('gadai.pelunasan', [
            'transaksi' => $transaksi,
            'query' => $this->extractListingQuery($request),
            'defaults' => [
                'tanggal_pelunasan' => Carbon::today()->toDateString(),
                'metode_pembayaran' => __('Tunai'),
                'pokok_dibayar' => number_format((float) $transaksi->uang_pinjaman, 2, '.', ''),
                'bunga_dibayar' => number_format((float) $transaksi->total_bunga, 2, '.', ''),
                'biaya_lain_dibayar' => number_format($pelunasanBiayaSaran, 2, '.', ''),
                'total_pelunasan' => number_format($pelunasanTotalSaran, 2, '.', ''),
            ],
            'pelunasanBiayaSaran' => $pelunasanBiayaSaran,
            'pelunasanTotalSaran' => $pelunasanTotalSaran,
        ]);
    }

    public function settle(Request $request, TransaksiGadai $transaksi): RedirectResponse
    {
        $status = $transaksi->status_transaksi;

        if (in_array($status, ['Lunas', 'Lelang', 'Batal'], true)) {
            $message = __('Transaksi dengan status :status tidak dapat dilunasi.', ['status' => $status ?? '—']);

            return redirect()
                ->route('gadai.lihat-gadai', $this->extractListingQuery($request))
                ->with('error', $message);
        }

        $data = $this->validateSettlementData($request);

        $pokok = (float) $data['pokok_dibayar'];
        $bunga = (float) $data['bunga_dibayar'];
        $biayaLain = (float) $data['biaya_lain_dibayar'];
        $total = (float) $data['total_pelunasan'];

        if ($total + 0.00001 < $pokok + $bunga + $biayaLain) {
            $message = __('Total pelunasan harus sama atau lebih besar dari komponen pembayaran.');

            return redirect()
                ->back()
                ->withInput($request->except('_token'))
                ->withErrors([
                    'total_pelunasan' => $message,
                ])
                ->with('error', $message);
        }

        $kasirId = Auth::id();

        if (!$kasirId) {
            abort(403, 'Kasir tidak dikenali.');
        }

        $tanggalPelunasan = Carbon::parse($data['tanggal_pelunasan'])
            ->setTimeFromTimeString(Carbon::now()->toTimeString());

        DB::transaction(function () use ($transaksi, $kasirId, $data, $tanggalPelunasan, $pokok, $bunga, $biayaLain, $total) {
            $transaksi->status_transaksi = 'Lunas';
            $transaksi->tanggal_pelunasan = $tanggalPelunasan;
            $transaksi->pokok_dibayar = $this->formatDecimal($pokok);
            $transaksi->bunga_dibayar = $this->formatDecimal($bunga);
            $transaksi->biaya_lain_dibayar = $this->formatDecimal($biayaLain);
            $transaksi->total_pelunasan = $this->formatDecimal($total);
            $transaksi->metode_pembayaran = $data['metode_pembayaran'];
            $transaksi->catatan_pelunasan = $data['catatan_pelunasan'] ?: null;
            $transaksi->pegawai_pelunasan_id = $kasirId;
            $transaksi->save();
        });

        return redirect()
            ->route('gadai.lihat-gadai', $this->extractListingQuery($request))
            ->with('status', __('Transaksi gadai berhasil dilunasi.'));
    }

    private function extractListingQuery(Request $request): array
    {
        return array_filter(
            $request->only(['search', 'tanggal_dari', 'tanggal_sampai', 'per_page', 'page']),
            static fn ($value) => $value !== null && $value !== ''
        );
    }

    private function validateData(Request $request): array
    {
        $validated = $request->validate([
            'barang_ids' => ['required', 'array', 'min:1'],
            'barang_ids.*' => [
                'required',
                'distinct',
                Rule::exists('barang_jaminan', 'barang_id')->whereNull('transaksi_id'),
            ],
            'nasabah_id' => ['required', 'exists:nasabahs,id'],
            'tanggal_gadai' => ['required', 'date'],
            'jatuh_tempo_awal' => ['required', 'date', 'after_or_equal:tanggal_gadai'],
            'uang_pinjaman' => ['required', 'string'],
            'biaya_admin' => ['nullable', 'string'],
            'premi' => ['nullable', 'string'],
        ]);

        $validated['barang_ids'] = array_values(array_map('strval', $validated['barang_ids']));
        $validated['uang_pinjaman'] = $this->toDecimalString($validated['uang_pinjaman']);
        $validated['biaya_admin'] = $this->toDecimalString($validated['biaya_admin'] ?? '0');
        $validated['premi'] = $this->toDecimalString($validated['premi'] ?? '0');

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

    private function validateSettlementData(Request $request): array
    {
        $validated = $request->validate([
            'tanggal_pelunasan' => ['required', 'date'],
            'pokok_dibayar' => ['required', 'string'],
            'bunga_dibayar' => ['nullable', 'string'],
            'biaya_lain_dibayar' => ['nullable', 'string'],
            'total_pelunasan' => ['required', 'string'],
            'metode_pembayaran' => ['required', 'string', 'max:100'],
            'catatan_pelunasan' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['pokok_dibayar'] = $this->toDecimalString($validated['pokok_dibayar']);
        $validated['bunga_dibayar'] = $this->toDecimalString($validated['bunga_dibayar'] ?? '0');
        $validated['biaya_lain_dibayar'] = $this->toDecimalString($validated['biaya_lain_dibayar'] ?? '0');
        $validated['total_pelunasan'] = $this->toDecimalString($validated['total_pelunasan']);
        $validated['metode_pembayaran'] = trim($validated['metode_pembayaran']);
        $validated['catatan_pelunasan'] = isset($validated['catatan_pelunasan'])
            ? trim((string) $validated['catatan_pelunasan'])
            : null;

        return $validated;
    }

    private function formatCurrency(float $value): string
    {
        return 'Rp ' . number_format($value, 2, ',', '.');
    }

    private function formatDecimal(float $value, int $precision = 2): string
    {
        return number_format($value, $precision, '.', '');
    }

    private function nextNoSbg(Carbon $tanggalGadai, bool $lock = false): string
    {
        $prefix = 'GE02' . $tanggalGadai->format('ymd');

        $query = TransaksiGadai::whereDate('tanggal_gadai', $tanggalGadai->toDateString())
            ->where('no_sbg', 'like', $prefix . '%');

        if ($lock) {
            $query->lockForUpdate();
        }

        $latest = $query->orderByDesc('no_sbg')->value('no_sbg');

        if ($latest && preg_match('/(\d{3})$/', $latest, $matches)) {
            $sequence = (int) $matches[1] + 1;
        } else {
            $sequence = 1;
        }

        return $prefix . str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
    }
}
