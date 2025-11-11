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
            <table class="min-w-full divide-y divide-neutral-200 text-left text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200" data-report-transaksi-table>
                <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">{{ __('No. SBG') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Nasabah') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Barang Jaminan') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Pinjaman') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Bunga Maksimum') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Bunga Terhutang Riil') }}</th>
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
                                    @php
                                        $barangCount = $transaksi->barangJaminan->count();
                                        $firstBarang = $transaksi->barangJaminan->first();
                                        $additionalBarang = max($barangCount - 1, 0);
                                        $collateralTemplateId = 'laporan-collateral-' . $transaksi->transaksi_id;
                                    @endphp
                                    <div class="flex flex-col gap-2">
                                        <span class="text-sm font-semibold text-neutral-900 dark:text-white">
                                            {{ __(':count Barang Jaminan', ['count' => $barangCount]) }}
                                        </span>
                                        <span class="text-xs text-neutral-500 dark:text-neutral-300">
                                            {{ $firstBarang?->jenis_barang ?? '—' }}@if ($firstBarang?->merek)
                                                · {{ $firstBarang->merek }}
                                            @endif
                                            @if ($additionalBarang > 0)
                                                <span class="text-neutral-400 dark:text-neutral-400">{{ __('dan :count lainnya', ['count' => $additionalBarang]) }}</span>
                                            @endif
                                        </span>
                                        <div class="flex flex-wrap gap-2">
                                            <button
                                                type="button"
                                                class="inline-flex items-center gap-2 rounded-lg border border-emerald-600 px-3 py-2 text-xs font-semibold text-emerald-600 transition hover:border-emerald-700 hover:bg-emerald-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-400 dark:text-emerald-300 dark:hover:border-emerald-300 dark:hover:bg-emerald-500/10"
                                                data-open-report-collateral
                                                data-target="{{ $collateralTemplateId }}"
                                                aria-haspopup="dialog"
                                                aria-controls="laporan-collateral-panel"
                                            >
                                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v-1.5a3 3 0 0 0-3-3h-6a3 3 0 0 0-3 3v1.5m18 0v-1.5a3 3 0 0 0-3-3h-1.5m-3-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 9v-1.125a2.625 2.625 0 0 0-2.625-2.625H15.75m-9.75 3.75a2.625 2.625 0 0 1 2.625-2.625H12" />
                                                </svg>
                                                <span>{{ __('Lihat Detail Barang') }}</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div id="{{ $collateralTemplateId }}" class="hidden" data-report-collateral-template>
                                        <div class="space-y-4">
                                            @foreach ($transaksi->barangJaminan as $barang)
                                                <article class="rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-950">
                                                    <div class="flex flex-col gap-2">
                                                        <div class="flex items-start justify-between gap-3">
                                                            <div>
                                                                <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">
                                                                    {{ __('Barang :number', ['number' => $loop->iteration]) }}
                                                                </h3>
                                                                <p class="text-xs text-neutral-500 dark:text-neutral-300">
                                                                    {{ $barang->jenis_barang ?? '—' }}@if ($barang->merek)
                                                                        · {{ $barang->merek }}
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div class="text-right">
                                                                <span class="text-[11px] font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Nilai Taksiran') }}</span>
                                                                <div class="text-sm font-semibold text-emerald-600 dark:text-emerald-300">Rp {{ number_format((float) $barang->nilai_taksiran, 0, ',', '.') }}</div>
                                                            </div>
                                                        </div>
                                                        <dl class="grid grid-cols-1 gap-3 text-sm text-neutral-700 dark:text-neutral-200">
                                                            <div class="space-y-1">
                                                                <dt class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Kondisi') }}</dt>
                                                                <dd>{{ $barang->kondisi ?? '—' }}</dd>
                                                            </div>
                                                            <div class="space-y-1">
                                                                <dt class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Kelengkapan') }}</dt>
                                                                <dd>{{ $barang->kelengkapan ?? '—' }}</dd>
                                                            </div>
                                                            <div class="space-y-1">
                                                                <dt class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Keterangan') }}</dt>
                                                                <dd>{{ $barang->keterangan ?? '—' }}</dd>
                                                            </div>
                                                        </dl>
                                                    </div>
                                                </article>
                                            @endforeach
                                        </div>
                                    </div>
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
                                @if ($transaksi->total_potongan > 0)
                                    <div class="text-xs text-neutral-500 dark:text-neutral-300">{{ __('Total potongan: Rp :amount', ['amount' => number_format($transaksi->total_potongan, 0, ',', '.')]) }}</div>
                                @endif
                                @if ($transaksi->uang_cair !== null)
                                    <div class="text-xs font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Uang cair: Rp :amount', ['amount' => number_format($transaksi->uang_cair, 0, ',', '.')]) }}</div>
                                @endif
                                <div class="text-xs text-neutral-500 dark:text-neutral-300">
                                    @if ($transaksi->tenor_hari)
                                        {{ __('Tenor: :days hari', ['days' => $transaksi->tenor_hari]) }}
                                    @else
                                        {{ __('Tenor: —') }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-emerald-600 dark:text-emerald-300">
                                    @if ((float) $transaksi->total_bunga > 0)
                                        Rp {{ number_format((float) $transaksi->total_bunga, 0, ',', '.') }}
                                    @else
                                        —
                                    @endif
                                </div>
                                @if ((float) $transaksi->tarif_bunga_harian > 0)
                                    <div class="text-xs text-neutral-500 dark:text-neutral-300">
                                        {{ __('Tarif bunga harian: :rate%', ['rate' => rtrim(rtrim(number_format((float) $transaksi->tarif_bunga_harian, 2, '.', ''), '0'), '.')]) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-emerald-600 dark:text-emerald-300">
                                    @if ((float) $transaksi->bunga_terutang_riil > 0)
                                        Rp {{ number_format((float) $transaksi->bunga_terutang_riil, 0, ',', '.') }}
                                    @else
                                        —
                                    @endif
                                </div>
                                @if ($transaksi->actual_days)
                                    <div class="text-xs text-neutral-500 dark:text-neutral-300">
                                        {{ __('Hari berjalan: :days hari', ['days' => $transaksi->actual_days]) }}
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
                            <td colspan="9" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-300">
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

    <div
        id="laporan-collateral-panel"
        class="fixed inset-0 z-40 hidden"
        data-report-collateral-panel
        data-placeholder="{{ __('Pilih salah satu transaksi untuk melihat detail barang jaminan.') }}"
        data-empty="{{ __('Barang jaminan tidak ditemukan.') }}"
        role="dialog"
        aria-modal="true"
        aria-labelledby="laporan-collateral-panel-title"
        aria-hidden="true"
    >
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" data-report-collateral-overlay></div>
        <div class="absolute inset-y-0 right-0 flex max-w-full pl-10">
            <div
                class="pointer-events-auto flex h-full w-screen max-w-md min-h-0 flex-col overflow-hidden bg-white shadow-xl transition dark:bg-neutral-900"
                data-report-collateral-dialog
                tabindex="-1"
            >
                <div class="flex items-start justify-between gap-4 border-b border-neutral-200 px-6 py-4 dark:border-neutral-700">
                    <div class="space-y-1">
                        <h2 id="laporan-collateral-panel-title" class="text-base font-semibold text-neutral-900 dark:text-white">
                            {{ __('Detail Barang Jaminan') }}
                        </h2>
                        <p class="text-xs text-neutral-500 dark:text-neutral-300">
                            {{ __('Periksa informasi lengkap setiap barang dalam transaksi ini.') }}
                        </p>
                    </div>
                    <button
                        type="button"
                        class="text-neutral-400 transition hover:text-neutral-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-400 dark:text-neutral-500 dark:hover:text-neutral-300"
                        data-report-collateral-close
                    >
                        <span class="sr-only">{{ __('Tutup panel detail') }}</span>
                        <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex-1 min-h-0 overflow-y-auto px-6 py-5" data-report-collateral-body>
                    <p class="text-sm text-neutral-500 dark:text-neutral-300">{{ __('Pilih salah satu transaksi untuk melihat detail barang jaminan.') }}</p>
                </div>
            </div>
        </div>
    </div>

    @once
        <script data-navigate-once>
            window.KRESNO = window.KRESNO || {};
            if (!window.KRESNO.laporanTransaksiGadaiBound) {
                const state = {
                    collateral: {
                        panel: null,
                        open: null,
                        close: null,
                        lastFocused: null,
                        focusables: [],
                    },
                };

                const initCollateralPanel = () => {
                    const panel = document.querySelector('[data-report-collateral-panel]');
                    if (!panel || panel.dataset.bound === 'true') {
                        return;
                    }

                    panel.dataset.bound = 'true';

                    const overlay = panel.querySelector('[data-report-collateral-overlay]');
                    const dialog = panel.querySelector('[data-report-collateral-dialog]');
                    const body = panel.querySelector('[data-report-collateral-body]');
                    const closeButtons = panel.querySelectorAll('[data-report-collateral-close]');
                    const focusableSelector = 'a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])';

                    const renderMessage = (text) => {
                        if (!body) {
                            return;
                        }

                        body.innerHTML = '';
                        const paragraph = document.createElement('p');
                        paragraph.className = 'text-sm text-neutral-500 dark:text-neutral-300';
                        paragraph.textContent = text;
                        body.appendChild(paragraph);
                    };

                    const trapFocus = (event) => {
                        if (event.key !== 'Tab') {
                            return;
                        }

                        const focusables = state.collateral.focusables;
                        if (!focusables.length) {
                            event.preventDefault();
                            dialog?.focus();
                            return;
                        }

                        const first = focusables[0];
                        const last = focusables[focusables.length - 1];
                        if (event.shiftKey) {
                            if (document.activeElement === first) {
                                event.preventDefault();
                                last.focus();
                            }
                        } else if (document.activeElement === last) {
                            event.preventDefault();
                            first.focus();
                        }
                    };

                    const closePanel = () => {
                        if (!panel || panel.classList.contains('hidden')) {
                            return;
                        }

                        panel.classList.add('hidden');
                        panel.setAttribute('aria-hidden', 'true');
                        document.body.classList.remove('overflow-hidden');
                        renderMessage(panel.dataset.placeholder || '');
                        if (state.collateral.lastFocused) {
                            state.collateral.lastFocused.focus();
                        }
                        state.collateral.focusables = [];
                        state.collateral.lastFocused = null;
                    };

                    const openPanel = (trigger) => {
                        if (!panel || !dialog || !body) {
                            return;
                        }

                        const targetId = trigger.dataset.target;
                        if (!targetId) {
                            return;
                        }

                        const template = document.getElementById(targetId);
                        if (!template) {
                            return;
                        }

                        const content = template.innerHTML.trim();
                        if (content) {
                            body.innerHTML = content;
                        } else {
                            renderMessage(panel.dataset.empty || '');
                        }

                        panel.classList.remove('hidden');
                        panel.setAttribute('aria-hidden', 'false');
                        document.body.classList.add('overflow-hidden');

                        state.collateral.lastFocused = trigger;

                        const focusables = Array.from(dialog.querySelectorAll(focusableSelector)).filter(
                            (element) => element.offsetParent !== null || element === dialog,
                        );
                        state.collateral.focusables = focusables.length ? focusables : [dialog];

                        requestAnimationFrame(() => {
                            (state.collateral.focusables[0] || dialog).focus({ preventScroll: false });
                        });
                    };

                    overlay?.addEventListener('click', (event) => {
                        event.preventDefault();
                        closePanel();
                    });

                    closeButtons.forEach((button) => {
                        button.addEventListener('click', (event) => {
                            event.preventDefault();
                            closePanel();
                        });
                    });

                    panel.addEventListener('keydown', (event) => {
                        if (event.key === 'Escape') {
                            event.preventDefault();
                            closePanel();
                            return;
                        }

                        trapFocus(event);
                    });

                    state.collateral = {
                        panel,
                        open: openPanel,
                        close: closePanel,
                        lastFocused: null,
                        focusables: [],
                    };
                };

                const bindTable = () => {
                    const table = document.querySelector('[data-report-transaksi-table]');
                    if (!table || table.dataset.bound === 'true') {
                        return;
                    }

                    table.dataset.bound = 'true';

                    table.addEventListener('click', (event) => {
                        const collateralButton = event.target.closest('[data-open-report-collateral]');
                        if (collateralButton) {
                            event.preventDefault();
                            if (typeof state.collateral.open === 'function') {
                                state.collateral.open(collateralButton);
                            }
                        }
                    });
                };

                const bootstrap = () => {
                    initCollateralPanel();
                    bindTable();
                };

                document.addEventListener('DOMContentLoaded', bootstrap);
                document.addEventListener('livewire:navigated', bootstrap);

                window.KRESNO.laporanTransaksiGadaiBound = true;
            }
        </script>
    @endonce
</x-layouts.app>
