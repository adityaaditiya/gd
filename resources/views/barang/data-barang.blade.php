<x-layouts.app :title="__('Data Barang')">
    <div class="space-y-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Data Barang') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Kelola daftar barang emas yang ditawarkan dalam paket cicilan termasuk pabrikan, berat, kadar, dan kombinasi DP–tenor yang tersedia.') }}
            </p>
        </div>

        <div class="rounded-xl border border-dashed border-neutral-300 bg-white p-6 text-center text-neutral-600 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">
            <p class="text-sm">
                {{ __('Belum ada modul manajemen barang. Tambahkan CRUD untuk pabrikan emas, berat, dan opsi cicilan guna mendukung simulasi transaksi cicilan emas.') }}
            </p>
        </div>
    </div>
</x-layouts.app>
