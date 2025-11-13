<x-layouts.app :title="__('Angsuran Rutin')">
    @php
        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\CicilEmasInstallment> $installments */
        $today = \Illuminate\Support\Carbon::now();
    @endphp

    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Angsuran Rutin') }}</h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                {{ __('Kelola proses penagihan bulanan, validasi pembayaran, dan hitung denda keterlambatan secara real-time.') }}
            </p>
        </div>

        @if (session('status'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <section class="flex flex-col gap-3 rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <header class="flex flex-col gap-1">
                <span class="text-xs font-semibold uppercase tracking-wide text-sky-500">{{ __('Menu Angsuran Rutin') }}</span>
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Proses Pembayaran Terjadwal') }}</h2>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Pastikan setiap jatuh tempo tercatat, pembayaran tervalidasi, dan denda dihitung otomatis bila terjadi keterlambatan.') }}
                </p>
                <p class="text-xs text-neutral-500 dark:text-neutral-400">
                    {{ __('Ketentuan denda: :rate% per hari dari nominal angsuran.', ['rate' => number_format($lateFeePercentagePerDay, 2, ',', '.')]) }}
                </p>
            </header>

            @if ($installments->isEmpty())
                <div class="flex flex-col items-center justify-center gap-3 rounded-lg border border-dashed border-neutral-300 p-6 text-center text-neutral-600 dark:border-neutral-600 dark:text-neutral-300">
                    <div class="space-y-1">
                        <p class="text-base font-semibold text-neutral-800 dark:text-neutral-100">{{ __('Belum ada jadwal angsuran') }}</p>
                        <p class="text-sm">{{ __('Simpan simulasi cicilan melalui menu Transaksi Cicil Emas untuk menghasilkan jadwal angsuran otomatis.') }}</p>
                    </div>
                    <a
                        href="{{ route('cicil-emas.transaksi-emas') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/40"
                    >
                        {{ __('Buat Simulasi Cicilan') }}
                    </a>
                </div>
            @else
                <div class="overflow-hidden rounded-lg border border-neutral-200 shadow-sm dark:border-neutral-700">
                    <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                        <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left">{{ __('Jatuh Tempo') }}</th>
                                <th scope="col" class="px-4 py-3 text-left">{{ __('Nasabah & Paket') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('Angsuran') }}</th>
                                <th scope="col" class="px-4 py-3 text-center">{{ __('Status') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('Denda') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('Tindakan') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 bg-white text-sm dark:divide-neutral-700 dark:bg-neutral-900">
                            @foreach ($installments as $installment)
                                @php
                                    $transaction = $installment->transaction;
                                    $nasabah = $transaction?->nasabah;
                                    $isPaid = filled($installment->paid_at);
                                    $dueDate = optional($installment->due_date);
                                    $paidAt = optional($installment->paid_at);
                                    $penaltyRate = $installment->penalty_rate ?: $lateFeePercentagePerDay;
                                    $daysLate = $isPaid
                                        ? max(0, $installment->due_date->diffInDays($installment->paid_at, false))
                                        : ($installment->due_date->isPast() ? $installment->due_date->diffInDays($today) : 0);
                                    $penaltyAmount = $isPaid
                                        ? $installment->penalty_amount
                                        : round($installment->amount * ($penaltyRate / 100) * $daysLate, 2);
                                @endphp
                                <tr>
                                    <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-neutral-900 dark:text-white">{{ $dueDate?->translatedFormat('d M Y') }}</span>
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Angsuran ke-:ke', ['ke' => $installment->sequence]) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-neutral-900 dark:text-white">{{ $nasabah->nama ?? __('Tidak diketahui') }}</span>
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400">
                                                {{ $transaction?->pabrikan }} • {{ number_format((float) ($transaction?->berat_gram ?? 0), 3, ',', '.') }} gr
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top text-right text-neutral-700 dark:text-neutral-200">
                                        {{ number_format((float) $installment->amount, 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 align-top text-center text-neutral-700 dark:text-neutral-200">
                                        @if ($isPaid)
                                            <div class="inline-flex flex-col items-center gap-1">
                                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-200">
                                                    {{ __('Lunas') }}
                                                </span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Dibayar :tanggal', ['tanggal' => $paidAt?->translatedFormat('d M Y')]) }}</span>
                                            </div>
                                        @else
                                            <div class="inline-flex flex-col items-center gap-1">
                                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/20 dark:text-amber-200">
                                                    {{ $installment->due_date->isPast() ? __('Terlambat') : __('Menunggu') }}
                                                </span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Jatuh tempo :tanggal', ['tanggal' => $dueDate?->translatedFormat('d M Y')]) }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 align-top text-right text-neutral-700 dark:text-neutral-200">
                                        <div class="flex flex-col items-end">
                                            <span class="font-semibold text-neutral-900 dark:text-white">{{ number_format((float) $penaltyAmount, 2, ',', '.') }}</span>
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ __(':hari hari • :rate%/hari', ['hari' => $daysLate, 'rate' => number_format($penaltyRate, 2, ',', '.')]) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top text-right text-neutral-700 dark:text-neutral-200">
                                        @if ($isPaid)
                                            <span class="text-xs text-neutral-400 dark:text-neutral-500">{{ __('Tidak ada tindakan') }}</span>
                                        @else
                                            <form method="POST" action="{{ route('cicil-emas.angsuran-rutin.pay', $installment) }}" class="flex flex-col items-end gap-2 text-xs">
                                                @csrf
                                                <label class="flex items-center gap-2">
                                                    <span class="text-neutral-500 dark:text-neutral-400">{{ __('Tanggal Bayar') }}</span>
                                                    <input
                                                        type="date"
                                                        name="payment_date"
                                                        value="{{ $today->format('Y-m-d') }}"
                                                        class="rounded-md border border-neutral-300 px-2 py-1 text-sm text-neutral-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100"
                                                        required
                                                    >
                                                </label>
                                                <label class="flex items-center gap-2">
                                                    <span class="text-neutral-500 dark:text-neutral-400">{{ __('Nominal Bayar') }}</span>
                                                    <input
                                                        type="number"
                                                        step="0.01"
                                                        min="0"
                                                        name="paid_amount"
                                                        value="{{ number_format((float) $installment->amount, 2, '.', '') }}"
                                                        class="w-28 rounded-md border border-neutral-300 px-2 py-1 text-right text-sm text-neutral-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100"
                                                        required
                                                    >
                                                </label>
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-500 px-3 py-1.5 text-sm font-semibold text-white shadow hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/40"
                                                >
                                                    {{ __('Catat Pembayaran') }}
                                                </button>
                                            </form>
                                        @endif
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
