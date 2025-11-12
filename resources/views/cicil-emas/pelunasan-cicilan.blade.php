<x-layouts.app :title="__('Pelunasan Cicilan')">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Pelunasan Cicilan') }}</h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                {{ __('Kelola proses penyelesaian cicilan emas, mulai dari validasi pelunasan hingga penyerahan emas fisik.') }}
            </p>
        </div>

        <section class="flex flex-col gap-3 rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <header class="flex flex-col gap-1">
                <span class="text-xs font-semibold uppercase tracking-wide text-rose-500">{{ __('Menu Pelunasan Cicilan') }}</span>
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Pelunasan & Penyerahan Emas') }}</h2>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Pastikan setiap cicilan yang lunas terverifikasi, diskon administrasi terhitung, dan jadwal penyerahan emas ditentukan.') }}
                </p>
            </header>
            <ul class="list-disc space-y-2 ps-5 text-sm text-neutral-700 dark:text-neutral-200">
                <li>{{ __('Validasi pelunasan otomatis setelah seluruh angsuran diterima atau saat nasabah melakukan pelunasan dipercepat.') }}</li>
                <li>{{ __('Hitung potensi diskon administrasi atau penyesuaian margin sesuai kebijakan perusahaan.') }}</li>
                <li>{{ __('Koordinasikan jadwal pengambilan atau pengiriman emas, termasuk integrasi dengan vendor logistik dan pencetakan sertifikat.') }}</li>
            </ul>
        </section>
    </div>
</x-layouts.app>
