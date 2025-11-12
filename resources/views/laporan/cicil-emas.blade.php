<x-layouts.app :title="__('Laporan Cicil Emas')">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Laporan Cicil Emas') }}</h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                {{ __('Susun laporan kepatuhan dan audit untuk seluruh portofolio cicil emas dalam satu tempat.') }}
            </p>
        </div>

        <section class="flex flex-col gap-3 rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <header class="flex flex-col gap-1">
                <span class="text-xs font-semibold uppercase tracking-wide text-fuchsia-500">{{ __('Menu Laporan') }}</span>
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Pelaporan Operasional & Kepatuhan') }}</h2>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Siapkan ringkasan performa cicilan, eksposur risiko, dan detail tunggakan untuk kebutuhan audit internal maupun regulator.') }}
                </p>
            </header>
            <ul class="list-disc space-y-2 ps-5 text-sm text-neutral-700 dark:text-neutral-200">
                <li>{{ __('Tampilkan metrik utama seperti outstanding principal, rasio tunggakan, serta komposisi tenor.') }}</li>
                <li>{{ __('Sediakan ekspor data (PDF/Excel) untuk laporan bulanan, termasuk jejak audit setiap penyesuaian jadwal.') }}</li>
                <li>{{ __('Integrasikan parameter pencarian (periode, status, cabang) agar tim operasional mudah melakukan rekonsiliasi.') }}</li>
            </ul>
        </section>
    </div>
</x-layouts.app>
