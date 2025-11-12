<x-layouts.app :title="__('Laporan Saldo Kas')">
    @php
        $formatCurrency = fn($value) => 'Rp ' . number_format($value, 2, ',', '.');
    @endphp

    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Laporan Saldo Kas') }}</h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                {{ __('Pantau pergerakan kas masuk dan kas keluar dari transaksi gadai terbaru.') }}
            </p>
        </div>

        <form method="get" class="grid gap-4 sm:grid-cols-[1fr_auto] items-end">
            <label class="flex flex-col gap-2">
                <span class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Pencarian') }}</span>
                <input
                    type="text"
                    name="search"
                    value="{{ old('search', $search) }}"
                    placeholder="{{ __('Cari No. SBG, Nasabah, atau Kasir') }}"
                    class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white"
                />
            </label>
            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-900"
            >
                {{ __('Terapkan') }}
            </button>
        </form>

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <p class="text-sm text-neutral-500 dark:text-neutral-300">{{ __('Total Kas Keluar') }}</p>
                <p class="mt-2 text-2xl font-semibold text-neutral-900 dark:text-white">{{ $formatCurrency($totalKasKeluar) }}</p>
            </div>
            <div class="rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <p class="text-sm text-neutral-500 dark:text-neutral-300">{{ __('Total Kas Masuk') }}</p>
                <p class="mt-2 text-2xl font-semibold text-neutral-900 dark:text-white">{{ $formatCurrency($totalKasMasuk) }}</p>
            </div>
            <div class="rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <p class="text-sm text-neutral-500 dark:text-neutral-300">{{ __('Saldo Kas Bersih') }}</p>
                <p class="mt-2 text-2xl font-semibold text-neutral-900 dark:text-white">{{ $formatCurrency($totalKasMasuk - $totalKasKeluar) }}</p>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead>
                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-300">
                        <th class="px-4 py-3">{{ __('Tanggal Gadai') }}</th>
                        <th class="px-4 py-3">{{ __('No. SBG') }}</th>
                        <th class="px-4 py-3">{{ __('Nasabah') }}</th>
                        <th class="px-4 py-3">{{ __('Kasir') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('Kas Keluar') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('Kas Masuk') }}</th>
                        <th class="px-4 py-3">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200">
                    @forelse ($transaksiGadai as $transaksi)
                        <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/60">
                            <td class="px-4 py-3 align-top">
                                {{ optional($transaksi->tanggal_gadai)->translatedFormat('d F Y') ?? '—' }}
                            </td>
                            <td class="px-4 py-3 align-top font-medium text-neutral-900 dark:text-white">
                                {{ $transaksi->no_sbg }}
                            </td>
                            <td class="px-4 py-3 align-top">
                                <div class="flex flex-col">
                                    <span class="font-medium text-neutral-900 dark:text-white">{{ $transaksi->nasabah->nama ?? '—' }}</span>
                                    @if ($transaksi->nasabah?->kode_member)
                                        <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Kode:') }} {{ $transaksi->nasabah->kode_member }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 align-top">
                                {{ $transaksi->kasir->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3 align-top text-right">
                                {{ $formatCurrency((float) $transaksi->uang_pinjaman) }}
                            </td>
                            <td class="px-4 py-3 align-top text-right">
                                @if ($transaksi->total_pelunasan)
                                    {{ $formatCurrency((float) $transaksi->total_pelunasan) }}
                                    @if ($transaksi->tanggal_pelunasan)
                                        <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                            {{ __('Pelunasan:') }} {{ optional($transaksi->tanggal_pelunasan)->translatedFormat('d F Y H:i') }}
                                        </div>
                                    @endif
                                @else
                                    <span class="text-neutral-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-top">
                                <span class="inline-flex rounded-full bg-neutral-200 px-2 py-1 text-xs font-medium text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                                    {{ $transaksi->status_transaksi ?? __('Tidak Diketahui') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-300">
                                {{ __('Belum ada transaksi gadai yang tercatat.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $transaksiGadai->links() }}
        </div>
    </div>
</x-layouts.app>
