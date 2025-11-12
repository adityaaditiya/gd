<x-layouts.app :title="__('Daftar Cicilan')">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Daftar Cicilan') }}</h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                {{ __('Kelola jadwal cicilan emas aktif, lengkap dengan rincian jatuh tempo dan ketentuan denda.') }}
            </p>
        </div>

        <section class="flex flex-col gap-3 rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <header class="flex flex-col gap-1">
                <span class="text-xs font-semibold uppercase tracking-wide text-emerald-500">{{ __('Menu Daftar Cicilan') }}</span>
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Penjadwalan Angsuran Otomatis') }}</h2>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Setiap transaksi cicilan yang disetujui menghasilkan jadwal angsuran terstruktur sebagai panduan penagihan.') }}
                </p>
            </header>
            <ul class="list-disc space-y-2 ps-5 text-sm text-neutral-700 dark:text-neutral-200">
                <li>{{ __('Sistem men-generate daftar angsuran lengkap dengan tanggal jatuh tempo, nominal pokok, margin, dan denda keterlambatan.') }}</li>
                <li>{{ __('Tersedia filter status untuk memantau cicilan yang mendekati jatuh tempo atau telah menunggak.') }}</li>
                <li>{{ __('Integrasikan daftar ini dengan modul notifikasi agar pengingat otomatis terkirim ke nasabah.') }}</li>
            </ul>
        </section>
    </div>
</x-layouts.app>
