<x-layouts.app :title="__('Nota Transaksi Gadai')">
    <style>
        @media print {
            body {
                background: #fff !important;
            }

            body * {
                visibility: hidden;
            }

            #nota-print-area,
            #nota-print-area * {
                visibility: visible;
            }

            #nota-print-area {
                position: absolute;
                inset: 0;
                margin: 0;
                width: 100%;
                border: none !important;
                box-shadow: none !important;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>

    <div class="space-y-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between no-print">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">{{ __('Preview PDF') }}</p>
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Nota Transaksi Gadai') }}</h1>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('Tinjau nota kontrak gadai berikut sebelum mencetak atau menyimpan sebagai PDF.') }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a
                    href="{{ route('gadai.lihat-gadai') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-neutral-300 px-3 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800"
                >
                    {{ __('Kembali ke daftar') }}
                </a>
                <button
                    type="button"
                    onclick="window.print()"
                    class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-2 dark:focus:ring-offset-neutral-900"
                >
                    {{ __('Cetak / Simpan PDF') }}
                </button>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <div class="border-b border-neutral-200 bg-neutral-50 px-6 py-4 dark:border-neutral-700 dark:bg-neutral-900">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Nomor SBG') }}</p>
                        <p class="text-lg font-semibold text-neutral-900 dark:text-white">{{ $transaksi->no_sbg }}</p>
                    </div>
                    <div class="text-sm text-neutral-600 dark:text-neutral-300">
                        <p>{{ __('Tanggal Gadai') }}: <span class="font-semibold text-neutral-900 dark:text-white">{{ optional($transaksi->tanggal_gadai)->translatedFormat('d F Y') }}</span></p>
                        <p>{{ __('Jatuh Tempo') }}: <span class="font-semibold text-neutral-900 dark:text-white">{{ optional($transaksi->jatuh_tempo_awal)->translatedFormat('d F Y') }}</span></p>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 px-6 py-6 lg:grid-cols-3">
                <div class="space-y-3">
                    <p class="text-sm font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Data Nasabah') }}</p>
                    <dl class="space-y-2 text-sm text-neutral-700 dark:text-neutral-300">
                        <div class="flex justify-between gap-3">
                            <dt>{{ __('Nama') }}</dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">{{ $transaksi->nasabah->nama ?? __('Tidak diketahui') }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt>{{ __('Kode Member') }}</dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">{{ $transaksi->nasabah->kode_member ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt>{{ __('Alamat') }}</dt>
                            <dd class="text-right font-semibold text-neutral-900 dark:text-white">{{ $transaksi->nasabah->alamat ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt>{{ __('Telepon') }}</dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">{{ $transaksi->nasabah->telepon ?? '—' }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="space-y-3">
                    <p class="text-sm font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Detail Transaksi') }}</p>
                    <dl class="space-y-2 text-sm text-neutral-700 dark:text-neutral-300">
                        <div class="flex justify-between gap-3">
                            <dt>{{ __('Type') }}</dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">{{ $transaksi->type ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt>{{ __('Tenor (hari)') }}</dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">{{ $transaksi->tenor_hari ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt>{{ __('Tarif Bunga Harian') }}</dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">{{ number_format((float) ($transaksi->tarif_bunga_harian ?? 0) * 100, 3, ',', '.') }}%</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt>{{ __('Kasir') }}</dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">{{ $transaksi->kasir->name ?? '—' }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="space-y-3">
                    <p class="text-sm font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Nilai Transaksi') }}</p>
                    <dl class="space-y-2 text-sm text-neutral-700 dark:text-neutral-300">
                        <div class="flex justify-between gap-3">
                            <dt>{{ __('Pinjaman Disetujui') }}</dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">Rp {{ number_format((float) ($transaksi->uang_pinjaman ?? 0), 2, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt>{{ __('Biaya Admin') }}</dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">Rp {{ number_format((float) ($transaksi->biaya_admin ?? 0), 2, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt>{{ __('Premi') }}</dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">Rp {{ number_format((float) ($transaksi->premi ?? 0), 2, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt>{{ __('Uang Cair') }}</dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">Rp {{ number_format((float) ($transaksi->uang_cair ?? 0), 2, ',', '.') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="border-t border-neutral-200 px-6 py-4 dark:border-neutral-700">
                <p class="text-sm font-semibold text-neutral-700 dark:text-neutral-200 no-print">{{ __('Rincian Barang Jaminan') }}</p>
                <div id="nota-print-area" class="mt-3 overflow-hidden rounded-lg border border-neutral-200 dark:border-neutral-700">
                    <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                        <thead class="bg-neutral-50 text-left text-xs uppercase tracking-wider text-neutral-600 dark:bg-neutral-900 dark:text-neutral-300">
                            <tr>
                                <th class="px-4 py-3">{{ __('Jenis & Merek') }}</th>
                                <th class="px-4 py-3 text-right">{{ __('Nilai Taksiran') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                            @forelse ($transaksi->barangJaminan as $barang)
                                <tr>
                                    <td class="px-4 py-3 text-neutral-800 dark:text-neutral-100">
                                        <p class="font-semibold">{{ $barang->jenis_barang }} — {{ $barang->merek }}</p>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Kode Barang') }}: {{ $barang->barang_id }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-neutral-900 dark:text-white">Rp {{ number_format((float) ($barang->nilai_taksiran ?? 0), 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-4 text-center text-neutral-500 dark:text-neutral-400">{{ __('Tidak ada barang terlampir.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if (request()->boolean('auto_print'))
        <script>
            (() => {
                const removeAutoPrintParam = () => {
                    try {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('auto_print');
                        window.history.replaceState({}, '', url.toString());
                    } catch (error) {
                        // noop
                    }
                };

                const triggerPrint = (targetWindow) => {
                    if (!targetWindow) return;

                    const startPrint = () => {
                        try {
                            targetWindow.focus();
                            targetWindow.print();
                        } catch (error) {
                            // noop
                        }
                    };

                    if (targetWindow.document?.readyState === 'complete') {
                        startPrint();
                        return;
                    }

                    targetWindow.addEventListener('load', () => {
                        startPrint();
                    }, { once: true });
                };

                if (window.opener) {
                    triggerPrint(window);
                    removeAutoPrintParam();
                    return;
                }

                const printWindow = window.open(window.location.href, '_blank');
                if (!printWindow) {
                    return;
                }

                triggerPrint(printWindow);
                removeAutoPrintParam();
            })();
        </script>
    @endif
</x-layouts.app>
