<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            @php
                $gadaiRoutes = [
                    'gadai.pemberian-kredit',
                    'gadai.lihat-gadai',
                    'gadai.lihat-barang-gadai',
                    'gadai.lihat-data-lelang',
                ];
                $isGadaiActive = request()->routeIs(...$gadaiRoutes);
                $laporanRoutes = [
                    'laporan.saldo-kas',
                    'laporan.transaksi-gadai',
                    'laporan.pelunasan-gadai',
                    'laporan.batal-gadai',
                    'laporan.perpanjangan-gadai',
                    'laporan.lelang',
                    'laporan.cicil-emas',
                ];
                $isLaporanActive = request()->routeIs(...$laporanRoutes);
                $akuntansiRoutes = [
                    'akuntansi.jurnal',
                    'akuntansi.buku-besar',
                    'akuntansi.neraca-percobaan',
                    'akuntansi.laba-rugi',
                    'akuntansi.neraca',
                ];
                $isAkuntansiActive = request()->routeIs(...$akuntansiRoutes);
                $cicilEmasRoutes = [
                    'cicil-emas.transaksi-emas',
                    'cicil-emas.daftar-cicilan',
                    'cicil-emas.angsuran-rutin',
                    'cicil-emas.riwayat-cicilan',
                    'cicil-emas.pelunasan-cicilan',
                ];
                $isCicilEmasActive = request()->routeIs(...$cicilEmasRoutes);
                $jualEmasRoutes = ['jual-emas.transaksi-penjualan', 'jual-emas.lihat-penjualan', 'jual-emas.batal-penjualan'];
                $isJualEmasActive = request()->routeIs(...$jualEmasRoutes);
                $beliEmasRoutes = ['beli-emas.transaksi-pembelian', 'beli-emas.lihat-pembelian', 'beli-emas.batal-pembelian'];
                $isBeliEmasActive = request()->routeIs(...$beliEmasRoutes);
                $titipEmasRoutes = ['titip-emas.transaksi-titip-emas', 'titip-emas.lihat-titipan'];
                $isTitipEmasActive = request()->routeIs(...$titipEmasRoutes);
                $nasabahRoutes = [
                    'nasabah.tambah-nasabah',
                    'nasabah.data-nasabah',
                    'nasabah.lihat-transaksi-nasabah',
                    'nasabah.nasabah-baru',
                    'nasabah.cdd-nasabah',
                ];
                $isNasabahActive = request()->routeIs(...$nasabahRoutes);
                $masterRoutes = ['admin.users.*', 'admin.pages.*'];
                $isMasterActive = request()->routeIs(...$masterRoutes);
            @endphp

            <nav class="flex flex-col gap-3">
                <div>
                    <a
                        href="{{ route('dashboard') }}"
                        wire:navigate
                        @class([
                            'flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold transition-colors duration-200',
                            'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('dashboard'),
                            'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('dashboard'),
                        ])
                    >
                        <svg
                            class="size-5"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m2.25 12 9-9 9 9M4.5 9.75v10.125A1.125 1.125 0 0 0 5.625 21h12.75A1.125 1.125 0 0 0 19.5 19.875V9.75"
                            />
                        </svg>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </div>

                <div>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-neutral-700 transition-colors duration-200 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:hover:text-white"
                        data-accordion-toggle
                        data-accordion-target="gadai-menu"
                        aria-expanded="{{ $isGadaiActive ? 'true' : 'false' }}"
                    >
                        <span class="flex items-center gap-2">
                            <svg
                                class="size-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3 6.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v2.25C7.5 9.246 6.996 9.75 6.375 9.75h-2.25A1.125 1.125 0 0 1 3 8.625v-2.25Zm0 8.25c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 0 1 3 16.875v-2.25ZM9.75 6.375c0-.621.504-1.125 1.125-1.125h10.5c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-10.5A1.125 1.125 0 0 1 9.75 8.625v-2.25Zm0 8.25c0-.621.504-1.125 1.125-1.125h10.5c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-10.5a1.125 1.125 0 0 1-1.125-1.125v-2.25Z"
                                />
                            </svg>
                            <span>{{ __('Gadai Elektronik') }}</span>
                        </span>
                        <svg
                            data-accordion-icon
                            class="size-4 transform transition-transform duration-300 {{ $isGadaiActive ? 'rotate-90' : '' }}"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                    <div
                        id="gadai-menu"
                        class="ms-3 mt-1 space-y-1 overflow-hidden text-sm transition-all duration-300"
                        style="max-height: {{ $isGadaiActive ? '500px' : '0px' }};"
                    >
                        <a
                            href="{{ route('gadai.pemberian-kredit') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('gadai.pemberian-kredit'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('gadai.pemberian-kredit'),
                            ])
                        >
                            {{ __('Pemberian Kredit') }}
                        </a>
                        <a
                            href="{{ route('gadai.lihat-gadai') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('gadai.lihat-gadai'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('gadai.lihat-gadai'),
                            ])
                        >
                            {{ __('Lihat Gadai') }}
                        </a>
                        <a
                            href="{{ route('gadai.lihat-barang-gadai') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('gadai.lihat-barang-gadai'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('gadai.lihat-barang-gadai'),
                            ])
                        >
                            {{ __('Lihat Barang Gadai') }}
                        </a>
                        <a
                            href="{{ route('gadai.lihat-data-lelang') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('gadai.lihat-data-lelang'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('gadai.lihat-data-lelang'),
                            ])
                        >
                            {{ __('Lihat Data Lelang') }}
                        </a>
                    </div>
                </div>
                <div>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-neutral-700 transition-colors duration-200 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:hover:text-white"
                        data-accordion-toggle
                        data-accordion-target="cicil-emas-menu"
                        aria-expanded="{{ $isCicilEmasActive ? 'true' : 'false' }}"
                    >
                        <span class="flex items-center gap-2">
                            <svg
                                class="size-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3.75 6a2.25 2.25 0 0 1 2.25-2.25h12a2.25 2.25 0 0 1 2.25 2.25v1.5A2.25 2.25 0 0 1 18 9.75H6A2.25 2.25 0 0 1 3.75 7.5V6Zm0 7.5A2.25 2.25 0 0 1 6 11.25h12a2.25 2.25 0 0 1 2.25 2.25v1.5A2.25 2.25 0 0 1 18 17.25H6a2.25 2.25 0 0 1-2.25-2.25v-1.5Zm6.75 5.25a.75.75 0 0 1 .75-.75h2a.75.75 0 0 1 0 1.5h-2a.75.75 0 0 1-.75-.75Z"
                                />
                            </svg>
                            <span>{{ __('Cicil Emas') }}</span>
                        </span>
                        <svg
                            data-accordion-icon
                            class="size-4 transform transition-transform duration-300 {{ $isCicilEmasActive ? 'rotate-90' : '' }}"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                    <div
                        id="cicil-emas-menu"
                        class="ms-3 mt-1 space-y-1 overflow-hidden text-sm transition-all duration-300"
                        style="max-height: {{ $isCicilEmasActive ? '500px' : '0px' }};"
                    >
                        <a
                            href="{{ route('cicil-emas.transaksi-emas') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('cicil-emas.transaksi-emas'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('cicil-emas.transaksi-emas'),
                            ])
                        >
                            {{ __('Transaksi Emas') }}
                        </a>
                        <a
                            href="{{ route('cicil-emas.daftar-cicilan') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('cicil-emas.daftar-cicilan'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('cicil-emas.daftar-cicilan'),
                            ])
                        >
                            {{ __('Daftar Cicilan') }}
                        </a>
                        <a
                            href="{{ route('cicil-emas.angsuran-rutin') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('cicil-emas.angsuran-rutin'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('cicil-emas.angsuran-rutin'),
                            ])
                        >
                            {{ __('Angsuran Rutin') }}
                        </a>
                        <a
                            href="{{ route('cicil-emas.riwayat-cicilan') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('cicil-emas.riwayat-cicilan'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('cicil-emas.riwayat-cicilan'),
                            ])
                        >
                            {{ __('Riwayat Cicilan') }}
                        </a>
                        <a
                            href="{{ route('cicil-emas.pelunasan-cicilan') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('cicil-emas.pelunasan-cicilan'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('cicil-emas.pelunasan-cicilan'),
                            ])
                        >
                            {{ __('Pelunasan Cicilan') }}
                        </a>
                    </div>
                </div>

                <div>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-neutral-700 transition-colors duration-200 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:hover:text-white"
                        data-accordion-toggle
                        data-accordion-target="jual-emas-menu"
                        aria-expanded="{{ $isJualEmasActive ? 'true' : 'false' }}"
                    >
                        <span class="flex items-center gap-2">
                            <svg
                                class="size-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4.5 6H13.5M13.5 6 10.5 3m3 3-3 3M19.5 18H10.5m0 0 3 3m-3-3 3-3M4.5 12h15"
                                />
                            </svg>
                            <span>{{ __('Jual Emas') }}</span>
                        </span>
                        <svg
                            data-accordion-icon
                            class="size-4 transform transition-transform duration-300 {{ $isJualEmasActive ? 'rotate-90' : '' }}"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                    <div
                        id="jual-emas-menu"
                        class="ms-3 mt-1 space-y-1 overflow-hidden text-sm transition-all duration-300"
                        style="max-height: {{ $isJualEmasActive ? '500px' : '0px' }};"
                    >
                        <a
                            href="{{ route('jual-emas.transaksi-penjualan') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('jual-emas.transaksi-penjualan'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('jual-emas.transaksi-penjualan'),
                            ])
                        >
                            {{ __('Transaksi Penjualan') }}
                        </a>
                        <a
                            href="{{ route('jual-emas.lihat-penjualan') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('jual-emas.lihat-penjualan'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('jual-emas.lihat-penjualan'),
                            ])
                        >
                            {{ __('Lihat Penjualan') }}
                        </a>
                        <a
                            href="{{ route('jual-emas.batal-penjualan') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('jual-emas.batal-penjualan'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('jual-emas.batal-penjualan'),
                            ])
                        >
                            {{ __('Batal Penjualan') }}
                        </a>
                    </div>
                </div>

                <div>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-neutral-700 transition-colors duration-200 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:hover:text-white"
                        data-accordion-toggle
                        data-accordion-target="beli-emas-menu"
                        aria-expanded="{{ $isBeliEmasActive ? 'true' : 'false' }}"
                    >
                        <span class="flex items-center gap-2">
                            <svg
                                class="size-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M20.25 7.5l-8.954 8.955a1.125 1.125 0 0 1-1.591 0L3.75 10.5m16.5-3L15 3.75M20.25 7.5 15 12.75"
                                />
                            </svg>
                            <span>{{ __('Beli Emas') }}</span>
                        </span>
                        <svg
                            data-accordion-icon
                            class="size-4 transform transition-transform duration-300 {{ $isBeliEmasActive ? 'rotate-90' : '' }}"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                    <div
                        id="beli-emas-menu"
                        class="ms-3 mt-1 space-y-1 overflow-hidden text-sm transition-all duration-300"
                        style="max-height: {{ $isBeliEmasActive ? '500px' : '0px' }};"
                    >
                        <a
                            href="{{ route('beli-emas.transaksi-pembelian') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('beli-emas.transaksi-pembelian'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('beli-emas.transaksi-pembelian'),
                            ])
                        >
                            {{ __('Transaksi Pembelian') }}
                        </a>
                        <a
                            href="{{ route('beli-emas.lihat-pembelian') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('beli-emas.lihat-pembelian'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('beli-emas.lihat-pembelian'),
                            ])
                        >
                            {{ __('Lihat Pembelian') }}
                        </a>
                        <a
                            href="{{ route('beli-emas.batal-pembelian') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('beli-emas.batal-pembelian'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('beli-emas.batal-pembelian'),
                            ])
                        >
                            {{ __('Batal Pembelian') }}
                        </a>
                    </div>
                </div>

                <div>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-neutral-700 transition-colors duration-200 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:hover:text-white"
                        data-accordion-toggle
                        data-accordion-target="titip-emas-menu"
                        aria-expanded="{{ $isTitipEmasActive ? 'true' : 'false' }}"
                    >
                        <span class="flex items-center gap-2">
                            <svg
                                class="size-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M2.25 12a9.75 9.75 0 0 1 19.5 0v6a2.25 2.25 0 0 1-2.25 2.25H4.5A2.25 2.25 0 0 1 2.25 18V12Zm9-4.5h1.5a1.5 1.5 0 0 1 1.5 1.5v1.5h-4.5V9a1.5 1.5 0 0 1 1.5-1.5Zm-1.5 6h4.5V18h-4.5v-4.5Z"
                                />
                            </svg>
                            <span>{{ __('Titip Emas') }}</span>
                        </span>
                        <svg
                            data-accordion-icon
                            class="size-4 transform transition-transform duration-300 {{ $isTitipEmasActive ? 'rotate-90' : '' }}"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                    <div
                        id="titip-emas-menu"
                        class="ms-3 mt-1 space-y-1 overflow-hidden text-sm transition-all duration-300"
                        style="max-height: {{ $isTitipEmasActive ? '500px' : '0px' }};"
                    >
                        <a
                            href="{{ route('titip-emas.transaksi-titip-emas') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('titip-emas.transaksi-titip-emas'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('titip-emas.transaksi-titip-emas'),
                            ])
                        >
                            {{ __('Transaksi Titip Emas') }}
                        </a>
                        <a
                            href="{{ route('titip-emas.lihat-titipan') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('titip-emas.lihat-titipan'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('titip-emas.lihat-titipan'),
                            ])
                        >
                            {{ __('Lihat Titipan') }}
                        </a>
                    </div>
                </div>

                <div>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-neutral-700 transition-colors duration-200 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:hover:text-white"
                        data-accordion-toggle
                        data-accordion-target="nasabah-menu"
                        aria-expanded="{{ $isNasabahActive ? 'true' : 'false' }}"
                    >
                        <span class="flex items-center gap-2">
                            <svg
                                class="size-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.837 0-5.518-.652-7.499-1.632Z"
                                />
                            </svg>
                            <span>{{ __('Nasabah') }}</span>
                        </span>
                        <svg
                            data-accordion-icon
                            class="size-4 transform transition-transform duration-300 {{ $isNasabahActive ? 'rotate-90' : '' }}"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                    <div
                        id="nasabah-menu"
                        class="ms-3 mt-1 space-y-1 overflow-hidden text-sm transition-all duration-300"
                        style="max-height: {{ $isNasabahActive ? '500px' : '0px' }};"
                    >
                        <a
                            href="{{ route('nasabah.data-nasabah') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('nasabah.data-nasabah'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('nasabah.data-nasabah'),
                            ])
                        >
                            {{ __('Data Nasabah') }}
                        </a>
                                                <a
                            href="{{ route('nasabah.lihat-transaksi-nasabah') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('nasabah.lihat-transaksi-nasabah'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('nasabah.lihat-transaksi-nasabah'),
                            ])
                        >
                            {{ __('Lihat Transaksi Nasabah') }}
                        </a>
                        </a>
                                                <a
                            href="{{ route('nasabah.nasabah-baru') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('nasabah.lihat-transaksi-nasabah'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('nasabah.lihat-transaksi-nasabah'),
                            ])
                        >
                            {{ __('Data Nasabah Baru') }}
                        </a>
                        <a
                            href="{{ route('nasabah.cdd-nasabah') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('nasabah.cdd-nasabah'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('nasabah.cdd-nasabah'),
                            ])
                        >
                            {{ __('CDD Nasabah') }}
                        </a>
                    </div>
                </div>
