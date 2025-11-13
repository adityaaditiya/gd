<x-layouts.app :title="__('Riwayat Cicilan')">
    <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Riwayat Cicilan') }}</h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                {{ __('Pantau status pembayaran, saldo pokok tersisa, serta nilai aset emas terbaru untuk setiap portofolio cicilan.') }}
            </p>
        </div>

        <section class="grid gap-4 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <header class="flex items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wide text-purple-500">{{ __('Ringkasan Portofolio') }}</span>
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Snapshot Kinerja Cicil Emas') }}</h2>
                </div>
                <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700 dark:bg-purple-900/40 dark:text-purple-200">
                    {{ number_format($portfolio['total_transactions'] ?? 0, 0, ',', '.') }} {{ __('transaksi') }}
                </span>
            </header>

            <dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Total Pokok Tercatat') }}</dt>
                    <dd class="text-xl font-semibold text-neutral-900 dark:text-white">Rp {{ number_format($portfolio['total_principal'] ?? 0, 0, ',', '.') }}</dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Saldo Pokok Tersisa') }}</dt>
                    <dd class="text-xl font-semibold text-amber-600 dark:text-amber-300">Rp {{ number_format($portfolio['total_outstanding'] ?? 0, 0, ',', '.') }}</dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Total Pembayaran Tercatat') }}</dt>
                    <dd class="text-xl font-semibold text-emerald-600 dark:text-emerald-300">Rp {{ number_format($portfolio['total_paid'] ?? 0, 0, ',', '.') }}</dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Rata-rata Penyelesaian') }}</dt>
                    <dd class="text-xl font-semibold text-blue-600 dark:text-blue-300">{{ number_format($portfolio['average_completion'] ?? 0, 2, ',', '.') }}%</dd>
                </div>
            </dl>

            <div class="grid gap-3 sm:grid-cols-3">
                @php
                    $statusColors = [
                        'Aktif' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-200',
                        'Menunggak' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-200',
                        'Lunas' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200',
                    ];
                @endphp
                @foreach(($portfolio['status_buckets'] ?? []) as $status => $count)
                    <div class="flex flex-col rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                        <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __($status) }}</span>
                        <div class="mt-2 flex items-end justify-between">
                            <span class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ $count }}</span>
                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusColors[$status] ?? 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200' }}">
                                {{ __('Status') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        @if(($insights->count() ?? 0) === 0)
            <div class="rounded-xl border border-dashed border-neutral-300 bg-white p-10 text-center shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <h3 class="text-lg font-semibold text-neutral-800 dark:text-white">{{ __('Belum ada riwayat cicilan.') }}</h3>
                <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-300">{{ __('Mulai dari menu Transaksi Cicil Emas untuk menyimpan simulasi dan jadwal angsuran nasabah.') }}</p>
                <a href="{{ route('cicil-emas.transaksi-emas') }}" class="mt-4 inline-flex items-center justify-center rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 dark:focus:ring-offset-neutral-900">{{ __('Buat Transaksi Baru') }}</a>
            </div>
        @else
            <div class="flex flex-col gap-6">
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
                    <article class="flex flex-col gap-6 rounded-2xl border border-neutral-200 bg-white p-6 shadow-sm transition hover:shadow-md dark:border-neutral-700 dark:bg-neutral-900">
                        <header class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                                    {{ $nasabah?->nama ?? __('Nasabah tidak ditemukan') }}
                                </h3>
                                <div class="mt-1 flex flex-wrap items-center gap-2 text-sm text-neutral-600 dark:text-neutral-300">
                                    <span>{{ __('Kode Member: :kode', ['kode' => $nasabah?->kode_member ?? '—']) }}</span>
                                    <span>•</span>
                                    <span>{{ __('Dibuat :tanggal', ['tanggal' => $transaction->created_at?->translatedFormat('d F Y H:i')]) }}</span>
                                </div>
                                <div class="mt-1 text-sm text-neutral-600 dark:text-neutral-300">
                                    {{ $barang?->nama_barang ?? $transaction->pabrikan }} · {{ number_format($transaction->berat_gram, 3, ',', '.') }} gr · {{ $transaction->kadar }}
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ __($insight['status']) }}
                                </span>
                                <div class="text-right text-sm text-neutral-500 dark:text-neutral-400">
                                    {{ __('Progres pembayaran: :progress%', ['progress' => number_format($insight['completion_ratio'], 2, ',', '.')]) }}
                                </div>
                            </div>
                        </header>

                        <section class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                            <div class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                                <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Nilai Emas Awal') }}</dt>
                                <dd class="mt-1 text-lg font-semibold text-neutral-900 dark:text-white">Rp {{ number_format($transaction->harga_emas, 0, ',', '.') }}</dd>
                            </div>
                            <div class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                                <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Nilai Emas Terkini') }}</dt>
                                <dd class="mt-1 text-lg font-semibold text-neutral-900 dark:text-white">Rp {{ number_format($insight['current_gold_value'], 0, ',', '.') }}</dd>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Δ :value', ['value' => ( $insight['gold_delta'] >= 0 ? '+' : '−') . 'Rp ' . number_format(abs($insight['gold_delta']), 0, ',', '.') ]) }}</p>
                            </div>
                            <div class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                                <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Saldo Pokok Tersisa') }}</dt>
                                <dd class="mt-1 text-lg font-semibold text-amber-600 dark:text-amber-300">Rp {{ number_format($insight['outstanding_principal'], 0, ',', '.') }}</dd>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Tenor :bulan bulan · Angsuran Rp :angsuran', ['bulan' => $transaction->tenor_bulan, 'angsuran' => number_format($transaction->besaran_angsuran, 0, ',', '.')]) }}</p>
                            </div>
                            <div class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                                <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Pembayaran Terakhir') }}</dt>
                                <dd class="mt-1 text-lg font-semibold text-neutral-900 dark:text-white">
                                    @if($insight['last_payment'])
                                        {{ $insight['last_payment']->paid_at?->translatedFormat('d M Y') }}
                                    @else
                                        {{ __('Belum ada pembayaran') }}
                                    @endif
                                </dd>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                    {{ __('Total dibayar: Rp :amount', ['amount' => number_format($insight['total_paid'], 0, ',', '.')]) }}
                                </p>
                            </div>
                        </section>

                        <section class="flex flex-col gap-3">
                            <h4 class="text-sm font-semibold text-neutral-900 dark:text-white">{{ __('Riwayat Pembayaran') }}</h4>
                            <div class="overflow-hidden rounded-lg border border-neutral-200 dark:border-neutral-700">
                                <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                                    <thead class="bg-neutral-50 dark:bg-neutral-800/70">
                                        <tr>
                                            <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Termin') }}</th>
                                            <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Jatuh Tempo') }}</th>
                                            <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Angsuran') }}</th>
                                            <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Denda') }}</th>
                                            <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300">{{ __('Status Pembayaran') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                                        @foreach($insight['installments'] as $installment)
                                            @php
                                                $paid = $installment->paid_at !== null;
                                                $isOverdue = ! $paid && $installment->due_date->lt(now()->startOfDay());
                                            @endphp
                                            <tr class="bg-white hover:bg-neutral-50 dark:bg-neutral-900 dark:hover:bg-neutral-800/60">
                                                <td class="px-4 py-3 font-medium text-neutral-800 dark:text-neutral-200">{{ __('Angsuran :sequence', ['sequence' => $installment->sequence]) }}</td>
                                                <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">{{ $installment->due_date->translatedFormat('d M Y') }}</td>
                                                <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp {{ number_format($installment->amount, 0, ',', '.') }}</td>
                                                <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp {{ number_format($installment->penalty_amount ?? 0, 0, ',', '.') }}</td>
                                                <td class="px-4 py-3">
                                                    @if($paid)
                                                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">{{ __('Lunas :tanggal', ['tanggal' => $installment->paid_at?->translatedFormat('d M Y')]) }}</span>
                                                    @elseif($isOverdue)
                                                        <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-900/30 dark:text-rose-200">{{ __('Terlambat') }}</span>
                                                    @else
                                                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-900/30 dark:text-blue-200">{{ __('Belum dibayar') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>
