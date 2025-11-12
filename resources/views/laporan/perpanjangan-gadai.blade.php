<x-layouts.app :title="__('Laporan Perpanjangan Gadai')">
    <div class="space-y-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Laporan Perpanjangan Gadai') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Pantau seluruh perpanjangan kontrak gadai termasuk tenor baru dan biaya yang diterima kas.') }}
            </p>
        </div>

        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <form method="GET" action="{{ route('laporan.perpanjangan-gadai') }}" class="grid w-full gap-3 sm:grid-cols-2 lg:max-w-3xl lg:grid-cols-4">
                <div class="sm:col-span-2">
                    <label for="search-perpanjangan" class="sr-only">{{ __('Cari No. SBG atau nama nasabah') }}</label>
                    <div class="relative">
                        <!-- <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-neutral-400 dark:text-neutral-500">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 105.5 5.5a7.5 7.5 0 0011.15 11.15z" />
                            </svg>
                        </span> -->
                        <input
                            id="search-perpanjangan"
                            name="search"
                            type="search"
                            value="{{ $search ?? '' }}"
                            placeholder="{{ __('    Cari No. SBG atau nama nasabah…') }}"
                            class="w-full rounded-lg border border-neutral-200 bg-white py-2 pl-10 pr-3 text-sm text-neutral-700 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                        />
                    </div>
                </div>
                <div>
                    <label for="tanggal-dari" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Tanggal dari') }}</label>
                    <input
                        id="tanggal-dari"
                        name="tanggal_dari"
                        type="date"
                        value="{{ $tanggalDari ?? '' }}"
                        class="w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                    />
                </div>
                <div>
                    <label for="tanggal-sampai" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Tanggal sampai') }}</label>
                    <input
                        id="tanggal-sampai"
                        name="tanggal_sampai"
                        type="date"
                        value="{{ $tanggalSampai ?? '' }}"
                        class="w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                    />
                </div>
                
                <div class="flex items-center gap-2 sm:col-span-2 lg:col-span-4">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-blue-600 shadow-sm transition hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:bg-emerald-500 dark:hover:bg-emerald-400"
                    >
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 9.75L8.25 12l3 2.25M12.75 9.75l3 2.25-3 2.25" />
                        </svg>
                        <span>{{ __('Terapkan Filter') }}</span>
                    </button>
                    @if (!empty($search) || !empty($tanggalDari) || !empty($tanggalSampai))
                        <a
                            href="{{ route('laporan.perpanjangan-gadai') }}"
                            class="inline-flex items-center gap-2 rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-400 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800/70"
                        >
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                            <span>{{ __('Reset') }}</span>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <table class="min-w-full divide-y divide-neutral-200 text-left text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200">
                <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">{{ __('Tanggal Perpanjangan') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('No. SBG & Nasabah') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Tenor') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Biaya Perpanjangan') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Jadwal Baru') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Petugas') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                    @forelse ($riwayat as $item)
                        @php
                            $isCancelled = $item->dibatalkan_pada !== null;
                        @endphp
                        <tr @class([
                            'align-top hover:bg-neutral-50 dark:hover:bg-neutral-700/70',
                            'bg-red-50/80 dark:bg-red-500/10' => $isCancelled,
                        ])>
                            <td class="whitespace-nowrap px-4 py-3 text-xs text-neutral-600 dark:text-neutral-300">
                                <div class="font-semibold text-neutral-900 dark:text-white">{{ optional($item->tanggal_perpanjangan)->format('d M Y H:i') ?? '—' }}</div>
                                <div>{{ __('Dicatat pada: :date', ['date' => $item->created_at?->format('d M Y H:i') ?? '—']) }}</div>
                                <div class="mt-2 flex flex-wrap items-center gap-2 text-[11px]">
                                    <span @class([
                                        'inline-flex items-center rounded-full px-2 py-0.5 font-semibold uppercase tracking-wide',
                                        'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200' => !$isCancelled,
                                        'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-200' => $isCancelled,
                                    ])>
                                        {{ $isCancelled ? __('Dibatalkan') : __('Aktif') }}
                                    </span>
                                    @if ($isCancelled)
                                        <span class="text-red-700 dark:text-red-200">
                                            {{ __('Dibatalkan :date oleh :user', [
                                                'date' => optional($item->dibatalkan_pada)->format('d M Y H:i') ?? '—',
                                                'user' => $item->pembatal?->name ?? __('Tidak diketahui'),
                                            ]) }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-neutral-900 dark:text-white">{{ $item->transaksi?->no_sbg ?? '—' }}</div>
                                <div class="text-xs text-neutral-500 dark:text-neutral-300">{{ $item->transaksi?->nasabah?->nama ?? '—' }}</div>
                                <div class="text-xs text-neutral-500 dark:text-neutral-300">{{ __('Pokok: Rp :amount', ['amount' => number_format((float) $item->pokok_pinjaman, 0, ',', '.')]) }}</div>
                            </td>
                            <td class="px-4 py-3 text-xs text-neutral-600 dark:text-neutral-300">
                                <div>{{ __('Sebelumnya: :days hari', ['days' => $item->tenor_sebelumnya]) }}</div>
                                <div>{{ __('Baru: :days hari', ['days' => $item->tenor_baru]) }}</div>
                            </td>
                            <td class="px-4 py-3 text-xs text-neutral-600 dark:text-neutral-300">
                                <div class="font-semibold text-neutral-900 dark:text-white">Rp {{ number_format((float) $item->total_bayar, 0, ',', '.') }}</div>
                                <dl class="mt-2 space-y-1">
                                    <div class="flex justify-between gap-3">
                                        <dt>{{ __('Bunga') }}</dt>
                                        <dd>Rp {{ number_format((float) $item->bunga_dibayar, 0, ',', '.') }}</dd>
                                    </div>
                                    @if ((float) $item->biaya_admin > 0)
                                        <div class="flex justify-between gap-3">
                                            <dt>{{ __('Admin') }}</dt>
                                            <dd>Rp {{ number_format((float) $item->biaya_admin, 0, ',', '.') }}</dd>
                                        </div>
                                    @endif
                                    @if ((float) $item->biaya_titip > 0)
                                        <div class="flex justify-between gap-3">
                                            <dt>{{ __('Titip') }}</dt>
                                            <dd>Rp {{ number_format((float) $item->biaya_titip, 0, ',', '.') }}</dd>
                                        </div>
                                    @endif
                                </dl>
                                @if (!empty($item->catatan))
                                    <div class="mt-2 rounded-lg bg-neutral-50 px-3 py-2 text-[11px] text-neutral-600 dark:bg-neutral-900 dark:text-neutral-300">
                                        {{ $item->catatan }}
                                    </div>
                                @endif
                                @if ($isCancelled)
                                    <div class="mt-3 rounded-lg bg-red-100 px-3 py-2 text-[11px] text-red-700 dark:bg-red-500/20 dark:text-red-100">
                                        <p>
                                            {{ __('Mutasi kas dibatalkan pada :date oleh :user.', [
                                                'date' => optional($item->dibatalkan_pada)->format('d M Y H:i') ?? '—',
                                                'user' => $item->pembatal?->name ?? __('Tidak diketahui'),
                                            ]) }}
                                        </p>
                                        @if (!empty($item->alasan_pembatalan))
                                            <p class="mt-1 italic">“{{ $item->alasan_pembatalan }}”</p>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-xs text-neutral-600 dark:text-neutral-300">
                                <div>{{ __('Mulai: :date', ['date' => optional($item->tanggal_mulai_baru)->format('d M Y') ?? '—']) }}</div>
                                <div>{{ __('Jatuh tempo: :date', ['date' => optional($item->tanggal_jatuh_tempo_baru)->format('d M Y') ?? '—']) }}</div>
                            </td>
                            <td class="px-4 py-3 text-xs text-neutral-600 dark:text-neutral-300">
                                <div class="font-medium text-neutral-900 dark:text-white">{{ $item->petugas?->name ?? '—' }}</div>
                                <div>{{ __('Cabang/Kasir awal: :name', ['name' => $item->transaksi?->kasir?->name ?? '—']) }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-300">
                                {{ __('Belum ada perpanjangan yang tercatat.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $riwayat->links() }}
        </div>
    </div>
</x-layouts.app>
