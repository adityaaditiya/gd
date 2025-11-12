<x-layouts.app :title="__('Angsuran Rutin')">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Angsuran Rutin') }}</h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                {{ __('Kelola proses penagihan bulanan, validasi pembayaran, dan hitung denda keterlambatan secara real-time.') }}
            </p>
        </div>

        <section class="flex flex-col gap-3 rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <header class="flex flex-col gap-1">
                <span class="text-xs font-semibold uppercase tracking-wide text-sky-500">{{ __('Menu Angsuran Rutin') }}</span>
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Proses Pembayaran Terjadwal') }}</h2>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Pastikan setiap jatuh tempo tercatat, pembayaran tervalidasi, dan denda dihitung otomatis bila terjadi keterlambatan.') }}
                </p>
            </header>
            <ul class="list-disc space-y-2 ps-5 text-sm text-neutral-700 dark:text-neutral-200">
                <li>{{ __('Sinkronkan instruksi bayar (transfer, VA, auto-debit) dan tandai status sukses, pending, atau gagal.') }}</li>
                <li>{{ __('Hitung denda berdasarkan kebijakan tenor; tampilkan rincian denda pada slip pembayaran dan dashboard nasabah.') }}</li>
                <li>{{ __('Sediakan alur eskalasi ke tim collection untuk cicilan yang menunggak melebihi ambang toleransi.') }}</li>
            </ul>
        </section>
    </div>
</x-layouts.app>
