<x-layouts.app :title="__('Perpanjangan Transaksi Gadai')">
    @php
        $listQuery = $listingQuery;
        $listRoute = route('gadai.lihat-gadai', $listQuery);
        $nasabah = $transaksi->nasabah?->nama ?? '—';
        $kasir = $transaksi->kasir?->name ?? '—';
        $barangJaminan = $transaksi->barangJaminan ?? collect();
        $riwayatPerpanjangan = $transaksi->perpanjangan ?? collect();
        $riwayatPerpanjanganAktif = $riwayatPerpanjangan->filter(fn ($item) => $item->dibatalkan_pada === null);
        $riwayatPerpanjanganTerbaru = $riwayatPerpanjanganAktif->first();
        $defaultMulai = \Carbon\Carbon::parse($defaultTanggalMulai);
        $cutoffString = $extensionCutoff ?? $defaultTanggalMulai;
        $bungaDirekomendasikan = (float) $bungaBerjalan;
        $hasElapsed = $extensionHasElapsed ?? ($bungaDirekomendasikan > 0);
        $bungaCutoff = \Carbon\Carbon::parse($cutoffString);
        $tenorFormValue = max(1, (int) old('tenor_hari', $defaultTenor));
    @endphp

    <div class="space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-2">
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">
                    {{ __('Perpanjangan Transaksi Gadai') }}
                </h1>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Perbarui tenor kontrak :number milik :customer dan catat biaya perpanjangan yang diterima.', [
                        'number' => $transaksi->no_sbg,
                        'customer' => $nasabah,
                    ]) }}
                </p>
            </div>
            <!-- <a
    href="{{ $listRoute }}"
    class="ml-auto inline-flex items-center gap-2 rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-400 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800/70"
>
    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
    </svg>
    <span>{{ __('Kembali ke daftar transaksi') }}</span>
