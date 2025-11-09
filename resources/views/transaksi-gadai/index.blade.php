<x-layouts.app :title="__('Daftar Kontrak Gadai')">
    <div class="space-y-8" id="transaksi-gadai-index">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Daftar Kontrak Gadai') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Pantau seluruh kontrak gadai aktif beserta ringkasan nasabah dan nominal pinjaman.') }}
            </p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-700 dark:border-emerald-500/60 dark:bg-emerald-500/10 dark:text-emerald-300">
                <p class="font-semibold text-black dark:text-white">{{ session('status') }}</p>
            </div>
        @endif

        <div class="flex flex-col gap-4 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-1">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Ringkasan Kontrak') }}</h2>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Tersedia :count kontrak tercatat.', ['count' => $transaksiList->count()]) }}
                </p>
            </div>

            <a
                href="{{ route('transaksi-gadai.create') }}"
                wire:navigate
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
            >
                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>{{ __('Buat Kontrak Baru') }}</span>
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <table class="min-w-full divide-y divide-neutral-200 text-left text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200">
                <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">{{ __('Nomor Kontrak') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Nasabah') }}</th>
                        <th scope="col" class="px-4 py-3 text-right">{{ __('Uang Pinjaman') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse ($transaksiList as $transaksi)
                        <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/40">
                            <td class="whitespace-nowrap px-4 py-3 font-semibold text-neutral-900 dark:text-white">{{ $transaksi->no_sbg }}</td>
                            <td class="px-4 py-3">{{ $transaksi->nasabah?->nama ?? __('Nasabah Tidak Diketahui') }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-right font-semibold text-neutral-900 dark:text-white">
                                {{ number_format((float) $transaksi->uang_pinjaman, 2, ',', '.') }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-400/20 dark:text-emerald-300">
                                    {{ $transaksi->status_transaksi }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-300">
                                {{ __('Belum ada kontrak gadai yang tercatat.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
