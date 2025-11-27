<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Nota Transaksi Gadai') }}</title>

    {{-- kalau di projectmu pakai Vite/Tailwind, biarkan baris ini --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            body {
                margin: 0;
                background: #ffffff;
            }
        }
    </style>
</head>
<body class="bg-neutral-100 text-neutral-900">

    <div class="min-h-screen flex items-start justify-center py-8">
        <div class="w-full max-w-3xl overflow-hidden border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <div class="border-b border-neutral-200 bg-neutral-50 px-6 py-4 dark:border-neutral-700 dark:bg-neutral-900">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Nomor SBG') }}</p>
                        <p class="text-lg font-semibold text-neutral-900 dark:text-white">{{ $transaksi->no_sbg }}</p>
                    </div>
                    <div class="text-sm text-neutral-600 dark:text-neutral-300">
                        <p>{{ __('Tanggal Gadai') }}:
                            <span class="font-semibold text-neutral-900 dark:text-white">
                                {{ optional($transaksi->tanggal_gadai)->translatedFormat('d F Y') }}
                            </span>
                        </p>
                        <p>{{ __('Jatuh Tempo') }}:
                            <span class="font-semibold text-neutral-900 dark:text-white">
                                {{ optional($transaksi->jatuh_tempo_awal)->translatedFormat('d F Y') }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 px-6 py-6 lg:grid-cols-3">
                <div class="space-y-3">
                    <p class="text-sm font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Data Nasabah') }}</p>
                    <dl class="space-y-2 text-sm text-neutral-700 dark:text-neutral-300">
                        <div class="flex justify-between gap-3">
                            <dt>{{ __('Nama') }}</dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">
                                {{ $transaksi->nasabah->nama ?? __('Tidak diketahui') }}
                            </dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt>{{ __('Kode Member') }}</dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">
                                {{ $transaksi->nasabah->kode_member ?? '—' }}
                            </dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt>{{ __('Alamat') }}</dt>
                            <dd class="text-right font-semibold text-neutral-900 dark:text-white">
                                {{ $transaksi->nasabah->alamat ?? '—' }}
                            </dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt>{{ __('Telepon') }}</dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">
                                {{ $transaksi->nasabah->telepon ?? '—' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="space-y-3">
                    <p class="text-sm font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Detail Transaksi') }}</p>
                    <dl class="space-y-2 text-sm text-neutral-700 dark:text-neutral-300">
                        <div class="flex justify-between gap-3">
                            <dt>{{ __('Type') }}</dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">
                                {{ $transaksi->type ?? '—' }}
                            </dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt>{{ __('Tenor (hari)') }}</dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">
                                {{ $transaksi->tenor_hari ?? '—' }}
                            </dd>
                        </div>
                        @php
                            $tarifBungaHarian = (float) ($transaksi->tarif_bunga_harian ?? 0);
                            $tarifBungaPeriodik = (float) ($transaksi->tarif_bunga_per_periode ?? 0);
                        @endphp

                        @if ($tarifBungaPeriodik > 0)
                            <div class="flex justify-between gap-3">
                                <dt>{{ __('Tarif Bunga Periodik') }}</dt>
                                <dd class="font-semibold text-neutral-900 dark:text-white">
                                    {{ number_format($tarifBungaPeriodik * 100, 3, ',', '.') }}%
                                </dd>
                            </div>
                        @elseif ($tarifBungaHarian > 0)
                            <div class="flex justify-between gap-3">
                                <dt>{{ __('Tarif Bunga Harian') }}</dt>
                                <dd class="font-semibold text-neutral-900 dark:text-white">
                                    {{ number_format($tarifBungaHarian * 100, 3, ',', '.') }}%
                                </dd>
                            </div>
                        @endif
                        <!-- <div class="flex justify-between gap-3">
                            <dt>{{ __('Kasir') }}</dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">
                                {{ $transaksi->kasir->name ?? '—' }}
                            </dd>
                        </div> -->
                    </dl>
                </div>

                <div class="space-y-3">
                    <p class="text-sm font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Nilai Transaksi') }}</p>
                    <dl class="space-y-2 text-sm text-neutral-700 dark:text-neutral-300">
                        <div class="flex justify-between gap-3">
                            <dt>{{ __('Pinjaman Disetujui') }}</dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">
                                Rp {{ number_format((float) ($transaksi->uang_pinjaman ?? 0), 2, ',', '.') }}
                            </dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt>{{ __('Biaya Admin') }}</dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">
                                Rp {{ number_format((float) ($transaksi->biaya_admin ?? 0), 2, ',', '.') }}
                            </dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt>{{ __('Premi') }}</dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">
                                Rp {{ number_format((float) ($transaksi->premi ?? 0), 2, ',', '.') }}
                            </dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt>{{ __('Uang Cair') }}</dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">
                                Rp {{ number_format((float) ($transaksi->uang_cair ?? 0), 2, ',', '.') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="border-t border-neutral-200 px-6 py-4 dark:border-neutral-700">
                <p class="text-sm font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Rincian Barang Jaminan') }}</p>
                <div class="mt-3 overflow-hidden rounded-lg border border-neutral-200 dark:border-neutral-700">
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
                                        <!-- <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                            {{ __('Kode Barang') }}: {{ $barang->barang_id }}
                                        </p> -->
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                            {{ __('Kondisi') }}: {{ $barang->kondisi_fisik }}
                                        </p>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                            {{ __('Kelengkapan') }}: {{ $barang->kelengkapan }}
                                        </p>
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-neutral-900 dark:text-white">
                                        Rp {{ number_format((float) ($barang->nilai_taksiran ?? 0), 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-4 text-center text-neutral-500 dark:text-neutral-400">
                                        {{ __('Tidak ada barang terlampir.') }}
                                    </td>
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

</body>
</html>
