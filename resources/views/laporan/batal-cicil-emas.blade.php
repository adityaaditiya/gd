<x-layouts.app :title="__('Laporan Batal Cicilan')">
    <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Laporan Batal Cicilan') }}</h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                {{ __('Pantau tren pembatalan cicilan emas lengkap dengan ringkasan nilai pembiayaan yang dibatalkan, alasan pembatalan, serta petugas yang memprosesnya.') }}
            </p>
        </div>

        <section class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <form method="get" class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div class="flex flex-col gap-2">
                    <label for="start_date" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Periode Mulai') }}</label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ $filters['start_date'] ?? '' }}"
                        class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100"
                    >
                </div>
                <div class="flex flex-col gap-2">
                    <label for="end_date" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Periode Selesai') }}</label>
                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ $filters['end_date'] ?? '' }}"
                        class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100"
                    >
                </div>
                <div class="flex flex-col gap-2">
                    <label for="query" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Nasabah') }}</label>
                    <input
                        type="search"
                        id="query"
                        name="query"
                        value="{{ $filters['query'] ?? '' }}"
                        placeholder="{{ __('Nama atau kode member') }}"
                        class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100"
                    >
                </div>
                <div class="flex flex-col gap-2">
                    <label for="petugas" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Petugas') }}</label>
                    <input
                        type="search"
                        id="petugas"
                        name="petugas"
                        value="{{ $filters['petugas'] ?? '' }}"
                        placeholder="{{ __('Nama petugas pembatal') }}"
                        class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100"
                    >
                </div>
                <div class="flex items-end gap-3 md:col-span-2 lg:col-span-4">
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 dark:focus:ring-offset-neutral-900">{{ __('Terapkan Filter') }}</button>
                    @if(!empty(array_filter($filters ?? [])))
                        <a href="{{ route('laporan.batal-cicil-emas') }}" class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800">{{ __('Reset') }}</a>
                    @endif
                </div>
            </form>
        </section>

        <section class="grid gap-4 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <header class="flex items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wide text-rose-500">{{ __('Ringkasan Pembatalan') }}</span>
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Snapshot Dampak Pembatalan Cicilan') }}</h2>
                </div>
                <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-900/40 dark:text-rose-200">
                    {{ number_format($metrics['total_transactions'] ?? 0, 0, ',', '.') }} {{ __('transaksi batal') }}
                </span>
            </header>

            <dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Total Pembiayaan Dibatalkan') }}</dt>
                    <dd class="text-xl font-semibold text-neutral-900 dark:text-white">Rp {{ number_format($metrics['total_financed'] ?? 0, 0, ',', '.') }}</dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Total Pokok Tercatat') }}</dt>
                    <dd class="text-xl font-semibold text-neutral-900 dark:text-white">Rp {{ number_format($metrics['total_principal'] ?? 0, 0, ',', '.') }}</dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Margin Potensial Hilang') }}</dt>
                    <dd class="text-xl font-semibold text-purple-600 dark:text-purple-300">Rp {{ number_format($metrics['total_margin'] ?? 0, 0, ',', '.') }}</dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Administrasi Terimbas') }}</dt>
                    <dd class="text-xl font-semibold text-neutral-900 dark:text-white">Rp {{ number_format($metrics['total_administration'] ?? 0, 0, ',', '.') }}</dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Rata-rata Progres Pembayaran') }}</dt>
                    <dd class="text-xl font-semibold text-blue-600 dark:text-blue-300">{{ number_format($metrics['average_completion'] ?? 0, 2, ',', '.') }}%</dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Total Transaksi Batal') }}</dt>
                    <dd class="text-xl font-semibold text-neutral-900 dark:text-white">{{ number_format($metrics['total_transactions'] ?? 0, 0, ',', '.') }}</dd>
                </div>
            </dl>

            <div class="grid gap-4 lg:grid-cols-2">
                <div class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">{{ __('Alasan Pembatalan Teratas') }}</h3>
                    <ul class="mt-3 space-y-2 text-sm text-neutral-600 dark:text-neutral-300">
                        @php($reasons = collect($metrics['reason_buckets'] ?? [])->take(5))
                        @forelse($reasons as $reason => $count)
                            <li class="flex items-center justify-between">
                                <span class="line-clamp-2 pe-4">{{ $reason }}</span>
                                <span class="rounded-full bg-neutral-100 px-2 py-0.5 text-xs font-semibold text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">{{ number_format($count, 0, ',', '.') }}</span>
                            </li>
                        @empty
                            <li class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Belum ada data pembatalan.') }}</li>
                        @endforelse
                    </ul>
                </div>
                <div class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">{{ __('Petugas Terlibat Pembatalan') }}</h3>
                    <ul class="mt-3 space-y-2 text-sm text-neutral-600 dark:text-neutral-300">
                        @php($officers = collect($metrics['officer_buckets'] ?? [])->take(5))
                        @forelse($officers as $officer => $count)
                            <li class="flex items-center justify-between">
                                <span>{{ $officer }}</span>
                                <span class="rounded-full bg-neutral-100 px-2 py-0.5 text-xs font-semibold text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">{{ number_format($count, 0, ',', '.') }}</span>
                            </li>
                        @empty
                            <li class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Belum ada data petugas.') }}</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">{{ __('Linimasa Pembatalan') }}</h3>
                <ul class="mt-3 space-y-2 text-sm text-neutral-600 dark:text-neutral-300">
                    @php($timeline = collect($metrics['timeline'] ?? []))
                    @forelse($timeline as $date => $data)
                        <li class="flex items-center justify-between">
                            <span>{{ \Carbon\Carbon::parse($date)->translatedFormat('d M Y') }}</span>
                            <span class="flex items-center gap-3">
                                <span class="rounded-full bg-neutral-100 px-2 py-0.5 text-xs font-semibold text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">{{ number_format($data['count'] ?? 0, 0, ',', '.') }} {{ __('transaksi') }}</span>
                                <span class="text-xs text-neutral-500 dark:text-neutral-400">Rp {{ number_format($data['total_financed'] ?? 0, 0, ',', '.') }}</span>
                            </span>
                        </li>
                    @empty
                        <li class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Belum ada data linimasa pembatalan.') }}</li>
                    @endforelse
                </ul>
            </div>
        </section>

        <section class="flex flex-col gap-4">
            <header class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Detail Transaksi Batal') }}</h2>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Gunakan tabel berikut untuk audit dan analisis penyebab pembatalan cicilan.') }}</p>
            </header>

            @forelse($insights as $insight)
                @php
                    $transaction = $insight['model'];
                    $nasabah = $transaction->nasabah;
                    $items = collect($insight['items'] ?? []);
                    $primaryItem = $items->first();
                    $pembatal = $transaction->pembatal;
                @endphp

                @if($loop->first)
                    <div class="overflow-hidden rounded-xl border border-neutral-200 shadow-sm dark:border-neutral-700">
                        <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                            <thead class="bg-neutral-50 dark:bg-neutral-800/70">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Tanggal Batal') }}</th>
                                    <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Nasabah') }}</th>
                                    <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Paket / Barang') }}</th>
                                    <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Tenor') }}</th>
                                    <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Nilai Emas Awal') }}</th>
                                    <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Total Pembiayaan') }}</th>
                                    <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Pokok Pembiayaan') }}</th>
                                    <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Margin') }}</th>
                                    <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Administrasi') }}</th>
                                    <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Progres Pembayaran') }}</th>
                                    <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Alasan Pembatalan') }}</th>
                                    <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Diproses Oleh') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-900">
                @endif

                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/60">
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">{{ $transaction->dibatalkan_pada?->translatedFormat('d M Y H:i') ?? '—' }}</td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-neutral-900 dark:text-neutral-100">{{ $nasabah?->nama ?? __('Nasabah tidak ditemukan') }}</span>
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Kode: :kode', ['kode' => $nasabah?->kode_member ?? '—']) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">
                                        <div class="flex flex-col">
                                            @if ($items->count() === 1)
                                                <span>{{ $primaryItem['nama_barang'] ?? $transaction->pabrikan }}</span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ number_format((float) ($primaryItem['berat'] ?? $transaction->berat_gram), 3, ',', '.') }} gr · {{ $primaryItem['kode'] ?? $transaction->kadar }}</span>
                                            @elseif ($items->count() > 1)
                                                <span>{{ __(':count barang', ['count' => $items->count()]) }}</span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ number_format($transaction->berat_gram, 3, ',', '.') }} gr · {{ $transaction->kadar }}</span>
                                            @else
                                                <span>{{ $transaction->pabrikan }}</span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ number_format($transaction->berat_gram, 3, ',', '.') }} gr · {{ $transaction->kadar }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">{{ $transaction->tenor_bulan }} {{ __('bulan') }}</td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp {{ number_format($transaction->harga_emas ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp {{ number_format($transaction->total_pembiayaan ?? ($insight['total_financed'] ?? 0), 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp {{ number_format($insight['principal_without_margin'] ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp {{ number_format($insight['margin_amount'] ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp {{ number_format($insight['administrasi'] ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">{{ number_format($insight['completion_ratio'] ?? 0, 2, ',', '.') }}%</td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">
                                        <span class="line-clamp-3">{{ $transaction->alasan_pembatalan ?? __('Tanpa alasan') }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">{{ $pembatal?->name ?? __('Tidak diketahui') }}</td>
                                </tr>

                @if($loop->last)
                            </tbody>
                        </table>
                    </div>
                @endif
            @empty
                <div class="rounded-xl border border-dashed border-neutral-300 bg-white p-10 text-center shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                    <h3 class="text-lg font-semibold text-neutral-800 dark:text-white">{{ __('Tidak ada data pembatalan untuk filter yang dipilih.') }}</h3>
                    <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-300">{{ __('Ubah periode, nasabah, atau petugas untuk melihat transaksi yang dibatalkan.') }}</p>
                </div>
            @endforelse
        </section>
    </div>
</x-layouts.app>
