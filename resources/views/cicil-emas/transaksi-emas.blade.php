<x-layouts.app :title="__('Transaksi Emas')">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Transaksi Emas') }}</h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                {{ __('Rancang dan jalankan alur awal cicil emas mulai dari simulasi paket hingga pencatatan pembayaran uang muka.') }}
            </p>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <section class="flex flex-col gap-3 rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <header class="flex flex-col gap-1">
                    <span class="text-xs font-semibold uppercase tracking-wide text-indigo-500">{{ __('Menu Transaksi Cicilan') }}</span>
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Simulasi & Pemilihan Paket') }}</h2>
                    <p class="text-sm text-neutral-600 dark:text-neutral-300">
                        {{ __('Gunakan kalkulator cicilan untuk menentukan kombinasi paket emas terbaik bagi nasabah sebelum pengajuan dibuat.') }}
                    </p>
                </header>
                <ul class="list-disc space-y-2 ps-5 text-sm text-neutral-700 dark:text-neutral-200">
                    <li>{{ __('Tampilkan estimasi uang muka, tenor, serta nominal angsuran berdasarkan berat dan kadar emas yang dipilih.') }}</li>
                    <li>{{ __('Sediakan daftar paket yang telah dikurasi; sistem menghitung ulang DP–tenor sesuai profil risiko nasabah.') }}</li>
                    <li>{{ __('Pilih nasabah dari basis data nasabah yang telah lulus verifikasi untuk memastikan keterhubungan data KYC.') }}</li>
                </ul>
            </section>

            <section class="flex flex-col gap-3 rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <header class="flex flex-col gap-1">
                    <span class="text-xs font-semibold uppercase tracking-wide text-amber-500">{{ __('Menu Transaksi Cicilan') }}</span>
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Pembayaran Uang Muka') }}</h2>
                    <p class="text-sm text-neutral-600 dark:text-neutral-300">
                        {{ __('Catat pembayaran down payment dan kunci harga emas sebagai dasar jadwal cicilan berikutnya.') }}
                    </p>
                </header>
                <ul class="list-disc space-y-2 ps-5 text-sm text-neutral-700 dark:text-neutral-200">
                    <li>{{ __('Dukung opsi pembayaran tunai maupun transfer, termasuk pembuatan bukti pembayaran otomatis.') }}</li>
                    <li>{{ __('Setelah DP terkonfirmasi, sistem menerbitkan perjanjian pembiayaan digital dan mengunci harga emas pada waktu transaksi.') }}</li>
                    <li>{{ __('Kunci status transaksi sebagai referensi awal untuk penjadwalan angsuran dan perhitungan stok emas.') }}</li>
                </ul>
            </section>
        </div>
    </div>
</x-layouts.app>
