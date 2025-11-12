<x-layouts.app :title="__('Riwayat Cicilan')">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Riwayat Cicilan') }}</h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                {{ __('Pantau histori pembayaran, saldo pokok tersisa, dan performa portofolio cicilan emas secara menyeluruh.') }}
            </p>
        </div>

        <section class="flex flex-col gap-3 rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <header class="flex flex-col gap-1">
                <span class="text-xs font-semibold uppercase tracking-wide text-purple-500">{{ __('Menu Riwayat Cicilan') }}</span>
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Monitoring Portofolio Cicil Emas') }}</h2>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Berikan visibilitas penuh terhadap status cicilan, termasuk saldo pokok, margin, dan nilai emas terkini.') }}
                </p>
            </header>
            <ul class="list-disc space-y-2 ps-5 text-sm text-neutral-700 dark:text-neutral-200">
                <li>{{ __('Tampilkan ringkasan status (aktif, lunas, menunggak) berikut indikator warna untuk memudahkan evaluasi cepat.') }}</li>
                <li>{{ __('Sediakan timeline pembayaran dengan bukti transaksi dan informasi metode bayar yang digunakan.') }}</li>
                <li>{{ __('Integrasikan data harga emas terbaru untuk menampilkan nilai aset terkini dan estimasi keuntungan nasabah.') }}</li>
            </ul>
        </section>
    </div>
</x-layouts.app>
