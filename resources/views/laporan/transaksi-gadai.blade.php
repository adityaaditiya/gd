<x-layouts.app :title="__('Laporan Transaksi Gadai')">
    <div class="space-y-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Laporan Transaksi Gadai') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Ringkasan transaksi gadai yang sedang berjalan maupun telah selesai, tanpa termasuk transaksi batal.') }}
            </p>
        </div>

        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <form method="GET" action="{{ route('laporan.transaksi-gadai') }}" class="w-full max-w-md">
                <label for="search-transaksi-gadai" class="sr-only">{{ __('Cari transaksi') }}</label>
                <div class="flex items-center gap-2">
                    <div class="relative flex-1">
                        <input
                            id="search-transaksi-gadai"
                            name="search"
                            type="search"
                            value="{{ $search ?? '' }}"
                            placeholder="{{ __('Cari No. SBG atau nama nasabah…') }}"
                            class="w-full rounded-lg border border-neutral-200 bg-white py-2 px-3 text-sm text-neutral-700 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                        />
                    </div>
                    @if (!empty($search))
                        <a
                            href="{{ route('laporan.transaksi-gadai') }}"
                            class="inline-flex items-center rounded-lg border border-neutral-200 px-3 py-2 text-xs font-semibold text-neutral-600 transition hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-700/60"
                        >
                            {{ __('Reset') }}
                        </a>
                    @endif
                    <button
                        type="submit"
                        class="inline-flex items-center rounded-lg border border-emerald-600 bg-emerald-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-400 dark:bg-emerald-500 dark:hover:border-emerald-300 dark:hover:bg-emerald-400"
                    >
                        {{ __('Cari') }}
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <table class="min-w-full divide-y divide-neutral-200 text-left text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200">
                <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">{{ __('No. SBG') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Nasabah') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Barang Jaminan') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Pinjaman') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Status') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Tanggal') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Petugas') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                    @forelse ($transaksiGadai as $transaksi)
                        <tr class="align-top hover:bg-neutral-50 dark:hover:bg-neutral-700/70">
                            <td class="whitespace-nowrap px-4 py-3 font-semibold text-neutral-900 dark:text-white">
                                {{ $transaksi->no_sbg }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="font-medium text-neutral-900 dark:text-white">{{ $transaksi->nasabah?->nama ?? '—' }}</span>
                                    <span class="text-xs text-neutral-500 dark:text-neutral-300">
                                        {{ $transaksi->nasabah?->kode_member ? __('Kode: :kode', ['kode' => $transaksi->nasabah->kode_member]) : '—' }}
                                    </span>
                                    @if (!empty($transaksi->nasabah?->telepon))
                                        <span class="text-xs text-neutral-500 dark:text-neutral-300">{{ $transaksi->nasabah->telepon }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if ($transaksi->barangJaminan->isEmpty())
                                    <span class="text-xs text-neutral-500 dark:text-neutral-300">{{ __('Tidak ada data barang.') }}</span>
                                @else
                                    <ul class="space-y-1">
                                        @foreach ($transaksi->barangJaminan as $barang)
                                            <li class="rounded-lg bg-neutral-50 px-3 py-2 text-xs text-neutral-700 dark:bg-neutral-900 dark:text-neutral-200">
                                                <div class="font-semibold text-neutral-900 dark:text-white">{{ $barang->jenis_barang }} — {{ $barang->merek }}</div>
                                                <div>Rp {{ number_format((float) $barang->nilai_taksiran, 0, ',', '.') }}</div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-emerald-600 dark:text-emerald-300">Rp {{ number_format((float) $transaksi->uang_pinjaman, 0, ',', '.') }}</div>
                                @if ((float) $transaksi->biaya_admin > 0)
                                    <div class="text-xs text-neutral-500 dark:text-neutral-300">{{ __('Biaya admin: Rp :amount', ['amount' => number_format((float) $transaksi->biaya_admin, 0, ',', '.')]) }}</div>
                                @endif
                                @if ((float) $transaksi->premi > 0)
                                    <div class="text-xs text-neutral-500 dark:text-neutral-300">{{ __('Premi: Rp :amount', ['amount' => number_format((float) $transaksi->premi, 0, ',', '.')]) }}</div>
                                @endif
                                <div class="text-xs text-neutral-500 dark:text-neutral-300">
                                    @if ($transaksi->tenor_hari)
                                        {{ __('Tenor: :days hari', ['days' => $transaksi->tenor_hari]) }}
                                    @else
                                        {{ __('Tenor: —') }}
                                    @endif
                                </div>
                                @if ((float) $transaksi->tarif_bunga_harian > 0)
                                    <div class="text-xs text-neutral-500 dark:text-neutral-300">
                                        {{ __('Tarif bunga harian: :rate%', ['rate' => rtrim(rtrim(number_format((float) $transaksi->tarif_bunga_harian, 2, '.', ''), '0'), '.')]) }}
                                    </div>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">
                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">
                                    {{ $transaksi->status_transaksi ?? __('Belum ada status') }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-xs text-neutral-600 dark:text-neutral-300">
                                <div>{{ __('Gadai: :date', ['date' => optional($transaksi->tanggal_gadai)->format('d M Y') ?? '—']) }}</div>
                                <div>{{ __('Jatuh tempo: :date', ['date' => optional($transaksi->jatuh_tempo_awal)->format('d M Y') ?? '—']) }}</div>
                            </td>
                            <td class="px-4 py-3 text-xs text-neutral-600 dark:text-neutral-300">
                                <div>{{ __('Kasir: :name', ['name' => $transaksi->kasir?->name ?? '—']) }}</div>
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
