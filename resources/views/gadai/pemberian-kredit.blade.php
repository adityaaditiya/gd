<x-layouts.app :title="__('Pemberian Kredit')">
    <div class="space-y-10">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Pemberian Kredit') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Kelola seluruh proses pemberian kredit gadai mulai dari pemilihan barang, penentuan pinjaman, hingga penerbitan kontrak.') }}
            </p>
        </div>

        <div class="space-y-4">
            <div class="flex flex-col gap-1">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Navigasi Cepat') }}</h2>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Tinjau kontrak yang ada atau lanjutkan dengan alur penerbitan kontrak baru.') }}
                </p>
            </div>
            <div class="grid gap-6 md:grid-cols-2">
                <a
                    href="{{ route('transaksi-gadai.index') }}"
                    wire:navigate
                    class="flex flex-col gap-3 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm transition hover:border-emerald-500 hover:shadow-md dark:border-neutral-700 dark:bg-neutral-800 dark:hover:border-emerald-400"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex size-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-300">
                            <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5M3.75 9h16.5m-16.5 4.5h10.5M3.75 18h10.5" />
                            </svg>
                        </div>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">{{ __('Langkah 1 & 2') }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Daftar Kontrak Gadai') }}</h3>
                    <p class="text-sm text-neutral-600 dark:text-neutral-300">
                        {{ __('Tinjau ringkasan kontrak gadai yang telah diterbitkan termasuk nilai pinjaman dan status terkini.') }}
                    </p>
                    <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-300">{{ __('Lihat Kontrak →') }}</span>
                </a>

                <a
                    href="{{ route('transaksi-gadai.create') }}"
                    wire:navigate
                    class="flex flex-col gap-3 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm transition hover:border-emerald-500 hover:shadow-md dark:border-neutral-700 dark:bg-neutral-800 dark:hover:border-emerald-400"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex size-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-300">
                            <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </div>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">{{ __('Langkah 3') }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Terbitkan Kontrak Baru') }}</h3>
                    <p class="text-sm text-neutral-600 dark:text-neutral-300">
                        {{ __('Mulai alur kritis: pilih barang siap gadai, tetapkan pinjaman maksimal 94% taksiran, dan simpan kontrak.') }}
                    </p>
                    <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-300">{{ __('Buat Kontrak →') }}</span>
                </a>
            </div>
        </div>

        <div class="space-y-4">
            <div class="flex flex-col gap-1">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Alur Penerbitan Kontrak Langsung') }}</h2>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Gunakan formulir berikut untuk menyelesaikan tiga langkah utama pemberian kredit tanpa meninggalkan menu gadai elektronik.') }}
                </p>
            </div>

            @include('transaksi-gadai.partials.three-step-form', [
                'barangSiapGadai' => $barangSiapGadai,
                'nasabahList' => $nasabahList,
            ])
        </div>
    </div>
</x-layouts.app>