<div>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-neutral-700 transition-colors duration-200 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:hover:text-white"
                        data-accordion-toggle
                        data-accordion-target="laporan-menu"
                        aria-expanded="{{ $isLaporanActive ? 'true' : 'false' }}"
                    >
                        <span class="flex items-center gap-2">
                            <svg
                                class="size-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3 4.5h18M3 9h18M9 13.5h12M9 18h12M3 13.5h3M3 18h3"
                                />
                            </svg>
                            <span>{{ __('Laporan') }}</span>
                        </span>
                        <svg
                            data-accordion-icon
                            class="size-4 transform transition-transform duration-300 {{ $isLaporanActive ? 'rotate-90' : '' }}"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                    <div
                        id="laporan-menu"
                        class="ms-3 mt-1 space-y-1 overflow-hidden text-sm transition-all duration-300"
                        style="max-height: {{ $isLaporanActive ? '500px' : '0px' }};"
                    >
                        <a
                            href="{{ route('laporan.saldo-kas') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('laporan.saldo-kas'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('laporan.saldo-kas'),
                            ])
                        >
                            {{ __('Laporan Saldo Kas') }}
                        </a>
                        <a
                            href="{{ route('laporan.transaksi-gadai') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('laporan.transaksi-gadai'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('laporan.transaksi-gadai'),
                            ])
                        >
                            {{ __('Laporan Transaksi Gadai') }}
                        </a>
                        <a
                            href="{{ route('laporan.pelunasan-gadai') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('laporan.pelunasan-gadai'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('laporan.pelunasan-gadai'),
                            ])
                        >
                            {{ __('Laporan Pelunasan Gadai') }}
                        </a>
                        <a
                            href="{{ route('laporan.batal-gadai') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('laporan.batal-gadai'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('laporan.batal-gadai'),
                            ])
                        >
                            {{ __('Laporan Pembatalan Gadai') }}
                        </a>
                        <a
                            href="{{ route('laporan.perpanjangan-gadai') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('laporan.perpanjangan-gadai'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('laporan.perpanjangan-gadai'),
                            ])
                        >
                            {{ __('Laporan Perpanjangan Gadai') }}
                        </a>
                        <a
                            href="{{ route('laporan.lelang') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('laporan.lelang'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('laporan.lelang'),
                            ])
                        >
                            {{ __('Laporan Lelang') }}
                        </a>
                        <a
                            href="{{ route('laporan.cicil-emas') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('laporan.cicil-emas'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('laporan.cicil-emas'),
                            ])
                        >
                            {{ __('Laporan Cicil Emas') }}
                        </a>
                    </div>
                </div>

                <div>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-neutral-700 transition-colors duration-200 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:hover:text-white"
                        data-accordion-toggle
                        data-accordion-target="akuntansi-menu"
                        aria-expanded="{{ $isAkuntansiActive ? 'true' : 'false' }}"
                    >
                        <span class="flex items-center gap-2">
                            <svg
                                class="size-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 3v18m-7.5-9h15M5.25 8.25h13.5m-13.5 7.5h13.5"
                                />
                            </svg>
                            <span>{{ __('Akuntansi') }}</span>
                        </span>
                        <svg
                            data-accordion-icon
                            class="size-4 transform transition-transform duration-300 {{ $isAkuntansiActive ? 'rotate-90' : '' }}"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                    <div
                        id="akuntansi-menu"
                        class="ms-3 mt-1 space-y-1 overflow-hidden text-sm transition-all duration-300"
                        style="max-height: {{ $isAkuntansiActive ? '500px' : '0px' }};"
                    >
                        <a
                            href="{{ route('akuntansi.jurnal') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('akuntansi.jurnal'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('akuntansi.jurnal'),
                            ])
                        >
                            {{ __('Jurnal') }}
                        </a>
                        <a
                            href="{{ route('akuntansi.buku-besar') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('akuntansi.buku-besar'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('akuntansi.buku-besar'),
                            ])
                        >
                            {{ __('Buku Besar') }}
                        </a>
                        <a
                            href="{{ route('akuntansi.neraca') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('akuntansi.neraca'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('akuntansi.neraca'),
                            ])
                        >
                            {{ __('Neraca') }}
                        </a>
                        <a
                            href="{{ route('akuntansi.laba-rugi') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('akuntansi.laba-rugi'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('akuntansi.laba-rugi'),
                            ])
                        >
                            {{ __('Laba Rugi') }}
                        </a>
                    </div>
                </div>
                @if (auth()->user()?->hasAdminAccess())
                    <div>
                        <button
                            type="button"
                            class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-neutral-700 transition-colors duration-200 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:hover:text-white"
                            data-accordion-toggle
                            data-accordion-target="master-menu"
                            aria-expanded="{{ $isMasterActive ? 'true' : 'false' }}"
                        >
                            <span class="flex items-center gap-2">
                                <svg
                                    class="size-5"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    aria-hidden="true"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 3h7.5v7.5H3V3Zm10.5 0H21v7.5h-7.5V3ZM3 13.5h7.5V21H3v-7.5Zm10.5 0H21V21h-7.5v-7.5Z"
                                    />
                                </svg>
                                <span>{{ __('Master') }}</span>
                            </span>
                            <svg
                                data-accordion-icon
                                class="size-4 transform transition-transform duration-300 {{ $isMasterActive ? 'rotate-90' : '' }}"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                        <div
                            id="master-menu"
                            class="ms-3 mt-1 space-y-1 overflow-hidden text-sm transition-all duration-300"
                            style="max-height: {{ $isMasterActive ? '500px' : '0px' }};"
                        >
                            <a
                                href="{{ route('admin.users.index') }}"
                                wire:navigate
                                @class([
                                    'block rounded-lg px-3 py-2 transition-colors duration-200',
                                    'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('admin.users.*'),
                                    'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('admin.users.*'),
                                ])
                            >
                                {{ __('Master User') }}
                            </a>
                            <a
                                href="{{ route('admin.pages.index') }}"
                                wire:navigate
                                @class([
                                    'block rounded-lg px-3 py-2 transition-colors duration-200',
                                    'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('admin.pages.*'),
                                    'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('admin.pages.*'),
                                ])
                            >
                                {{ __('Halaman Baru') }}
                            </a>
                        </div>
                    </div>
                @endif
            </nav>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        <script>
            const initializeAccordion = () => {
                document.querySelectorAll('[data-accordion-toggle]').forEach((toggle) => {
                    if (toggle.dataset.accordionInitialized === 'true') {
                        return;
                    }

                    toggle.dataset.accordionInitialized = 'true';

                    const targetId = toggle.getAttribute('data-accordion-target');
                    const target = document.getElementById(targetId);

                    if (!target) {
                        return;
                    }

                    const icon = toggle.querySelector('[data-accordion-icon]');
                    const isExpanded = toggle.getAttribute('aria-expanded') === 'true';

                    target.style.maxHeight = isExpanded ? `${target.scrollHeight}px` : '0px';

                    toggle.addEventListener('click', () => {
                        const currentlyExpanded = toggle.getAttribute('aria-expanded') === 'true';

                        if (currentlyExpanded) {
                            target.style.maxHeight = '0px';
                            toggle.setAttribute('aria-expanded', 'false');
                            icon?.classList.remove('rotate-90');
                        } else {
                            target.style.maxHeight = `${target.scrollHeight}px`;
                            toggle.setAttribute('aria-expanded', 'true');
                            icon?.classList.add('rotate-90');
                        }
                    });
                });
            };

            document.addEventListener('DOMContentLoaded', initializeAccordion);
            document.addEventListener('livewire:navigated', initializeAccordion);
        </script>

        @fluxScripts
    </body>
</html>
