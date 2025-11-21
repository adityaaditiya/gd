<x-layouts.app :title="__('Daftar Cicilan')">
    @php
        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\CicilEmasTransaction> $transactions */
        $highlightId = session('transaction_summary.transaksi_id');
        $transactionErrorId = (string) session('transaction_error_id');
    @endphp

    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Daftar Cicilan') }}</h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                {{ __('Kelola jadwal cicilan emas aktif, lengkap dengan rincian jatuh tempo dan ketentuan denda.') }}
            </p>
        </div>

        @if (session('status'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <section class="flex flex-col gap-3 rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <!-- <header class="flex flex-col gap-1">
                <span class="text-xs font-semibold uppercase tracking-wide text-emerald-500">{{ __('Menu Daftar Cicilan') }}</span>
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Penjadwalan Angsuran Otomatis') }}</h2>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Setiap transaksi cicilan yang disetujui menghasilkan jadwal angsuran terstruktur sebagai panduan penagihan.') }}
                </p>
            </header> -->

            @if ($transactions->isEmpty())
                <div class="flex flex-col items-center justify-center gap-3 rounded-lg border border-dashed border-neutral-300 p-6 text-center text-neutral-600 dark:border-neutral-600 dark:text-neutral-300">
                    <div class="space-y-1">
                        <p class="text-base font-semibold text-neutral-800 dark:text-neutral-100">{{ __('Belum ada cicilan tersimpan') }}</p>
                        <p class="text-sm">{{ __('Simulasi yang Anda simpan melalui menu Transaksi Cicil Emas akan muncul di sini secara otomatis.') }}</p>
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
                    <div class="w-full overflow-x-auto">
                        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                        <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left">{{ __('Tanggal') }}</th>
                                <th scope="col" class="px-4 py-3 text-center">{{ __('Jatuh Tempo Terdekat') }}</th>
                                <th scope="col" class="px-4 py-3 text-center">{{ __('Nomor Cicilan') }}</th>
                                <th scope="col" class="px-4 py-3 text-left">{{ __('Nasabah') }}</th>
                                <th scope="col" class="px-4 py-3 text-left">{{ __('Detail Barang') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('Harga') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('Uang Muka') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('Margin') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('Administrasi') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('Angsuran / Bln') }}</th>
                                <th scope="col" class="px-4 py-3 text-center">{{ __('Tenor') }}</th>
                                <th scope="col" class="px-4 py-3 text-center">{{ __('Status') }}</th>
                                <th scope="col" class="px-4 py-3 text-left">{{ __('Pembatalan') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 bg-white text-sm dark:divide-neutral-700 dark:bg-neutral-900">
                            @foreach ($transactions as $transaction)
                                @php
                                    $isHighlighted = (string) $highlightId === (string) $transaction->id;
                                    $nearestInstallment = $transaction->relationLoaded('installments')
                                        ? $transaction->installments
                                            ->filter(fn ($installment) => $installment->paid_at === null)
                                            ->sortBy('due_date')
                                            ->first()
                                        : null;

                                    $hasPendingInstallment = $nearestInstallment !== null;
                                    $nearestDueDate = $nearestInstallment?->due_date;
                                    $isOverdue = $nearestDueDate ? $nearestDueDate->isPast() : false;

                                    if (! $hasPendingInstallment && $transaction->relationLoaded('installments')) {
                                        $allInstallmentsPaid = $transaction->installments->isNotEmpty()
                                            && $transaction->installments->every(fn ($installment) => $installment->paid_at !== null);
                                    } else {
                                        $allInstallmentsPaid = false;
                                    }

                                    $status = $transaction->status;
                                    $isCancelled = $status === \App\Models\CicilEmasTransaction::STATUS_CANCELLED;
                                    $isSettled = $status === \App\Models\CicilEmasTransaction::STATUS_SETTLED;
                                    $totalPaidAmount = $transaction->relationLoaded('installments')
                                        ? $transaction->installments->sum(fn ($installment) => (float) ($installment->paid_amount ?? 0))
                                        : 0;

                                    $statusBadge = [
                                        'label' => __('Aktif'),
                                        'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300',
                                    ];

                                    if ($isCancelled) {
                                        $statusBadge = [
                                            'label' => __('Batal'),
                                            'class' => 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-300',
                                        ];
                                    } elseif ($isSettled) {
                                        $statusBadge = [
                                            'label' => __('Lunas'),
                                            'class' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/10 dark:text-sky-300',
                                        ];
                                    }

                                    $formTextareaValue = $transactionErrorId === (string) $transaction->id ? old('alasan_batal') : '';
                                @endphp
                                <tr @class([
                                    'bg-emerald-50/60 dark:bg-emerald-500/10' => $isHighlighted,
                                    'opacity-70 dark:opacity-60' => $isCancelled,
                                ])>
                                    <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-neutral-900 dark:text-white">{{ optional($transaction->created_at)->translatedFormat('d M Y') }}</span>
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ optional($transaction->created_at)->format('H:i') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        @if ($hasPendingInstallment && $nearestDueDate)
                                            <div class="flex flex-col">
                                                <span @class([
                                                    'font-semibold text-neutral-900 dark:text-white' => ! $isOverdue,
                                                    'font-semibold text-red-600 dark:text-red-400' => $isOverdue,
                                                ])>
                                                    {{ $nearestDueDate->translatedFormat('d M Y') }}
                                                </span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">
                                                    {{ __('Cicilan ke-:sequence', ['sequence' => $nearestInstallment->sequence]) }}
                                                </span>
                                            </div>
                                        @elseif ($allInstallmentsPaid)
                                            <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-300">{{ __('Lunas') }}</span>
                                        @else
                                            <span class="text-sm font-semibold text-neutral-500 dark:text-neutral-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">
                                        @if ($transaction->nomor_cicilan)
                                            <span class="font-mono text-sm font-semibold text-neutral-900 dark:text-white">{{ $transaction->nomor_cicilan }}</span>
                                        @else
                                            <span class="text-sm font-semibold text-neutral-500 dark:text-neutral-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-neutral-900 dark:text-white">{{ $transaction->nasabah->nama ?? __('Tidak diketahui') }}</span>
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ $transaction->nasabah->telepon ?? __('Tidak diketahui') }}</span></span>
                                            @if ($transaction->nasabah && $transaction->nasabah->kode_member)
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Kode Member: :kode', ['kode' => $transaction->nasabah->kode_member]) }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">
                                        <div class="flex flex-col">
                                            @php
                                                $items = $transaction->relationLoaded('items') ? $transaction->items : collect();
                                            @endphp
                                            @if ($items->count() === 1)
                                                @php
                                                    $item = $items->first();
                                                @endphp
                                                <span class="font-semibold text-neutral-900 dark:text-white">{{ $item->nama_barang ?? $transaction->pabrikan }}</span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ number_format((float) ($item->berat ?? $transaction->berat_gram), 3, ',', '.') }} gr • {{ $item->kode_barcode ?? $item->kode_intern ?? $transaction->kadar }} • {{ $item->kode_intern ?? $item->kode_intern ?? '—' }}</span>
                                            @elseif ($items->count() > 1)
                                                <span class="font-semibold text-neutral-900 dark:text-white">{{ __(':count barang', ['count' => $items->count()]) }}</span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ number_format((float) $transaction->berat_gram, 3, ',', '.') }} gr </span>
                                                <ul class="mt-1 list-disc space-y-1 ps-4 text-[11px] text-neutral-500 dark:text-neutral-400">
                                                    @foreach ($items->take(3) as $item)
                                                        <li>{{ $item->nama_barang }} • {{ number_format((float) ($item->berat ?? 0), 3, ',', '.') }} gr • {{ $item->kode_barcode ?? $item->kode_intern ?? '—' }} • {{ $item->kode_intern ?? $item->kode_intern ?? '—' }}</li>
                                                    @endforeach
                                                    @if ($items->count() > 3)
                                                        <li>+ {{ $items->count() - 3 }} {{ __('barang lainnya') }}</li>
                                                    @endif
                                                </ul>
                                            @else
                                                <span class="font-semibold text-neutral-900 dark:text-white">{{ $transaction->pabrikan }}</span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ number_format((float) $transaction->berat_gram, 3, ',', '.') }} gr • {{ $transaction->kadar }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top text-right text-neutral-700 dark:text-neutral-200">
                                        {{ number_format((float) $transaction->harga_emas, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 align-top text-right text-neutral-700 dark:text-neutral-200">
                                        <div class="flex flex-col items-end">
                                            <span class="font-semibold text-neutral-900 dark:text-white">{{ number_format((float) $transaction->estimasi_uang_muka, 0, ',', '.') }}</span>
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ number_format((float) $transaction->dp_percentage, 2, ',', '.') }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top text-right text-neutral-700 dark:text-neutral-200">
                                        <div class="flex flex-col items-end">
                                            <span class="font-semibold text-neutral-900 dark:text-white">{{ number_format((float) $transaction->margin_amount, 0, ',', '.') }}</span>
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ number_format((float) $transaction->margin_percentage, 2, ',', '.') }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top text-right text-neutral-700 dark:text-neutral-200">
                                        {{ number_format((float) $transaction->administrasi, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 align-top text-right text-neutral-700 dark:text-neutral-200">
                                        {{ number_format((float) $transaction->besaran_angsuran, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 align-top text-center text-neutral-700 dark:text-neutral-200">
                                        <span class="inline-flex rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">{{ $transaction->tenor_bulan }} {{ __('Bulan') }}</span>
                                    </td>
                                    <td class="px-4 py-3 align-top text-center text-neutral-700 dark:text-neutral-200">
                                        <span @class([
                                            'inline-flex items-center justify-center rounded-full px-3 py-1 text-xs font-semibold',
                                            $statusBadge['class'],
                                        ])>
                                            {{ $statusBadge['label'] }}
                                        </span>
                                        @if ($totalPaidAmount > 0)
                                            <div class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                                                {{ __('Terbayar: :amount', ['amount' => number_format($totalPaidAmount, 0, ',', '.')]) }}
                                            </div>
                                        @endif
                                        @if ($isCancelled && $transaction->cancelled_at)
                                            <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                                {{ __('Dibatalkan :date', ['date' => optional($transaction->cancelled_at)->translatedFormat('d M Y H:i')]) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">
                                        @if ($transaction->isCancelable())
                                            <form method="POST" action="{{ route('cicil-emas.transaksi.cancel', $transaction) }}" class="flex flex-col gap-2">
                                                @csrf
                                                <!-- <label for="alasan-batal-{{ $transaction->id }}" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                                                    {{ __('Alasan Pembatalan') }}
                                                </label> -->
                                                <textarea
                                                    id="alasan-batal-{{ $transaction->id }}"
                                                    name="alasan_batal"
                                                    rows="2"
                                                    required
                                                    placeholder="{{ __('Alasan Pembatalan') }}"
                                                    class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100"
                                                >{{ $formTextareaValue }}</textarea>
                                                @if ($transactionErrorId === (string) $transaction->id)
                                                    @error('alasan_batal')
                                                        <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                                    @enderror
                                                @endif
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-red-500 px-3 py-2 text-sm font-semibold text-white shadow hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500/40"
                                                    onclick="return confirm('{{ __('Batalkan transaksi ini? Pembayaran yang sudah masuk perlu direkonsiliasi manual.') }}');"
                                                >
                                                    {{ __('Batalkan Transaksi') }}
                                                </button>
                                            </form>
                                        @else
                                            <div class="flex flex-col gap-1 text-xs text-neutral-500 dark:text-neutral-400">
                                                @if ($isCancelled && $transaction->cancellation_reason)
                                                    <span>{{ __('Alasan: :reason', ['reason' => $transaction->cancellation_reason]) }}</span>
                                                @endif
                                                @if ($totalPaidAmount > 0)
                                                    <span>{{ __('Total angsuran tercatat :amount', ['amount' => number_format($totalPaidAmount, 0, ',', '.')]) }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex flex-col gap-4 border border-t-0 border-neutral-200 bg-white p-4 text-sm dark:border-neutral-700 dark:bg-neutral-900 sm:flex-row sm:items-center sm:justify-between">
                    <form method="GET" action="{{ route('cicil-emas.daftar-cicilan') }}" class="flex flex-col gap-2 sm:flex-row sm:items-center">
                        @foreach (request()->except(['per_page', 'page']) as $name => $value)
                            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                        @endforeach
                        <label for="rows-per-page" class="text-xm font-semibold tracking-wide text-neutral-500 dark:text-neutral-400">
                            {{ __('Rows per page') }}
                            <span class="ms-1 inline-flex items-center rounded-full bg-neutral-200 px-2 py-0.5 text-[11px] font-semibold text-neutral-700 dark:bg-neutral-700 dark:text-neutral-200">{{ $perPage }}</span>
                        </label>
                        <select
                            id="rows-per-page"
                            name="per_page"
                            class="flex items-center gap-3 rounded-lg border border-neutral-300 bg-white px-1 py-1 text-sm text-neutral-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 sm:w-32"
                            onchange="this.form.submit()"
                        >
                            @foreach ($perPageOptions as $option)
                                <option value="{{ $option }}" @selected($option === $perPage)>{{ $option }}</option>
                            @endforeach
                        </select>
                    </form>

                    @php
                        $currentPage = $transactions->currentPage();
                        $lastPage = $transactions->lastPage();
                    @endphp

                    <nav class="flex flex-wrap items-center gap-2" aria-label="{{ __('Pagination') }}">
                        <a
                            href="{{ $transactions->url(1) }}"
                            @class([
                                'inline-flex items-center rounded-lg border px-3 py-1.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500/40',
                                'border-neutral-300 text-neutral-600 hover:bg-neutral-100 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-800' => ! $transactions->onFirstPage(),
                                'pointer-events-none border-neutral-300 text-neutral-400 opacity-60 dark:border-neutral-600 dark:text-neutral-500' => $transactions->onFirstPage(),
                            ])
                            @if ($transactions->onFirstPage()) aria-disabled="true" tabindex="-1" @endif
                        >
                            &laquo; {{ __('First') }}
                        </a>
                        <a
                            href="{{ $transactions->previousPageUrl() ?? $transactions->url(1) }}"
                            @class([
                                'inline-flex items-center rounded-lg border px-3 py-1.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500/40',
                                'border-neutral-300 text-neutral-600 hover:bg-neutral-100 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-800' => ! $transactions->onFirstPage(),
                                'pointer-events-none border-neutral-300 text-neutral-400 opacity-60 dark:border-neutral-600 dark:text-neutral-500' => $transactions->onFirstPage(),
                            ])
                            @if ($transactions->onFirstPage()) aria-disabled="true" tabindex="-1" @endif
                        >
                            &lt; {{ __('Back') }}
                        </a>

                        @for ($page = 1; $page <= $lastPage; $page++)
                            @php
                                $isActive = $page === $currentPage;
                            @endphp
                            <a
                                href="{{ $transactions->url($page) }}"
                                @class([
                                    'inline-flex items-center justify-center rounded-lg border px-3 py-1.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500/40',
                                    'border-neutral-900 bg-neutral-900 text-white dark:border-white dark:bg-white dark:text-neutral-900' => $isActive,
                                    'border-neutral-300 text-neutral-600 hover:bg-neutral-100 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-800' => ! $isActive,
                                ])
                                @if ($isActive) aria-current="page" @endif
                            >
                                {{ $page }}
                            </a>
                        @endfor

                        <a
                            href="{{ $transactions->nextPageUrl() ?? $transactions->url($lastPage) }}"
                            @class([
                                'inline-flex items-center rounded-lg border px-3 py-1.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500/40',
                                'border-neutral-300 text-neutral-600 hover:bg-neutral-100 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-800' => $currentPage < $lastPage,
                                'pointer-events-none border-neutral-300 text-neutral-400 opacity-60 dark:border-neutral-600 dark:text-neutral-500' => $currentPage >= $lastPage,
                            ])
                            @if ($currentPage >= $lastPage) aria-disabled="true" tabindex="-1" @endif
                        >
                            {{ __('Next') }} &gt;
                        </a>
                        <a
                            href="{{ $transactions->url($lastPage) }}"
                            @class([
                                'inline-flex items-center rounded-lg border px-3 py-1.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500/40',
                                'border-neutral-300 text-neutral-600 hover:bg-neutral-100 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-800' => $currentPage < $lastPage,
                                'pointer-events-none border-neutral-300 text-neutral-400 opacity-60 dark:border-neutral-600 dark:text-neutral-500' => $currentPage >= $lastPage,
                            ])
                            @if ($currentPage >= $lastPage) aria-disabled="true" tabindex="-1" @endif
                        >
                            {{ __('Last') }} &raquo;
                        </a>
                    </nav>
                </div>
            @endif
        </section>
    </div>

</x-layouts.app>
