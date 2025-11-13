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
                    <dd class="text-xl font-semibold text-neutral-900 dark:text-black">Rp {{ number_format($portfolio['total_principal'] ?? 0, 0, ',', '.') }}</dd>
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
    </div>
    
</x-layouts.app>
