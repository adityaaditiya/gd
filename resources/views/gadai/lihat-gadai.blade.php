<x-layouts.app :title="__('Lihat Gadai')">
    <div class="space-y-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Data Transaksi Gadai') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Pantau seluruh kontrak gadai yang aktif lengkap dengan detail nasabah, barang jaminan, dan estimasi bunga harian.') }}
            </p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm dark:border-emerald-500/60 dark:bg-emerald-500/10 dark:text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-4">
                <div class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Total :count transaksi.', ['count' => number_format($transaksiGadai->total(), 0, ',', '.')]) }}
                </div>
                <form method="GET" action="{{ route('gadai.lihat-gadai') }}" class="relative">
                    <label for="search-no-sbg" class="sr-only">{{ __('Cari No. SBG') }}</label>
                    <div class="flex items-center gap-2">
                        <div class="relative flex-1">
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-neutral-400 dark:text-neutral-500">
                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 105.5 5.5a7.5 7.5 0 0011.15 11.15z" />
                                </svg>
                            </span>
                            <input
                                id="search-no-sbg"
                                name="search"
                                type="search"
                                value="{{ $search ?? '' }}"
                                placeholder="{{ __('Cari No. SBG…') }}"
                                class="w-full rounded-lg border border-neutral-200 bg-white py-2 pl-9 pr-3 text-sm text-neutral-700 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                            />
                        </div>
                        @if (!empty($search))
                            <a
                                href="{{ route('gadai.lihat-gadai') }}"
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
            <a
                href="{{ route('gadai.pemberian-kredit') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
            >
                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>{{ __('Tambah Transaksi Gadai') }}</span>
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <table class="min-w-full divide-y divide-neutral-200 text-left text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200">
                <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">{{ __('No. SBG') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Nasabah') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Barang Jaminan') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Pinjaman') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Tenor') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Bunga Terakumulasi') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Tarif Bunga Harian') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Jatuh Tempo') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Kasir') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Status') }}</th>
                        <th scope="col" class="px-4 py-3 text-center">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                    @forelse ($transaksiGadai as $transaksi)
                        <tr class="align-top hover:bg-neutral-50 dark:hover:bg-neutral-700/70">
                            <td class="whitespace-nowrap px-4 py-3 font-semibold text-neutral-900 dark:text-white">
                                {{ $transaksi->no_sbg }}
                                <div class="text-xs font-normal text-neutral-500 dark:text-neutral-300">
                                    {{ __('Tanggal Gadai: :date', ['date' => optional($transaksi->tanggal_gadai)->format('d M Y') ?? '—']) }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="font-medium text-neutral-900 dark:text-white">{{ $transaksi->nasabah?->nama ?? '—' }}</span>
                                    <span class="text-xs text-neutral-500 dark:text-neutral-300">{{ $transaksi->nasabah?->kode_member ?? '' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if ($transaksi->barangJaminan->isEmpty())
                                    <span class="text-xs text-neutral-500 dark:text-neutral-300">{{ __('Belum ada barang terhubung') }}</span>
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
                            <td class="whitespace-nowrap px-4 py-3">
                                <div class="font-semibold text-emerald-600 dark:text-emerald-300">Rp {{ number_format((float) $transaksi->uang_pinjaman, 0, ',', '.') }}</div>
                                @if ((float) $transaksi->biaya_admin > 0)
                                    <div class="text-xs text-neutral-500 dark:text-neutral-300">{{ __('Biaya admin: Rp :amount', ['amount' => number_format((float) $transaksi->biaya_admin, 0, ',', '.')]) }}</div>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">{{ $transaksi->tenor_hari ? $transaksi->tenor_hari . ' ' . __('hari') : '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-3">Rp {{ number_format((float) $transaksi->total_bunga, 0, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-4 py-3">{{ number_format((float) $transaksi->tarif_bunga_harian * 100, 2, ',', '.') }}%</td>
                            <td class="whitespace-nowrap px-4 py-3">{{ optional($transaksi->jatuh_tempo_awal)->format('d M Y') ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col text-xs text-neutral-600 dark:text-neutral-300">
                                    <span>{{ __('Kasir:') }} {{ $transaksi->kasir?->name ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-neutral-700 dark:bg-neutral-700/60 dark:text-neutral-100">
                                    {{ __($transaksi->status_transaksi ?? 'Tidak diketahui') }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center">
                                    @if ($transaksi->status_transaksi === 'Lunas')
                                        <a
                                            href="{{ route('laporan.pelunasan-gadai', ['search' => $transaksi->no_sbg]) }}"
                                            class="inline-flex items-center gap-2 rounded-lg border border-emerald-600 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 transition hover:border-emerald-700 hover:bg-emerald-600 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-400 dark:bg-emerald-500/10 dark:text-emerald-200 dark:hover:bg-emerald-400 dark:hover:text-neutral-900"
                                        >
                                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 13.5l3 3 3-3m-3 3v-9" />
                                            </svg>
                                            <span>{{ __('Lihat Pelunasan') }}</span>
                                        </a>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold text-neutral-500 dark:bg-neutral-700/60 dark:text-neutral-200">
                                            {{ __('Belum Lunas') }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-300">
                                {{ __('Belum ada transaksi gadai yang tersimpan.') }}
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
