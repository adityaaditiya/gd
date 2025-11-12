<x-layouts.app :title="__('Pelunasan Transaksi Gadai')">
    @php
        $listQuery = $query;
        $listRoute = route('gadai.lihat-gadai', $listQuery);
        $nasabah = $transaksi->nasabah?->nama ?? '—';
        $kasir = $transaksi->kasir?->name ?? '—';
        $barangJaminan = $transaksi->barangJaminan ?? collect();
        $perhitungan = $perhitunganPelunasan;
        $tarifBungaPersen = $perhitungan['tarif_bunga'] * 100;
    @endphp

    <div class="space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-2">
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">
                    {{ __('Pelunasan Transaksi Gadai') }}
                </h1>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Konfirmasi penerimaan pelunasan untuk kontrak :number milik :customer.', [
                        'number' => $transaksi->no_sbg,
                        'customer' => $nasabah,
                    ]) }}
                </p>
            </div>
            <a
    href="{{ $listRoute }}"
    class="ml-auto inline-flex items-center gap-2 rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-400 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800/70"
>
    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
    </svg>
    <span>{{ __('Kembali ke daftar transaksi') }}</span>
</a>

        </div>
        
<br>

<aside class="space-y-6">
                <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Informasi Transaksi') }}</h2>
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
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300">{{ __('Kasir Pembuat') }}</dt>
                            <dd class="text-right text-neutral-900 dark:text-white">{{ $kasir }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300">{{ __('Tanggal Gadai') }}</dt>
                            <dd class="text-right text-neutral-900 dark:text-white">{{ optional($transaksi->tanggal_gadai)->format('d M Y') ?? '—' }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300">{{ __('Jatuh Tempo') }}</dt>
                            <dd class="text-right text-neutral-900 dark:text-white">{{ optional($transaksi->jatuh_tempo_awal)->format('d M Y') ?? '—' }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300">{{ __('Uang Pinjaman') }}</dt>
                            <dd class="text-right font-semibold text-emerald-600 dark:text-emerald-300">Rp {{ number_format((float) $transaksi->uang_pinjaman, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300">{{ __('Tarif Bunga Harian') }}</dt>
                            <dd class="text-right text-neutral-900 dark:text-white">{{ number_format($tarifBungaPersen, 2, ',', '.') }}%</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300">{{ __('Hari Pemakaian Aktual') }}</dt>
                            <dd class="text-right text-neutral-900 dark:text-white">{{ $perhitungan['actual_days'] }} {{ __('hari') }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300">{{ __('Sewa Modal Terutang') }}</dt>
                            <dd class="text-right text-neutral-900 dark:text-white">Rp {{ number_format($perhitungan['sewa_modal'], 0, ',', '.') }}</dd>
                        </div>
                        @if ($perhitungan['biaya_lain'] > 0)
                            <div class="flex items-start justify-between gap-4">
                                <dt class="font-medium text-neutral-600 dark:text-neutral-300">{{ __('Biaya Lain-Lain Pelunasan') }}</dt>
                                <dd class="text-right text-neutral-900 dark:text-white">Rp {{ number_format($perhitungan['biaya_lain'], 0, ',', '.') }}</dd>
                            </div>
                        @endif
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

        <div class="grid gap-6 lg:grid-cols-[2fr,1fr]">
            <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <form
                    method="POST"
                    action="{{ route('gadai.transaksi-gadai.settle', ['transaksi' => $transaksi->transaksi_id]) }}"
                    class="space-y-6 p-6"
                >
                    @csrf
                    @foreach (['search', 'tanggal_dari', 'tanggal_sampai', 'per_page', 'page'] as $param)
                        <input type="hidden" name="{{ $param }}" value="{{ $listQuery[$param] ?? '' }}">
                    @endforeach

                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="flex flex-col gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                            <span class="font-medium">{{ __('Tanggal Pelunasan') }}</span>
                            <input
                                type="date"
                                name="tanggal_pelunasan"
                                value="{{ old('tanggal_pelunasan', $defaults['tanggal_pelunasan']) }}"
                                required
                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                            >
                            @error('tanggal_pelunasan')
                                <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="flex flex-col gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                            <span class="font-medium">{{ __('Metode Pembayaran') }}</span>
                            <input
                                type="text"
                                name="metode_pembayaran"
                                value="{{ old('metode_pembayaran', $defaults['metode_pembayaran']) }}"
                                required
                                maxlength="100"
                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                                placeholder="{{ __('Contoh: Tunai, Transfer Bank…') }}"
                            >
                            @error('metode_pembayaran')
                                <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="flex flex-col gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                            <span class="font-medium">{{ __('Pokok Dibayar') }}</span>
                            <input
                                type="text"
                                name="pokok_dibayar"
                                value="{{ old('pokok_dibayar', $defaults['pokok_dibayar']) }}"
                                required
                                inputmode="decimal"
                                data-currency-input
                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                                placeholder="{{ __('Masukkan nominal pokok…') }}"
                            >
                            @error('pokok_dibayar')
                                <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="flex flex-col gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                            <span class="font-medium">{{ __('Bunga Dibayar') }}</span>
                            <input
                                type="text"
                                name="bunga_dibayar"
                                value="{{ old('bunga_dibayar', $defaults['bunga_dibayar']) }}"
                                inputmode="decimal"
                                data-currency-input
                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                                placeholder="{{ __('Masukkan nominal bunga…') }}"
                            >
                            @error('bunga_dibayar')
                                <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    <label class="flex flex-col gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                        <span class="font-medium">{{ __('Biaya Lain-Lain (Opsional)') }}</span>
                        <input
                            type="text"
                            name="biaya_lain_dibayar"
                            value="{{ old('biaya_lain_dibayar', $defaults['biaya_lain_dibayar']) }}"
                            inputmode="decimal"
                            data-currency-input
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                            placeholder="{{ __('Nominal biaya lain-lain yang harus dilunasi (jika ada)…') }}"
                        >
                        @error('biaya_lain_dibayar')
                            <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="flex flex-col gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                        <span class="font-medium">{{ __('Total Pelunasan') }}</span>
                        <input
                            type="text"
                            name="total_pelunasan"
                            value="{{ old('total_pelunasan', $defaults['total_pelunasan']) }}"
                            required
                            inputmode="decimal"
                            data-currency-input
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                            placeholder="{{ __('Total dana yang diterima kasir…') }}"
                        >
                        @error('total_pelunasan')
                            <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="flex flex-col gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                        <span class="font-medium">{{ __('Catatan Pelunasan') }}</span>
                        <textarea
                            name="catatan_pelunasan"
                            rows="3"
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                            placeholder="{{ __('Catat detail tambahan seperti nomor referensi transfer atau kondisi barang saat ditebus…') }}"
                        >{{ old('catatan_pelunasan') }}</textarea>
                        @error('catatan_pelunasan')
                            <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </label>

                    <div class="flex flex-col gap-3 rounded-lg border border-emerald-200 bg-emerald-50/60 px-4 py-3 text-xs text-emerald-800 shadow-sm dark:border-emerald-500/50 dark:bg-emerald-500/10 dark:text-emerald-200">
                        <p class="font-semibold">{{ __('Ringkasan pelunasan') }}</p>
                        <ul class="list-disc space-y-1 pl-4">
                            <li>{{ __('Total pelunasan minimal meliputi pokok pinjaman, sewa modal terutang, dan biaya lain-lain yang Anda cantumkan.') }}</li>
                            <li>{{ __('Setelah disimpan, status transaksi berubah menjadi Lunas dan tercatat pada laporan pelunasan.') }}</li>
                        </ul>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                        <a
                            href="{{ $listRoute }}"
                            class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-600 transition hover:bg-neutral-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-400 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800/70"
                        >
                            {{ __('Batal') }}
                        </a>
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-blue-600 shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
                        >
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m6 .75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span>{{ __('Konfirmasi Pelunasan') }}</span>
                        </button>
                    </div>
                </form>
            </div>

            

                <div class="rounded-xl border border-emerald-200 bg-emerald-50/70 p-6 text-sm text-emerald-800 shadow-sm dark:border-emerald-500/60 dark:bg-emerald-500/10 dark:text-emerald-200">
                    <h2 class="text-lg font-semibold">{{ __('Perhitungan Pelunasan') }}</h2>
                    <dl class="mt-4 space-y-3">
                        <div class="flex items-center justify-between gap-4">
                            <dt>{{ __('Pokok Pinjaman') }}</dt>
                            <dd class="font-semibold">Rp {{ number_format($perhitungan['pokok'], 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt>{{ __('Sewa Modal Terutang') }}</dt>
                            <dd class="font-semibold">Rp {{ number_format($perhitungan['sewa_modal'], 0, ',', '.') }}</dd>
                        </div>
                        @if ($perhitungan['biaya_lain'] > 0)
                            <div class="flex items-center justify-between gap-4">
                                <dt>{{ __('Biaya Lain-Lain Pelunasan') }}</dt>
                                <dd class="font-semibold">Rp {{ number_format($perhitungan['biaya_lain'], 0, ',', '.') }}</dd>
                            </div>
                        @endif
                        <div class="flex items-center justify-between gap-4 border-t border-emerald-200 pt-3 dark:border-emerald-500/40">
                            <dt>{{ __('Total Tagihan Pelunasan') }}</dt>
                            <dd class="text-base font-bold">Rp {{ number_format($perhitungan['total_tagihan'], 0, ',', '.') }}</dd>
                        </div>
                    </dl>
                    <p class="mt-4 text-xs text-emerald-700 dark:text-emerald-200/80">
                        {{ __('Nilai di atas dihitung berdasarkan tarif bunga harian 0,15% dan jumlah hari aktual sejak tanggal gadai.') }}
                    </p>
                </div>
            </aside>
        </div>
    </div>
</x-layouts.app>
