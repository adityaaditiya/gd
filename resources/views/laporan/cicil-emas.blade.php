<x-layouts.app :title="__('Laporan Cicil Emas')">
    <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Laporan Cicil Emas') }}</h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                {{ __('Susun laporan audit internal atas kinerja portofolio cicilan emas lengkap dengan filter periode dan status pembayaran.') }}
            </p>
        </div>

        <section class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <form method="get" class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div class="flex flex-col gap-2">
                    <label for="start_date" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Periode Mulai') }}</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100" />
                </div>
                <div class="flex flex-col gap-2">
                    <label for="end_date" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Periode Selesai') }}</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100" />
                </div>
                <div class="flex flex-col gap-2">
                    <label for="status" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Status Pembayaran') }}</label>
                    <select id="status" name="status" class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100">
                        <option value="">{{ __('Semua Status') }}</option>
                        <option value="Aktif" @selected(($filters['status'] ?? null) === 'Aktif')>{{ __('Aktif') }}</option>
                        <option value="Menunggak" @selected(($filters['status'] ?? null) === 'Menunggak')>{{ __('Menunggak') }}</option>
                        <option value="Lunas" @selected(($filters['status'] ?? null) === 'Lunas')>{{ __('Lunas') }}</option>
                    </select>
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 dark:focus:ring-offset-neutral-900">{{ __('Terapkan Filter') }}</button>
                    @if(!empty(array_filter($filters ?? [])))
                        <a href="{{ route('laporan.cicil-emas') }}" class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800">{{ __('Reset') }}</a>
                    @endif
                </div>
            </form>
        </section>

        <section class="grid gap-4 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <header class="flex items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wide text-fuchsia-500">{{ __('Ringkasan Audit') }}</span>
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Snapshot Portofolio & Risiko') }}</h2>
                </div>
                <span class="rounded-full bg-fuchsia-100 px-3 py-1 text-xs font-semibold text-fuchsia-700 dark:bg-fuchsia-900/40 dark:text-fuchsia-200">
                    {{ number_format($metrics['total_transactions'] ?? 0, 0, ',', '.') }} {{ __('transaksi tercakup') }}
                </span>
            </header>

            <dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Total Pembiayaan') }}</dt>
                    <dd class="text-xl font-semibold text-neutral-900 dark:text-white">Rp {{ number_format($metrics['total_financed'] ?? 0, 0, ',', '.') }}</dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Total Pokok Tercatat') }}</dt>
                    <dd class="text-xl font-semibold text-neutral-900 dark:text-white">Rp {{ number_format($metrics['total_principal'] ?? 0, 0, ',', '.') }}</dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Total Margin Terjadwal') }}</dt>
                    <dd class="text-xl font-semibold text-purple-600 dark:text-purple-300">Rp {{ number_format($metrics['total_margin'] ?? 0, 0, ',', '.') }}</dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Saldo Pembiayaan Tersisa') }}</dt>
                    <dd class="text-xl font-semibold text-amber-600 dark:text-amber-300">Rp {{ number_format($metrics['total_outstanding'] ?? 0, 0, ',', '.') }}</dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Total Pembayaran') }}</dt>
                    <dd class="text-xl font-semibold text-emerald-600 dark:text-emerald-300">Rp {{ number_format($metrics['total_paid'] ?? 0, 0, ',', '.') }}</dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Total Denda Tercatat') }}</dt>
                    <dd class="text-xl font-semibold text-rose-600 dark:text-rose-300">Rp {{ number_format($metrics['total_penalty'] ?? 0, 0, ',', '.') }}</dd>
                </div>
            </dl>

            <div class="grid gap-4 lg:grid-cols-2">
                <div class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">{{ __('Distribusi Status Pembayaran') }}</h3>
                    <div class="mt-4 grid gap-3">
                        @php
                            $statusColors = [
                                'Aktif' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-200',
                                'Menunggak' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-200',
                                'Lunas' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200',
                            ];
                        @endphp
                        @forelse($statusBuckets as $status => $count)
                            <div class="flex items-center justify-between rounded-lg border border-dashed border-neutral-200 px-4 py-3 dark:border-neutral-700">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-neutral-900 dark:text-neutral-100">{{ __($status) }}</span>
                                    <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Jumlah: :count transaksi', ['count' => number_format($count, 0, ',', '.')]) }}</span>
                                </div>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusColors[$status] ?? 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200' }}">{{ __('Status') }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Belum ada data status cicilan pada filter ini.') }}</p>
                        @endforelse
                    </div>
                </div>
                <div class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">{{ __('Indikator Risiko') }}</h3>
                    <dl class="mt-4 space-y-3 text-sm text-neutral-600 dark:text-neutral-300">
                        <div class="flex items-center justify-between">
                            <dt>{{ __('Rasio Menunggak') }}</dt>
                            <dd class="font-semibold text-rose-600 dark:text-rose-300">{{ number_format($metrics['late_ratio'] ?? 0, 2, ',', '.') }}%</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt>{{ __('Rata-rata Penyelesaian') }}</dt>
                            <dd class="font-semibold text-blue-600 dark:text-blue-300">{{ number_format($metrics['average_completion'] ?? 0, 2, ',', '.') }}%</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt>{{ __('Total Transaksi diaudit') }}</dt>
                            <dd class="font-semibold text-neutral-900 dark:text-neutral-100">{{ number_format($metrics['total_transactions'] ?? 0, 0, ',', '.') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </section>

        <section class="flex flex-col gap-4">
            <header class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Detail Transaksi Cicil Emas') }}</h2>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Gunakan tabel berikut untuk keperluan audit dan rekonsiliasi pembayaran.') }}</p>
            </header>

            @if(($insights->count() ?? 0) === 0)
                <div class="rounded-xl border border-dashed border-neutral-300 bg-white p-10 text-center shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                    <h3 class="text-lg font-semibold text-neutral-800 dark:text-white">{{ __('Tidak ada data untuk filter yang dipilih.') }}</h3>
                    <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-300">{{ __('Ubah periode atau status untuk melihat portofolio cicilan lainnya.') }}</p>
                </div>
            @else
                <div class="overflow-hidden rounded-xl border border-neutral-200 shadow-sm dark:border-neutral-700">
                    <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                        <thead class="bg-neutral-50 dark:bg-neutral-800/70">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Tanggal Transaksi') }}</th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Nasabah') }}</th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Paket Emas') }}</th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Tenor') }}</th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Nilai Emas Awal') }}</th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Pokok Pembiayaan') }}</th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Margin') }}</th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Total Pembiayaan') }}</th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Saldo Pembiayaan Tersisa') }}</th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Total Dibayar') }}</th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Total Denda') }}</th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-900">
                            @foreach($insights as $insight)
                                @php
                                    $transaction = $insight['model'];
                                    $nasabah = $transaction->nasabah;
                                    $barang = $insight['barang'];
                                    $statusClass = [
                                        'success' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
                                        'danger' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-200',
                                        'info' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-200',
                                    ][$insight['status_style'] ?? 'info'] ?? 'bg-neutral-200 text-neutral-800 dark:bg-neutral-700 dark:text-neutral-200';
                                @endphp
                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/60">
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">{{ $transaction->created_at?->translatedFormat('d M Y H:i') }}</td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-neutral-900 dark:text-neutral-100">{{ $nasabah?->nama ?? __('Nasabah tidak ditemukan') }}</span>
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Kode: :kode', ['kode' => $nasabah?->kode_member ?? '—']) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">
                                        <div class="flex flex-col">
                                            <span>{{ $barang?->nama_barang ?? $transaction->pabrikan }}</span>
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ number_format($transaction->berat_gram, 3, ',', '.') }} gr · {{ $transaction->kadar }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">{{ $transaction->tenor_bulan }} {{ __('bulan') }}</td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp {{ number_format($transaction->harga_emas, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp {{ number_format($insight['principal_without_margin'] ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">
                                        {{ number_format($insight['margin_percentage'] ?? 0, 2, ',', '.') }}% • Rp {{ number_format($insight['margin_amount'] ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp {{ number_format($insight['total_financed'] ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp {{ number_format($insight['outstanding_principal'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp {{ number_format($insight['total_paid'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp {{ number_format($insight['total_penalty'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">{{ __($insight['status']) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
</x-layouts.app>
