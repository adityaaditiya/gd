<x-layouts.app :title="__('Daftar Kontrak Gadai')">
    <div class="space-y-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div class="flex flex-col gap-2">
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Daftar Kontrak Gadai') }}</h1>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Pantau seluruh kontrak gadai yang telah diterbitkan lengkap dengan status terkini.') }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('transaksi-gadai.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
                >
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>{{ __('Tambah Kontrak') }}</span>
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700 dark:border-emerald-500/60 dark:bg-emerald-500/10 dark:text-emerald-300">
                {{ session('status') }}
            </div>
        @endif

        <div class="overflow-x-auto rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <table class="min-w-full divide-y divide-neutral-200 text-left text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200">
                <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                    <tr>
                        <th class="px-4 py-3 font-semibold">{{ __('No. SBG') }}</th>
                        <th class="px-4 py-3 font-semibold">{{ __('Nasabah') }}</th>
                        <th class="px-4 py-3 font-semibold">{{ __('Uang Pinjaman') }}</th>
                        <th class="px-4 py-3 font-semibold">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                    @forelse ($transaksiList as $transaksi)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-neutral-900 dark:text-white">{{ $transaksi->no_sbg }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="font-medium text-neutral-900 dark:text-white">{{ $transaksi->nasabah?->nama ?? __('Nasabah Tidak Diketahui') }}</span>
                                    @if ($transaksi->nasabah?->kode_member)
                                        <span class="text-xs text-neutral-500 dark:text-neutral-300">{{ $transaksi->nasabah->kode_member }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 font-semibold text-emerald-600 dark:text-emerald-300">Rp {{ number_format((float) $transaksi->uang_pinjaman, 2, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full border border-emerald-300 bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-700 dark:border-emerald-500/60 dark:bg-emerald-500/10 dark:text-emerald-300">
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

        {{ $transaksiList->links() }}
    </div>
</x-layouts.app>
