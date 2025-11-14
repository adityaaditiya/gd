@php
    $pendingCancelTransaksiId = old('transaksi_id', session('show_cancel_modal'));
    $pendingCancelTransaksiId = $pendingCancelTransaksiId ? (string) $pendingCancelTransaksiId : '';
    $pendingCancelReason = old('alasan_batal', '');
@endphp

<x-layouts.app :title="__('Lihat Gadai')">
    <div class="space-y-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Data Transaksi Gadai') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Pantau seluruh kontrak gadai yang aktif lengkap dengan detail nasabah, barang jaminan, dan estimasi bunga harian.') }}
            </p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm dark:border-emerald-500/60 dark:bg-emerald-500/10 dark:text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 shadow-sm dark:border-red-500/60 dark:bg-red-500/10 dark:text-red-200">
                {{ session('error') }}
            </div>
        @endif

        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex flex-col gap-4 border-b border-neutral-200 p-4 dark:border-neutral-700">
                
                <form
                    method="GET"
                    action="{{ route('gadai.lihat-gadai') }}"
                    class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between"
                    data-filter-form
                    data-auto-submit="{{ $shouldAutoSubmitFilters ? 'true' : 'false' }}"
                >
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:gap-4">
                        <label class="flex flex-col gap-2 text-sm text-neutral-600 dark:text-neutral-200">
                            <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Tanggal Dari') }}</span>
                            <input
                                id="tanggal-dari"
                                name="tanggal_dari"
                                type="date"
                                value="{{ $tanggalDari }}"
                                class="rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-200 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                                onchange="this.form.requestSubmit()"
                            />
                        </label>
                        <label class="flex flex-col gap-2 text-sm text-neutral-600 dark:text-neutral-200">
                            <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Tanggal Sampai') }}</span>
                            <input
                                id="tanggal-sampai"
                                name="tanggal_sampai"
                                type="date"
                                value="{{ $tanggalSampai }}"
                                class="rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-200 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                                onchange="this.form.requestSubmit()"
                            />
                        </label>
                        <div class="flex flex-col gap-2 text-sm text-neutral-600 dark:text-neutral-200">
                            <span></span>
                            <span></span>
                            <span></span>

                            @if (!empty($search) || $tanggalDari || $tanggalSampai)
                                <a
                                    href="{{ route('gadai.lihat-gadai', ['per_page' => $perPage]) }}"
                                    class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-3 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-700/60"
                                >
                                    {{ __('Reset') }}
                                </a>
                            @endif
                            
                        </div>
                        <div class="flex flex-col gap-2 text-sm text-neutral-600 dark:text-neutral-200">
                            <span></span>
                            <span></span>
                            <span></span>
                        <a
                                    href="{{ route('gadai.pemberian-kredit') }}"
                                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-blue-600 shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
                                >
                                    {{ __('Tambah Data') }}
                                </a>
                        </div>
                    </div>
                    <label class="flex w-full items-center gap-3 rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-600 shadow-sm focus-within:border-emerald-500 focus-within:text-neutral-900 focus-within:ring-2 focus-within:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-300 dark:focus-within:border-emerald-400 dark:focus-within:text-white dark:focus-within:ring-emerald-500/40" for="search-transaksi">
                        <svg class="size-5 text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        <div class="flex w-full flex-col">
                            <input
                                id="search-transaksi"
                                name="search"
                                type="search"
                                value="{{ $search ?? '' }}"
                                placeholder="{{ __('Cari No. SBG, nama nasabah, kode member, atau telepon…') }}"
                                class="w-full border-0 bg-transparent p-0 text-sm text-neutral-900 placeholder:text-neutral-400 focus:outline-none focus:ring-0 dark:text-white"
                            />
                        </div>
                    </label>
                </form>
            </div>
            <div
                class="overflow-x-auto"
                data-transaksi-gadai-table
            >
                <table class="min-w-full divide-y divide-neutral-200 text-left text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200">
                <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">{{ __('No. SBG') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Nasabah') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Barang Jaminan') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Pinjaman') }}</th>
                        <!-- <th scope="col" class="px-4 py-3">{{ __('Premi') }}</th> -->
                        <th scope="col" class="px-4 py-3">{{ __('Tenor') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Bunga Terakumulasi') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Tarif Bunga Harian') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Jatuh Tempo') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Status') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Kasir') }}</th>
                        <th scope="col" class="px-4 py-3 text-center">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                    @forelse ($transaksiGadai as $transaksi)
                        <tr class="align-top hover:bg-neutral-50 dark:hover:bg-neutral-700/70">
                            @php
                                $pelunasanBiayaSaran = (float) $transaksi->biaya_admin + (float) $transaksi->premi;
                                $pelunasanTotalSaran = (float) $transaksi->uang_pinjaman + (float) $transaksi->total_bunga + $pelunasanBiayaSaran;
                            @endphp
                            <td class="whitespace-nowrap px-4 py-3 font-semibold text-neutral-900 dark:text-white">
                                {{ $transaksi->no_sbg }}
                                <div class="text-xs font-normal text-neutral-500 dark:text-neutral-300">
                                    {{ __('Tanggal Gadai: :date', ['date' => optional($transaksi->tanggal_gadai)->format('d M Y') ?? '—']) }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="font-medium text-neutral-900 dark:text-white">{{ $transaksi->nasabah?->nama ?? '—' }}</span>
                                    <span class="text-xs text-neutral-500 dark:text-neutral-300">{{ $transaksi->nasabah?->kode_member ?? '' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if ($transaksi->barangJaminan->isEmpty())
                                    <span class="text-xs text-neutral-500 dark:text-neutral-300">{{ __('Belum ada barang terhubung') }}</span>
                                @else
                                    @php
                                        $barangCount = $transaksi->barangJaminan->count();
                                        $firstBarang = $transaksi->barangJaminan->first();
                                        $additionalBarang = max($barangCount - 1, 0);
                                        $collateralTemplateId = 'collateral-data-' . $transaksi->transaksi_id;
                                    @endphp
                                    <div class="flex flex-col gap-2">
                                        <span class="text-sm font-semibold text-neutral-900 dark:text-white">
                                            {{ __(':count Barang Jaminan', ['count' => $barangCount]) }}
                                        </span>
                                        <span class="text-xs text-neutral-500 dark:text-neutral-300">
                                            {{ $firstBarang?->jenis_barang ?? '—' }}@if ($firstBarang?->merek)
                                                · {{ $firstBarang->merek }}
                                            @endif
                                            @if ($additionalBarang > 0)
                                                <span class="text-neutral-400 dark:text-neutral-400">{{ __('dan :count lainnya', ['count' => $additionalBarang]) }}</span>
                                            @endif
                                        </span>
                                        <div class="flex flex-wrap gap-2">
                                            <button
                                                type="button"
                                                class="inline-flex items-center gap-2 rounded-lg border border-emerald-600 px-3 py-2 text-xs font-semibold text-emerald-600 transition hover:border-emerald-700 hover:bg-emerald-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-400 dark:text-emerald-300 dark:hover:border-emerald-300 dark:hover:bg-emerald-500/10"
                                                data-open-collateral
                                                data-target="{{ $collateralTemplateId }}"
                                                aria-haspopup="dialog"
                                                aria-controls="collateral-panel"
                                            >
                                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v-1.5a3 3 0 0 0-3-3h-6a3 3 0 0 0-3 3v1.5m18 0v-1.5a3 3 0 0 0-3-3h-1.5m-3-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 9v-1.125a2.625 2.625 0 0 0-2.625-2.625H15.75m-9.75 3.75a2.625 2.625 0 0 1 2.625-2.625H12" />
                                                </svg>
                                                <span>{{ __('Lihat Detail Barang') }}</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div id="{{ $collateralTemplateId }}" class="hidden" data-collateral-template>
                                        <div class="space-y-4">
                                            @foreach ($transaksi->barangJaminan as $barang)
                                                <article class="rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-950">
                                                    <div class="flex flex-col gap-2">
                                                        <div class="flex items-start justify-between gap-3">
                                                            <div>
                                                                <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">
                                                                    {{ __('Barang :number', ['number' => $loop->iteration]) }}
                                                                </h3>
                                                                <p class="text-xs text-neutral-500 dark:text-neutral-300">
                                                                    {{ $barang->jenis_barang ?? '—' }}@if ($barang->merek)
                                                                        · {{ $barang->merek }}
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div class="text-right">
                                                                <span class="text-[11px] font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Nilai Taksiran') }}</span>
                                                                <div class="text-sm font-semibold text-emerald-600 dark:text-emerald-300">Rp {{ number_format((float) $barang->nilai_taksiran, 0, ',', '.') }}</div>
                                                            </div>
                                                        </div>
                                                        <dl class="grid grid-cols-1 gap-3 text-sm text-neutral-700 dark:text-neutral-200">
                                                            <div class="space-y-1">
                                                                <dt class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Kondisi') }}</dt>
                                                                <dd>{{ $barang->kondisi_fisik ?? '—' }}</dd>
                                                            </div>
                                                            <div class="space-y-1">
                                                                <dt class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Kelengkapan') }}</dt>
                                                                <dd>{{ $barang->kelengkapan ?? '—' }}</dd>
                                                            </div>
                                                            <div class="space-y-1">
                                                                <dt class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Keterangan') }}</dt>
                                                                <dd>{{ $barang->keterangan ?? '—' }}</dd>
                                                            </div>
                                                        </dl>
                                                    </div>
                                                </article>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">
                                <div class="font-semibold text-emerald-600 dark:text-emerald-300">Rp {{ number_format((float) $transaksi->uang_pinjaman, 0, ',', '.') }}</div>
                                @if ((float) $transaksi->biaya_admin > 0)
                                    <div class="text-xs text-neutral-500 dark:text-neutral-300">{{ __('Biaya admin: Rp :amount', ['amount' => number_format((float) $transaksi->biaya_admin, 0, ',', '.')]) }}</div>
                                @endif
                                @if ($transaksi->premi !== null)
                                    <div class="text-xs text-neutral-500 dark:text-neutral-300">{{ __('Premi: Rp :amount', ['amount' => number_format($transaksi->premi, 0, ',', '.')]) }}</div>
                                @endif
                                @if ($transaksi->total_potongan > 0)
                                    <div class="text-xs text-neutral-500 dark:text-neutral-300">{{ __('Total potongan: Rp :amount', ['amount' => number_format($transaksi->total_potongan, 0, ',', '.')]) }}</div>
                                @endif
                                @if ($transaksi->uang_cair !== null)
                                    <div class="text-xs font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Uang cair: Rp :amount', ['amount' => number_format($transaksi->uang_cair, 0, ',', '.')]) }}</div>
                                @endif
                            </td>
                            <!-- <td class="whitespace-nowrap px-4 py-3">
                                <div class="font-semibold text-neutral-900 dark:text-white">Rp {{ number_format((float) $transaksi->premi, 0, ',', '.') }}</div>
                            </td> -->
                            <td class="whitespace-nowrap px-4 py-3">
                                <div class="font-semibold text-neutral-900 dark:text-white">
                                    {{ $transaksi->tenor_hari ? __(':days hari', ['days' => $transaksi->tenor_hari]) : '—' }}
                                </div>
                                @if ($transaksi->actual_days)
                                    <div class="text-xs text-neutral-500 dark:text-neutral-300">
                                        {{ __('Hari berjalan: :days hari', ['days' => $transaksi->actual_days]) }}
                                    </div>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">
                                @php
                                    $accruedInterest = $transaksi->accrued_interest;
                                @endphp
                                @if ($accruedInterest !== null)
                                    Rp {{ number_format($accruedInterest, 0, ',', '.') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">{{ number_format((float) $transaksi->tarif_bunga_harian * 100, 2, ',', '.') }}%</td>
                            <td class="whitespace-nowrap px-4 py-3">{{ optional($transaksi->jatuh_tempo_awal)->format('d M Y') ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-emerald-600 dark:text-emerald-300">
                                    <span>{{ $transaksi->status_transaksi ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col text-xs text-neutral-600 dark:text-neutral-300">
                                    <span>{{ __('Kasir:') }} {{ $transaksi->kasir?->name ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="relative flex justify-center" data-more-container>
                                    <button
                                        type="button"
                                        class="inline-flex items-center rounded-full border border-neutral-200 bg-white p-2 text-neutral-500 transition hover:border-neutral-300 hover:text-neutral-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:border-neutral-500 dark:hover:text-white"
                                        data-more-toggle
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                    >
                                        <span class="sr-only">{{ __('Menu aksi untuk transaksi') }}</span>
                                        <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.008M12 12h.008M19 12h.008" />
                                        </svg>
                                    </button>
                                    <div
                                        class="absolute right-0 top-full z-20 mt-2 hidden w-48 rounded-lg border border-neutral-200 bg-white py-1 text-sm shadow-lg dark:border-neutral-600 dark:bg-neutral-900"
                                        data-more-menu
                                        role="menu"
                                    >
                                        <button
                                            type="button"
                                            class="flex w-full items-center gap-2 px-4 py-2 text-left text-neutral-700 transition hover:bg-neutral-50 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50 dark:text-neutral-200 dark:hover:bg-neutral-700/60"
                                            data-menu-item="cancel"
                                            data-transaksi-id="{{ $transaksi->transaksi_id }}"
                                            data-no-sbg="{{ $transaksi->no_sbg }}"
                                            data-nasabah="{{ $transaksi->nasabah?->nama ?? '' }}"
                                            data-uang-pinjaman="Rp {{ number_format((float) $transaksi->uang_pinjaman, 0, ',', '.') }}"
                                            {{ in_array($transaksi->status_transaksi, ['Lunas', 'Perpanjang', 'Siap Lelang', 'Lelang', 'Batal'], true) ? 'disabled' : '' }}
                                            role="menuitem"
                                        >
                                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            <span>{{ __('Batal Gadai') }}</span>
                                        </button>
                                        @php
                                            $listingQuery = collect([
                                                'search' => $search,
                                                'tanggal_dari' => $tanggalDari,
                                                'tanggal_sampai' => $tanggalSampai,
                                                'per_page' => $perPage,
                                                'page' => $transaksiGadai->currentPage(),
                                            ])->filter(fn ($value) => $value !== null && $value !== '')->all();
                                            $canExtend = in_array($transaksi->status_transaksi, ['Aktif', 'Perpanjang'], true);
                                            $extendUrl = $canExtend
                                                ? route('gadai.transaksi-gadai.extend-form', array_merge(['transaksi' => $transaksi->transaksi_id], $listingQuery))
                                                : null;
                                            $canSettle = !in_array($transaksi->status_transaksi, ['Lunas', 'Siap Lelang', 'Lelang', 'Batal'], true);
                                            $settleUrl = $canSettle
                                                ? route('gadai.transaksi-gadai.settle-form', array_merge(['transaksi' => $transaksi->transaksi_id], $listingQuery))
                                                : null;
                                        @endphp
                                        @if ($canExtend)
                                            <a
                                                href="{{ $extendUrl }}"
                                                class="flex w-full items-center gap-2 px-4 py-2 text-left text-neutral-700 transition hover:bg-neutral-50 focus:outline-none dark:text-neutral-200 dark:hover:bg-neutral-700/60"
                                                role="menuitem"
                                            >
                                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 8.25V5.25H13.5M7.5 15.75V18.75H10.5M8.25 5.25H5.25V8.25M15.75 18.75H18.75V15.75M9 12a3 3 0 1 1 6 0 3 3 0 0 1-6 0Z" />
                                                </svg>
                                                <span>{{ __('Perpanjang Gadai') }}</span>
                                            </a>
                                        @else
                                            <span
                                                class="flex w-full items-center gap-2 px-4 py-2 text-left text-neutral-400 opacity-50 cursor-not-allowed dark:text-neutral-500"
                                                role="menuitem"
                                                aria-disabled="true"
                                            >
                                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 8.25V5.25H13.5M7.5 15.75V18.75H10.5M8.25 5.25H5.25V8.25M15.75 18.75H18.75V15.75M9 12a3 3 0 1 1 6 0 3 3 0 0 1-6 0Z" />
                                                </svg>
                                                <span>{{ __('Perpanjang Gadai') }}</span>
                                            </span>
                                        @endif
                                        @if ($canSettle)
                                            <a
                                                href="{{ $settleUrl }}"
                                                class="flex w-full items-center gap-2 px-4 py-2 text-left text-neutral-700 transition hover:bg-neutral-50 focus:outline-none dark:text-neutral-200 dark:hover:bg-neutral-700/60"
                                                role="menuitem"
                                            >
                                        @else
                                            <span
                                                class="flex w-full items-center gap-2 px-4 py-2 text-left text-neutral-400 opacity-50 cursor-not-allowed dark:text-neutral-500"
                                                aria-disabled="true"
                                                role="menuitem"
                                            >
                                        @endif
                                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m6 .75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                                <span>{{ __('Pelunasan Gadai') }}</span>
                                        @if ($canSettle)
                                            </a>
                                        @else
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-300">
                                {{ __('Belum ada transaksi gadai yang tersimpan.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
            <div class="border-t border-neutral-200 bg-neutral-50 px-4 py-3 dark:border-neutral-700 dark:bg-neutral-900">
                <x-table.pagination-controls
                    :paginator="$transaksiGadai"
                    :per-page="$perPage"
                    :per-page-options="$perPageOptions"
                    :form-action="route('gadai.lihat-gadai')"
                />
            </div>
        </div>
    </div>

    <div
        id="collateral-panel"
        class="fixed inset-0 z-40 hidden"
        data-collateral-panel
        data-placeholder="{{ __('Pilih salah satu transaksi untuk melihat detail barang jaminan.') }}"
        data-empty="{{ __('Barang jaminan tidak ditemukan.') }}"
        role="dialog"
        aria-modal="true"
        aria-labelledby="collateral-panel-title"
        aria-hidden="true"
    >
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" data-collateral-overlay></div>
        <div class="absolute inset-y-0 right-0 flex max-w-full pl-10">
            <div
                class="pointer-events-auto flex h-full w-screen max-w-md min-h-0 flex-col overflow-hidden bg-white shadow-xl transition dark:bg-neutral-900"
                data-collateral-dialog
                tabindex="-1"
            >
                <div class="flex items-start justify-between gap-4 border-b border-neutral-200 px-6 py-4 dark:border-neutral-700">
                    <div class="space-y-1">
                        <h2 id="collateral-panel-title" class="text-base font-semibold text-neutral-900 dark:text-white">
                            {{ __('Detail Barang Jaminan') }}
                        </h2>
                        <p class="text-xs text-neutral-500 dark:text-neutral-300">
                            {{ __('Periksa informasi lengkap setiap barang dalam transaksi ini.') }}
                        </p>
                    </div>
                    <button
                        type="button"
                        class="text-neutral-400 transition hover:text-neutral-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-400 dark:text-neutral-500 dark:hover:text-neutral-300"
                        data-collateral-close
                    >
                        <span class="sr-only">{{ __('Tutup panel detail') }}</span>
                        <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex-1 min-h-0 overflow-y-auto px-6 py-5" data-collateral-body>
                    <p class="text-sm text-neutral-500 dark:text-neutral-300">{{ __('Pilih salah satu transaksi untuk melihat detail barang jaminan.') }}</p>
                </div>
            </div>
        </div>
    </div>

    @once
<script data-navigate-once>
  window.KRESNO = window.KRESNO || {};
  if (!window.KRESNO.lihatGadaiBound) {
    const state = {
      activeDropdown: null,
      table: null,
      collateral: {
        panel: null,
        open: null,
        close: null,
        lastFocused: null,
        focusables: [],
      },
    };

    const closeDropdown = () => {
      if (!state.activeDropdown) return;
      const { menu, toggle } = state.activeDropdown;
      menu.classList.add('hidden');
      toggle.setAttribute('aria-expanded', 'false');
      state.activeDropdown = null;
    };

    const initCancelModal = () => {
      const modal = document.getElementById('cancel-modal');
      if (!modal) {
        window.KRESNO.cancelModal = {
          open: () => {},
          close: () => {},
        };
        return;
      }

      const form = modal.querySelector('[data-cancel-form]');
      const summary = modal.querySelector('[data-cancel-summary]');
      const reasonField = modal.querySelector('[data-cancel-reason]');
      const hiddenTransaksi = form?.querySelector('input[name="transaksi_id"]');
      const actionTemplate = form?.dataset.actionTemplate ?? '';

      const closeModal = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('overflow-hidden');
      };

      const openModal = (button, presetReason = '') => {
        if (!form) return;
        const transaksiId = button.dataset.transaksiId;
        if (!transaksiId) return;

        form.action = actionTemplate.replace('__TRANSAKSI__', transaksiId);
        if (hiddenTransaksi) {
          hiddenTransaksi.value = transaksiId;
        }

        if (summary) {
          const noSbg = button.dataset.noSbg || '';
          const nasabah = button.dataset.nasabah || '';
          const amount = button.dataset.uangPinjaman || '';
          const parts = [noSbg, nasabah, amount].filter(Boolean);
          summary.textContent = parts.join(' • ');
        }

        if (reasonField) {
          reasonField.value = presetReason || '';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('overflow-hidden');

        requestAnimationFrame(() => {
          reasonField?.focus();
          if (reasonField) {
            const length = reasonField.value.length;
            reasonField.setSelectionRange(length, length);
          }
        });
      };

      if (modal.dataset.bound !== 'true') {
        modal.dataset.bound = 'true';

        modal.querySelectorAll('[data-cancel-close]').forEach((element) => {
          element.addEventListener('click', (event) => {
            event.preventDefault();
            closeModal();
          });
        });

        modal.addEventListener('click', (event) => {
          if (event.target === modal) {
            closeModal();
          }
        });

        document.addEventListener('keydown', (event) => {
          if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
          }
        });
      }

      window.KRESNO.cancelModal = { open: openModal, close: closeModal };

      const initialTransaksi = modal.dataset.initialTransaksi || '';
      const initialReason = modal.dataset.initialReason || '';
      if (initialTransaksi) {
        const trigger = document.querySelector(`[data-menu-item="cancel"][data-transaksi-id="${initialTransaksi}"]`);
        if (trigger) {
          openModal(trigger, initialReason);
        }
        modal.dataset.initialTransaksi = '';
        modal.dataset.initialReason = '';
      }
    };

    const initCollateralPanel = () => {
      const panel = document.querySelector('[data-collateral-panel]');
      if (!panel || panel.dataset.bound === 'true') {
        return;
      }

      panel.dataset.bound = 'true';

      const overlay = panel.querySelector('[data-collateral-overlay]');
      const dialog = panel.querySelector('[data-collateral-dialog]');
      const body = panel.querySelector('[data-collateral-body]');
      const closeButtons = panel.querySelectorAll('[data-collateral-close]');
      const focusableSelector = 'a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])';

      const renderMessage = (text) => {
        if (!body) {
          return;
        }

        body.innerHTML = '';
        const paragraph = document.createElement('p');
        paragraph.className = 'text-sm text-neutral-500 dark:text-neutral-300';
        paragraph.textContent = text;
        body.appendChild(paragraph);
      };

      const trapFocus = (event) => {
        if (event.key !== 'Tab') {
          return;
        }

        const focusables = state.collateral.focusables;
        if (!focusables.length) {
          event.preventDefault();
          dialog?.focus();
          return;
        }

        const first = focusables[0];
        const last = focusables[focusables.length - 1];
        if (event.shiftKey) {
          if (document.activeElement === first) {
            event.preventDefault();
            last.focus();
          }
        } else if (document.activeElement === last) {
          event.preventDefault();
          first.focus();
        }
      };

      const closePanel = () => {
        if (!panel || panel.classList.contains('hidden')) {
          return;
        }

        panel.classList.add('hidden');
        panel.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('overflow-hidden');
        renderMessage(panel.dataset.placeholder || '');
        if (state.collateral.lastFocused) {
          state.collateral.lastFocused.focus();
        }
        state.collateral.focusables = [];
        state.collateral.lastFocused = null;
      };

      const openPanel = (trigger) => {
        if (!panel || !dialog || !body) {
          return;
        }

        const targetId = trigger.dataset.target;
        if (!targetId) {
          return;
        }

        const template = document.getElementById(targetId);
        if (!template) {
          return;
        }

        const content = template.innerHTML.trim();
        if (content) {
          body.innerHTML = content;
        } else {
          renderMessage(panel.dataset.empty || '');
        }

        panel.classList.remove('hidden');
        panel.setAttribute('aria-hidden', 'false');
        document.body.classList.add('overflow-hidden');

        state.collateral.lastFocused = trigger;

        const focusables = Array.from(dialog.querySelectorAll(focusableSelector)).filter((element) => element.offsetParent !== null || element === dialog);
        state.collateral.focusables = focusables.length ? focusables : [dialog];

        requestAnimationFrame(() => {
          (state.collateral.focusables[0] || dialog).focus({ preventScroll: false });
        });
      };

      overlay?.addEventListener('click', (event) => {
        event.preventDefault();
        closePanel();
      });

      closeButtons.forEach((button) => {
        button.addEventListener('click', (event) => {
          event.preventDefault();
          closePanel();
        });
      });

      panel.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
          event.preventDefault();
          closePanel();
          return;
        }

        trapFocus(event);
      });

      state.collateral = {
        panel,
        open: openPanel,
        close: closePanel,
        lastFocused: null,
        focusables: [],
      };
    };

    const bindTable = () => {
      const table = document.querySelector('[data-transaksi-gadai-table]');
      if (!table || table.dataset.bound === 'true') {
        return;
      }

      table.dataset.bound = 'true';
      state.table = table;

      table.addEventListener('click', (event) => {
        const toggle = event.target.closest('[data-more-toggle]');
        if (toggle) {
          event.preventDefault();
          const container = toggle.closest('[data-more-container]');
          if (!container) return;

          const menu = container.querySelector('[data-more-menu]');
          if (!menu) return;

          if (state.activeDropdown && state.activeDropdown.menu === menu) {
            closeDropdown();
            return;
          }

          closeDropdown();
          menu.classList.remove('hidden');
          toggle.setAttribute('aria-expanded', 'true');
          state.activeDropdown = { menu, toggle };
          return;
        }

        const cancelButton = event.target.closest('[data-menu-item="cancel"]');
        if (cancelButton) {
          event.preventDefault();
          if (cancelButton.disabled) {
            return;
          }
          closeDropdown();
          (window.KRESNO.cancelModal || { open: () => {} }).open(cancelButton);
          return;
        }

        const collateralButton = event.target.closest('[data-open-collateral]');
        if (collateralButton) {
          event.preventDefault();
          if (typeof state.collateral.open === 'function') {
            state.collateral.open(collateralButton);
          }
          return;
        }

        if (event.target.closest('[data-more-menu]')) {
          return;
        }

        closeDropdown();
      });
    };

    document.addEventListener('click', (event) => {
      if (!state.activeDropdown) return;
      if (state.table && state.table.contains(event.target)) return;
      closeDropdown();
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        closeDropdown();
        if (typeof state.collateral.close === 'function') {
          state.collateral.close();
        }
      }
    });

    const bootstrap = () => {
      initCancelModal();
      initCollateralPanel();
      bindTable();
    };

    document.addEventListener('DOMContentLoaded', bootstrap);
    document.addEventListener('livewire:navigated', bootstrap);

    window.KRESNO.lihatGadaiBound = true;
  }
</script>
@endonce

    <div
        id="cancel-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4 py-6 backdrop-blur-sm"
        role="dialog"
        aria-modal="true"
        aria-labelledby="cancel-modal-title"
        data-initial-transaksi="{{ $pendingCancelTransaksiId }}"
        data-initial-reason="{{ e($pendingCancelReason) }}"
    >
        <div class="mx-auto w-full max-w-lg rounded-2xl bg-white shadow-xl dark:bg-neutral-900">
            <div class="flex items-center justify-between border-b border-neutral-200 px-6 py-4 dark:border-neutral-700">
                <div>
                    <h2 id="cancel-modal-title" class="text-lg font-semibold text-neutral-900 dark:text-white">
                        {{ __('Batalkan Transaksi Gadai') }}
                    </h2>
                    <p class="text-sm text-neutral-500 dark:text-neutral-300" data-cancel-summary></p>
                </div>
                <button type="button" class="text-neutral-400 transition hover:text-neutral-600 dark:text-neutral-500 dark:hover:text-neutral-300" data-cancel-close>
                    <span class="sr-only">{{ __('Tutup modal') }}</span>
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form
                method="POST"
                class="space-y-4 px-6 py-5"
                data-cancel-form
                data-action-template="{{ route('gadai.transaksi-gadai.cancel', ['transaksi' => '__TRANSAKSI__']) }}"
            >
                @csrf
                <input type="hidden" name="transaksi_id" value="">
                <input type="hidden" name="search" value="{{ $search }}">
                <input type="hidden" name="tanggal_dari" value="{{ $tanggalDari }}">
                <input type="hidden" name="tanggal_sampai" value="{{ $tanggalSampai }}">
                <input type="hidden" name="per_page" value="{{ $perPage }}">
                <input type="hidden" name="page" value="{{ $transaksiGadai->currentPage() }}">
                <label class="flex flex-col gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                    <span class="font-medium">{{ __('Alasan Pembatalan') }}</span>
                    <textarea
                        name="alasan_batal"
                        rows="4"
                        required
                        class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:focus:border-red-400 dark:focus:ring-red-500/40"
                        placeholder="{{ __('Jelaskan mengapa transaksi ini dibatalkan…') }}"
                        data-cancel-reason
                    >{{ old('alasan_batal') }}</textarea>
                    @error('alasan_batal')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>
                <div class="flex items-center justify-between gap-3">
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-600 transition hover:bg-neutral-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-400 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800/70"
                        data-cancel-close
                    >
                        {{ __('Batal') }}
                    </button>
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-red-600 bg-red-600 px-4 py-2 text-sm font-semibold text-red shadow-sm transition hover:border-red-700 hover:bg-red-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 dark:border-red-500 dark:bg-red-500 dark:hover:border-red-400 dark:hover:bg-red-400"
                    >
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span>{{ __('Konfirmasi Batal') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filterForm = document.querySelector('[data-filter-form]');

            if (filterForm && filterForm.dataset.autoSubmit === 'true') {
                filterForm.requestSubmit();
            }
        });
    </script>
@endpush