</a> -->
        </div>

        <div class="grid gap-6 lg:grid-cols-[2fr,1fr]">
            <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <form
                    method="POST"
                    action="{{ route('gadai.transaksi-gadai.extend', ['transaksi' => $transaksi->transaksi_id]) }}"
                    class="space-y-6 p-6"
                >
                    @csrf
                    @foreach (['search', 'tanggal_dari', 'tanggal_sampai', 'per_page', 'page'] as $param)
                        <input type="hidden" name="{{ $param }}" value="{{ $listQuery[$param] ?? '' }}">
                    @endforeach

                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="flex flex-col gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                            <span class="font-medium">{{ __('Tanggal Mulai Tenor Baru') }}</span>
                            <input
                                type="date"
                                name="tanggal_mulai_baru"
                                value="{{ old('tanggal_mulai_baru', $defaultTanggalMulai) }}"
                                required
                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                            >
                            @error('tanggal_mulai_baru')
                                <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="flex flex-col gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                            <span class="font-medium">{{ __('Tenor (Hari)') }}</span>
                            <input
                                type="number"
                                name="tenor_hari"
                                value="{{ old('tenor_hari', $defaultTenor) }}"
                                min="1"
                                max="365"
                                required
                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                            >
                            @error('tenor_hari')
                                <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="flex flex-col gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                            <span class="font-medium">{{ __('Bunga Dibayar') }}</span>
                            <input
                                type="text"
                                name="bunga_dibayar"
                                value="{{ old('bunga_dibayar', number_format($bungaDirekomendasikan, 2, '.', '')) }}"
                                required
                                inputmode="decimal"
                                data-currency-input
                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                                placeholder="{{ __('Nominal bunga yang diterima saat perpanjangan…') }}"
                            >
                            <span class="text-[11px] text-neutral-500 dark:text-neutral-400">
                                @if ($hasElapsed && $bungaDirekomendasikan > 0)
                                    {{ __('Minimal sebesar Rp :amount untuk menutup pemakaian sampai :date.', [
                                        'amount' => number_format($bungaDirekomendasikan, 0, ',', '.'),
                                        'date' => $bungaCutoff->format('d M Y'),
                                    ]) }}
                                @else
                                    {{ __('Belum ada bunga berjalan yang harus dilunasi; masukkan 0 jika tidak ada pembayaran bunga.') }}
                                @endif
                            </span>
                            @error('bunga_dibayar')
                                <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="flex flex-col gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                            <span class="font-medium">{{ __('Biaya Administrasi Perpanjangan (Opsional)') }}</span>
                            <input
                                type="text"
                                name="biaya_admin"
                                value="{{ old('biaya_admin') }}"
                                inputmode="decimal"
                                data-currency-input
                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                                placeholder="{{ __('Jika ada biaya administrasi tambahan…') }}"
                            >
                            @error('biaya_admin')
                                <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    <label class="flex flex-col gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                        <span class="font-medium">{{ __('Biaya Titip Barang (Opsional)') }}</span>
                        <input
                            type="text"
                            name="biaya_titip"
                            value="{{ old('biaya_titip') }}"
                            inputmode="decimal"
                            data-currency-input
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                            placeholder="{{ __('Catat biaya penitipan tambahan bila ditarik saat perpanjangan…') }}"
                        >
                        @error('biaya_titip')
                            <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="flex flex-col gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                        <span class="font-medium">{{ __('Catatan Internal (Opsional)') }}</span>
                        <textarea
                            name="catatan"
                            rows="3"
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                            placeholder="{{ __('Contoh: Denda keterlambatan dibebaskan, tenor diperpanjang atas permintaan nasabah…') }}"
                        >{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </label>

                    <!-- <div class="flex flex-col gap-2 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-200">
                        <div class="font-semibold">{{ __('Ringkasan Perpanjangan') }}</div>
                        <ul class="list-disc space-y-1 pl-4">
                            <li>{{ __('Tenor baru berakhir pada :date.', ['date' => $defaultMulai->copy()->addDays($tenorFormValue - 1)->format('d M Y')]) }}</li>
                            <li>{{ __('Biaya yang dicatat otomatis masuk ke laporan saldo kas pada tanggal perpanjangan.') }}</li>
                            <li>{{ __('Status kontrak berubah menjadi Perpanjang setelah disimpan.') }}</li>
                        </ul>
                    </div> -->

                    <div class="flex items-center justify-end gap-3">
                        <a
                            href="{{ $listRoute }}"
                            class="inline-flex items-center gap-2 rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-400 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800/70"
                        >
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                            <span>{{ __('Batal') }}</span>
                        </a>
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-blue-600 shadow-sm transition hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:bg-emerald-500 dark:hover:bg-emerald-400"
                        >
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            <span>{{ __('Simpan Perpanjangan') }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <aside class="space-y-6">
                <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Informasi Kontrak Saat Ini') }}</h2>
                    <dl class="mt-4 space-y-3 text-sm text-neutral-700 dark:text-neutral-200">
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300">{{ __('No. SBG') }}</dt>
                            <dd class="text-right font-semibold text-neutral-900 dark:text-white">{{ $transaksi->no_sbg }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300">{{ __('Nasabah') }}</dt>
                            <dd class="text-right text-neutral-900 dark:text-white">{{ $nasabah }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300">{{ __('Kasir Penerbit') }}</dt>
                            <dd class="text-right text-neutral-900 dark:text-white">{{ $kasir }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300">{{ __('Tanggal Gadai Saat Ini') }}</dt>
                            <dd class="text-right text-neutral-900 dark:text-white">{{ optional($transaksi->tanggal_gadai)->format('d M Y') ?? '—' }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300">{{ __('Jatuh Tempo') }}</dt>
                            <dd class="text-right text-neutral-900 dark:text-white">{{ optional($transaksi->jatuh_tempo_awal)->format('d M Y') ?? '—' }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300">{{ __('Tenor Aktif') }}</dt>
                            <dd class="text-right text-neutral-900 dark:text-white">{{ $transaksi->tenor_hari ?? '—' }} {{ __('hari') }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300">{{ __('Tarif Bunga Harian') }}</dt>
                            <dd class="text-right text-neutral-900 dark:text-white">{{ number_format(($transaksi->tarif_bunga_harian ?? 0) * 100, 2, ',', '.') }}%</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300">{{ __('Bunga Berjalan') }}</dt>
                            <dd class="text-right text-neutral-900 dark:text-white">Rp {{ number_format($bungaDirekomendasikan, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300">{{ __('Pokok Pinjaman') }}</dt>
                            <dd class="text-right font-semibold text-emerald-600 dark:text-emerald-300">Rp {{ number_format((float) $transaksi->uang_pinjaman, 0, ',', '.') }}</dd>
                        </div>
                    </dl>

                    @if ($barangJaminan->isNotEmpty())
                        <div class="mt-4 space-y-2 text-sm text-neutral-600 dark:text-neutral-300">
                            <p class="font-medium text-neutral-700 dark:text-neutral-200">{{ __('Barang Jaminan') }}</p>
                            <ul class="space-y-2">
                                @foreach ($barangJaminan as $barang)
                                    <li class="rounded-lg border border-neutral-200 px-3 py-2 text-xs text-neutral-700 dark:border-neutral-700 dark:text-neutral-200">
                                        <div class="font-semibold text-neutral-900 dark:text-white">{{ $barang->jenis_barang }} — {{ $barang->merek }}</div>
                                        <div>{{ __('Nilai taksiran: :amount', ['amount' => 'Rp ' . number_format((float) $barang->nilai_taksiran, 0, ',', '.')]) }}</div>
                                        <div class="text-[11px] text-neutral-500 dark:text-neutral-400">{{ __('Kelengkapan:') }} {{ $barang->kelengkapan ?? '—' }}</div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                @if ($riwayatPerpanjanganTerbaru)
                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-6 shadow-sm dark:border-amber-500/60 dark:bg-amber-500/10">
                        <h2 class="text-lg font-semibold text-amber-900 dark:text-amber-200">{{ __('Batalkan Perpanjangan Terakhir') }}</h2>
                        <p class="mt-2 text-sm text-amber-800 dark:text-amber-100">
                            {{ __('Perpanjangan yang dicatat pada :date oleh :user dapat dibatalkan jika terjadi kesalahan input.', [
                                'date' => optional($riwayatPerpanjanganTerbaru->tanggal_perpanjangan)->format('d M Y H:i') ?? '—',
                                'user' => $riwayatPerpanjanganTerbaru->petugas?->name ?? __('Tidak diketahui'),
                            ]) }}
                        </p>

                        <dl class="mt-4 space-y-2 text-xs text-amber-900 dark:text-amber-100">
                            <div class="flex items-start justify-between gap-3">
                                <dt class="font-semibold uppercase tracking-wide">{{ __('Rentang Tenor') }}</dt>
                                <dd>{{ $riwayatPerpanjanganTerbaru->tenor_sebelumnya }} → {{ $riwayatPerpanjanganTerbaru->tenor_baru }} {{ __('hari') }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-3">
                                <dt class="font-semibold uppercase tracking-wide">{{ __('Biaya Dicatat') }}</dt>
                                <dd>Rp {{ number_format((float) $riwayatPerpanjanganTerbaru->total_bayar, 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-3">
                                <dt class="font-semibold uppercase tracking-wide">{{ __('Jatuh Tempo Baru') }}</dt>
                                <dd>{{ optional($riwayatPerpanjanganTerbaru->tanggal_jatuh_tempo_baru)->format('d M Y') ?? '—' }}</dd>
                            </div>
                        </dl>

                        <form
                            method="POST"
                            action="{{ route('gadai.transaksi-gadai.extend-cancel', [
                                'transaksi' => $transaksi->transaksi_id,
                                'perpanjangan' => $riwayatPerpanjanganTerbaru->perpanjangan_id,
                            ]) }}"
                            class="mt-4 space-y-3"
                            onsubmit="return confirm('{{ __('Batalkan perpanjangan ini dan pulihkan tenor sebelumnya?') }}');"
                        >
                            @csrf
                            @method('DELETE')
                            @foreach (['search', 'tanggal_dari', 'tanggal_sampai', 'per_page', 'page'] as $param)
                                <input type="hidden" name="{{ $param }}" value="{{ $listQuery[$param] ?? '' }}">
                            @endforeach

                            <label class="flex flex-col gap-2 text-sm text-amber-900 dark:text-amber-100">
                                <span class="font-medium">{{ __('Alasan Pembatalan (Opsional)') }}</span>
                                <textarea
                                    name="alasan_pembatalan"
                                    rows="2"
                                    class="w-full rounded-lg border border-amber-300 bg-white/80 px-3 py-2 text-sm text-amber-900 shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200 dark:border-amber-400/70 dark:bg-transparent dark:text-amber-100 dark:focus:border-amber-300 dark:focus:ring-amber-500/40"
                                    placeholder="{{ __('Contoh: salah input tenor, biaya perlu dikoreksi…') }}"
                                >{{ old('alasan_pembatalan') }}</textarea>
                                @error('alasan_pembatalan')
                                    <span class="text-xs text-red-600 dark:text-red-300">{{ $message }}</span>
                                @enderror
                            </label>

                            <p class="text-xs text-amber-800 dark:text-amber-100/80">
                                {{ __('Pembatalan akan menghapus mutasi kas perpanjangan dan mengembalikan tenor serta jadwal sebelumnya.') }}
                            </p>

                            <div class="flex items-center justify-end gap-2">
                                <button
                                    type="submit"
                                    class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-red shadow-sm transition hover:bg-red-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 dark:bg-red-500 dark:hover:bg-red-400"
                                >
                                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                    <span>{{ __('Batalkan Perpanjangan') }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Riwayat Perpanjangan') }}</h2>
                    @if ($riwayatPerpanjangan->isEmpty())
                        <p class="mt-3 text-sm text-neutral-600 dark:text-neutral-300">{{ __('Belum ada perpanjangan yang tercatat untuk kontrak ini.') }}</p>
                    @else
                        <ul class="mt-4 space-y-3 text-sm text-neutral-700 dark:text-neutral-200">
                            @foreach ($riwayatPerpanjangan->take(5) as $riwayat)
                                @php
                                    $isCancelled = $riwayat->dibatalkan_pada !== null;
                                @endphp
                                <li @class([
                                    'rounded-lg border px-4 py-3',
                                    'border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-950/60' => !$isCancelled,
                                    'border-red-200 bg-red-50/80 dark:border-red-500/60 dark:bg-red-500/10' => $isCancelled,
                                ])>
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex flex-col gap-1">
                                            <p class="font-semibold text-neutral-900 dark:text-white">
                                                {{ optional($riwayat->tanggal_perpanjangan)->format('d M Y H:i') ?? '—' }}
                                            </p>
                                            <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                                {{ __('Tenor :old → :new hari', ['old' => $riwayat->tenor_sebelumnya, 'new' => $riwayat->tenor_baru]) }}
                                            </p>
                                            <div class="flex items-center gap-2 text-[11px]">
                                                <span @class([
                                                    'inline-flex items-center rounded-full px-2 py-0.5 font-semibold uppercase tracking-wide',
                                                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200' => !$isCancelled,
                                                    'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-200' => $isCancelled,
                                                ])>
                                                    {{ $isCancelled ? __('Dibatalkan') : __('Aktif') }}
                                                </span>
                                                <span class="text-neutral-400 dark:text-neutral-500">•</span>
                                                <span class="text-neutral-500 dark:text-neutral-400">
                                                    {{ __('Jatuh tempo baru: :date', ['date' => optional($riwayat->tanggal_jatuh_tempo_baru)->format('d M Y') ?? '—']) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-right text-xs text-neutral-500 dark:text-neutral-400">
                                            {{ $riwayat->petugas?->name ?? __('Tidak diketahui') }}
                                        </div>
                                    </div>
                                    <div @class([
                                        'mt-2 flex flex-wrap items-center gap-3 text-xs',
                                        'text-neutral-600 dark:text-neutral-300' => !$isCancelled,
                                        'text-neutral-600/80 dark:text-neutral-200/70' => $isCancelled,
                                    ])>
                                        <span>{{ __('Biaya: Rp :amount', ['amount' => number_format((float) $riwayat->total_bayar, 0, ',', '.')]) }}</span>
                                        <span>•</span>
                                        <span>{{ __('Mulai tenor baru: :date', ['date' => optional($riwayat->tanggal_mulai_baru)->format('d M Y') ?? '—']) }}</span>
                                    </div>
                                    @if ($riwayat->catatan)
                                        <p class="mt-2 text-xs italic text-neutral-500 dark:text-neutral-400">“{{ $riwayat->catatan }}”</p>
                                    @endif
                                    @if ($isCancelled)
                                        <div class="mt-3 rounded-lg bg-white/70 px-3 py-2 text-[11px] text-red-700 dark:bg-red-500/10 dark:text-red-200">
                                            <p>
                                                {{ __('Dibatalkan pada :date oleh :user.', [
                                                    'date' => optional($riwayat->dibatalkan_pada)->format('d M Y H:i') ?? '—',
                                                    'user' => $riwayat->pembatal?->name ?? __('Tidak diketahui'),
                                                ]) }}
                                            </p>
                                            <p class="mt-1">
                                                {{ __('Mutasi kas perpanjangan turut dibatalkan secara otomatis.') }}
                                            </p>
                                            @if ($riwayat->alasan_pembatalan)
                                                <p class="mt-1 italic">“{{ $riwayat->alasan_pembatalan }}”</p>
                                            @endif
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        @if ($riwayatPerpanjangan->count() > 5)
                            <p class="mt-3 text-xs text-neutral-500 dark:text-neutral-400">
                                {{ __('Menampilkan 5 catatan terbaru. Lihat laporan perpanjangan untuk riwayat lengkap.') }}
                            </p>
                        @endif
                    @endif
                </div>
            </aside>
        </div>
    </div>
</x-layouts.app>
