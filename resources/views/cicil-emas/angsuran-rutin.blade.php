<x-layouts.app :title="__('Angsuran Rutin')">
    @php
        /** @var \Illuminate\Pagination\LengthAwarePaginator<\App\Models\CicilEmasInstallment> $installments */
        $today = $today ?? \Illuminate\Support\Carbon::now();
        $filters = $filters ?? [];
        $hasFilters = $hasFilters ?? false;
        $isDefaultingToToday = $isDefaultingToToday ?? false;
        $statusOptions = [
            'paid' => __('Lunas'),
            'overdue' => __('Terlambat'),
            'upcoming' => __('Menunggu'),
        ];
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

        @if (session('error'))
            <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:border-rose-500/40 dark:bg-rose-500/10 dark:text-rose-200">
                {{ session('error') }}
            </div>
        @endif

        <section class="flex flex-col gap-4 rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <header class="flex flex-col gap-1">
                <!-- <span class="text-xs font-semibold uppercase tracking-wide text-sky-500">{{ __('Menu Angsuran Rutin') }}</span>
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Proses Pembayaran Terjadwal') }}</h2>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Pastikan setiap jatuh tempo tercatat, pembayaran tervalidasi, dan denda dihitung otomatis bila terjadi keterlambatan.') }}
                </p> -->
                <p class="text-xs text-neutral-500 dark:text-neutral-400">
                    {{ __('Ketentuan denda: :rate% per hari dari nominal angsuran.', ['rate' => number_format($lateFeePercentagePerDay, 2, ',', '.')]) }}
                </p>
            </header>

            <form method="GET" class="grid gap-3 rounded-lg border border-neutral-200 bg-neutral-50 p-4 text-sm dark:border-neutral-700 dark:bg-neutral-800/60 md:grid-cols-5">
                <div class="md:col-span-2">
                    <label class="flex flex-col gap-1">
                        <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Cari Nomor Cicilan / Nasabah / Paket') }}</span>
                        <input
                            type="search"
                            name="search"
                            value="{{ $filters['search'] ?? '' }}"
                            placeholder="{{ __('Nomor cicilan, nama, kode member, NIK, atau paket cicilan') }}"
                            class="rounded-md border border-neutral-300 px-3 py-2 text-sm text-neutral-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100"
                        >
                    </label>
                </div>
                <div>
                    <label class="flex flex-col gap-1">
                        <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Status Pembayaran') }}</span>
                        <select
                            name="status"
                            class="rounded-md border border-neutral-300 px-3 py-2 text-sm text-neutral-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100"
                        >
                            <option value="">{{ __('Semua status') }}</option>
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <div>
                    <label class="flex flex-col gap-1">
                        <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Jatuh Tempo Dari') }}</span>
                        <input
                            type="date"
                            name="due_from"
                            value="{{ $filters['due_from'] ?? '' }}"
                            class="rounded-md border border-neutral-300 px-3 py-2 text-sm text-neutral-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100"
                        >
                    </label>
                </div>
                <div>
                    <label class="flex flex-col gap-1">
                        <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Jatuh Tempo Hingga') }}</span>
                        <input
                            type="date"
                            name="due_until"
                            value="{{ $filters['due_until'] ?? '' }}"
                            class="rounded-md border border-neutral-300 px-3 py-2 text-sm text-neutral-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100"
                        >
                    </label>
                </div>
                <div class="flex items-end gap-2 md:justify-end">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-md bg-sky-500 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500/40"
                    >
                        {{ __('Terapkan Filter') }}
                    </button>
                    @if ($hasFilters)
                        <a
                            href="{{ route('cicil-emas.angsuran-rutin') }}"
                            class="inline-flex items-center justify-center rounded-md border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-600 hover:text-neutral-800 focus:outline-none focus:ring-2 focus:ring-neutral-300/60 dark:border-neutral-600 dark:text-neutral-300 dark:hover:text-white"
                        >
                            {{ __('Atur Ulang') }}
                        </a>
                    @endif
                </div>
            </form>

            @if ($installments->isEmpty())
                <div class="flex flex-col items-center justify-center gap-3 rounded-lg border border-dashed border-neutral-300 p-6 text-center text-neutral-600 dark:border-neutral-600 dark:text-neutral-300">
                    @if ($hasFilters)
                        <div class="space-y-1">
                            <p class="text-base font-semibold text-neutral-800 dark:text-neutral-100">{{ __('Tidak ada angsuran yang cocok') }}</p>
                            <p class="text-sm">{{ __('Coba ubah kata kunci pencarian, rentang jatuh tempo, atau pilih status lain untuk melihat jadwal angsuran yang tersedia.') }}</p>
                        </div>
                        <a
                            href="{{ route('cicil-emas.angsuran-rutin') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-600 hover:text-neutral-800 focus:outline-none focus:ring-2 focus:ring-neutral-300/60 dark:border-neutral-600 dark:text-neutral-300 dark:hover:text-white"
                        >
                            {{ __('Bersihkan Filter') }}
                        </a>
                    @elseif ($isDefaultingToToday)
                        <div class="space-y-1">
                            <p class="text-base font-semibold text-neutral-800 dark:text-neutral-100">{{ __('Tidak ada angsuran jatuh tempo hari ini') }}</p>
                            <p class="text-sm">{{ __('Periksa kembali nanti atau terapkan filter untuk meninjau jadwal angsuran lainnya.') }}</p>
                        </div>
                    @else
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
                    @endif
                </div>
            @else
                <div class="flex flex-wrap items-center justify-between gap-2 rounded-lg border border-neutral-200 bg-neutral-50 px-4 py-2 text-xs font-medium text-neutral-600 dark:border-neutral-700 dark:bg-neutral-800/60 dark:text-neutral-300">
                    <div class="flex flex-col gap-1 text-left md:flex-row md:items-center md:gap-3">
                        <span>
                            {{ __('Menampilkan :from-:to dari :total angsuran', [
                                'from' => number_format($installments->firstItem(), 0, ',', '.'),
                                'to' => number_format($installments->lastItem(), 0, ',', '.'),
                                'total' => number_format($installments->total(), 0, ',', '.'),
                            ]) }}
                        </span>
                        @if ($isDefaultingToToday)
                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-3 py-1 text-amber-700 dark:bg-amber-500/20 dark:text-amber-200">
                                {{ __('Fokus pada jatuh tempo hari ini (:date)', ['date' => $today->translatedFormat('d M Y')]) }}
                            </span>
                        @endif
                    </div>
                    @if ($hasFilters)
                        <span class="inline-flex items-center gap-2 rounded-full bg-sky-100 px-3 py-1 text-sky-700 dark:bg-sky-900/40 dark:text-sky-200">
                            {{ __('Filter aktif') }}
                            <a href="{{ route('cicil-emas.angsuran-rutin') }}" class="text-xs font-semibold underline hover:text-sky-500 dark:hover:text-sky-100">{{ __('Hapus') }}</a>
                        </span>
                    @endif
                </div>

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
                                    $lastSequence = $transaction?->relationLoaded('installments')
                                        ? $transaction->installments->max('sequence')
                                        : null;
                                    $isLastInstallment = $lastSequence !== null && (int) $installment->sequence === (int) $lastSequence;
                                    $daysLate = $isPaid
                                        ? max(0, $installment->due_date->diffInDays($installment->paid_at, false))
                                        : ($installment->due_date->isPast() ? $installment->due_date->diffInDays($today) : 0);
                                    $penaltyAmount = $isPaid
                                        ? $installment->penalty_amount
                                        : round($installment->amount * ($penaltyRate / 100) * $daysLate, 2);
                                    $pendingPreviousInstallment = null;

                                    if ($transaction && $transaction->relationLoaded('installments')) {
                                        $pendingPreviousInstallment = $transaction->installments
                                            ->sortBy('sequence')
                                            ->first(function ($related) use ($installment) {
                                                return $related->sequence < $installment->sequence && blank($related->paid_at);
                                            });
                                    }

                                    $canRecordPayment = ! $isPaid && $pendingPreviousInstallment === null;
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
                                            @php
                                                $items = $transaction?->relationLoaded('items') ? $transaction->items : collect();
                                            @endphp
                                            @if ($items->count() === 1)
                                                @php $item = $items->first(); @endphp
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">
                                                    {{ $item->nama_barang ?? $transaction?->pabrikan }} •
                                                    {{ number_format((float) ($item->berat ?? $transaction?->berat_gram ?? 0), 3, ',', '.') }} gr •
                                                    {{ $item->kode_baki ?? $item->kode_intern ?? $transaction?->kadar }}
                                                </span>
                                            @elseif ($items->count() > 1)
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">
                                                    {{ __(':count barang', ['count' => $items->count()]) }} •
                                                    {{ number_format((float) ($transaction?->berat_gram ?? 0), 3, ',', '.') }} gr •
                                                    {{ $transaction?->kadar }}
                                                </span>
                                                <ul class="mt-1 list-disc space-y-1 ps-4 text-[11px] text-neutral-500 dark:text-neutral-400">
                                                    @foreach ($items->take(3) as $item)
                                                        <li>{{ $item->nama_barang }} • {{ number_format((float) ($item->berat ?? 0), 3, ',', '.') }} gr • {{ $item->kode_baki ?? $item->kode_intern ?? '—' }}</li>
                                                    @endforeach
                                                    @if ($items->count() > 3)
                                                        <li>+ {{ $items->count() - 3 }} {{ __('barang lainnya') }}</li>
                                                    @endif
                                                </ul>
                                            @else
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">
                                                    {{ $transaction?->pabrikan }} • {{ number_format((float) ($transaction?->berat_gram ?? 0), 3, ',', '.') }} gr
                                                </span>
                                            @endif
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400">
                                                {{ __('Administrasi: Rp :amount', ['amount' => number_format((float) ($transaction?->administrasi ?? 0), 0, ',', '.')]) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top text-right text-neutral-700 dark:text-neutral-200">
                                        {{ number_format((float) $installment->amount, 0, ',', '.') }}
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
                                            <span class="font-semibold text-neutral-900 dark:text-white">{{ number_format((float) $penaltyAmount, 0, ',', '.') }}</span>
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ __(':hari hari • :rate%/hari', ['hari' => $daysLate, 'rate' => number_format($penaltyRate, 2, ',', '.')]) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top text-right text-neutral-700 dark:text-neutral-200">
                                        @if ($isPaid)
                                            <form
                                                method="POST"
                                                action="{{ route('cicil-emas.angsuran-rutin.cancel', array_merge(['installment' => $installment], request()->query())) }}"
                                                class="flex flex-col items-end gap-2 text-xs"
                                                onsubmit="return confirm('{{ __('Batalkan pembayaran angsuran ini?') }}')"
                                            >
                                                @csrf
                                                <span class="text-neutral-500 dark:text-neutral-400">{{ __('Pembayaran tercatat :tanggal', ['tanggal' => $paidAt?->translatedFormat('d M Y')]) }}</span>
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-rose-500 px-3 py-1.5 text-sm font-semibold text-white shadow hover:bg-rose-600 focus:outline-none focus:ring-2 focus:ring-rose-500/40"
                                                >
                                                    {{ __('Batalkan Pembayaran') }}
                                                </button>
                                            </form>
                                        @elseif (! $canRecordPayment)
                                            <div class="flex max-w-xs flex-col items-end gap-1 text-right">
                                                <!-- <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/20 dark:text-amber-200">
                                                    {{ __('Menunggu Angsuran Sebelumnya') }}
                                                </span> -->
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">
                                                    {{ __('Selesaikan angsuran ke-:sequence terlebih dahulu sebelum mencatat pembayaran ini.', ['sequence' => $pendingPreviousInstallment?->sequence]) }}
                                                </span>
                                            </div>
                                        @else
                                            @if ($isLastInstallment)
                                                <a
                                                    href="{{ route('cicil-emas.pelunasan-cicilan', ['search' => $transaction?->nomor_cicilan]) }}"
                                                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-500 px-3 py-1.5 text-sm font-semibold text-white shadow hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/40"
                                                >
                                                    {{ __('Pelunasan Cicilan') }}
                                                </a>
                                            @else
                                                <form method="POST" action="{{ route('cicil-emas.angsuran-rutin.pay', array_merge(['installment' => $installment], request()->query())) }}" class="flex flex-col items-end gap-2 text-xs">
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
                                                            step="1"
                                                            min="0"
                                                            name="paid_amount"
                                                            value="{{ number_format((float) $installment->amount, 0, '.', '') }}"
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
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-end">
                    {{ $installments->onEachSide(1)->links() }}
                </div>
            @endif
        </section>
    </div>
</x-layouts.app>
