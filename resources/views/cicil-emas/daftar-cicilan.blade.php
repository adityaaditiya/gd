<x-layouts.app :title="__('Daftar Cicilan')">
    @php
        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\CicilEmasTransaction> $transactions */
        $highlightId = session('transaction_summary.transaksi_id');
        $pendingCancelId = old('transaction_id');
        $pendingCancelReason = old('alasan_pembatalan');
        $transactionsById = $transactions->keyBy('id');
        $pendingCancelSummary = '';

        if ($pendingCancelId && $transactionsById->has((int) $pendingCancelId)) {
            $pendingTransaction = $transactionsById->get((int) $pendingCancelId);
            $nasabahName = $pendingTransaction->nasabah?->nama ?? __('Nasabah tidak diketahui');
            $packageLabel = $pendingTransaction->option_label
                ?? ($pendingTransaction->items->count() === 1
                    ? ($pendingTransaction->items->first()->nama_barang ?? $pendingTransaction->pabrikan)
                    : ($pendingTransaction->items->count() > 1
                        ? __(':count barang', ['count' => $pendingTransaction->items->count()])
                        : $pendingTransaction->pabrikan));

            $pendingCancelSummary = __('Cicilan :nasabah • :paket', [
                'nasabah' => $nasabahName,
                'paket' => $packageLabel,
            ]);
        }
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
            <header class="flex flex-col gap-1">
                <span class="text-xs font-semibold uppercase tracking-wide text-emerald-500">{{ __('Menu Daftar Cicilan') }}</span>
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Penjadwalan Angsuran Otomatis') }}</h2>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Setiap transaksi cicilan yang disetujui menghasilkan jadwal angsuran terstruktur sebagai panduan penagihan.') }}
                </p>
            </header>

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
                    <table
                        id="cicilan-transactions-table"
                        data-cicilan-table
                        class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700"
                    >
                        <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left">{{ __('Tanggal') }}</th>
                                <th scope="col" class="px-4 py-3 text-left">{{ __('Jatuh Tempo Terdekat') }}</th>
                                <th scope="col" class="px-4 py-3 text-left">{{ __('Nasabah') }}</th>
                                <th scope="col" class="px-4 py-3 text-left">{{ __('Paket Emas') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('Harga') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('Uang Muka') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('Margin') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('Administrasi') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('Angsuran / Bln') }}</th>
                                <th scope="col" class="px-4 py-3 text-center">{{ __('Tenor') }}</th>
                                <th scope="col" class="px-4 py-3 text-center">{{ __('Aksi') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 bg-white text-sm dark:divide-neutral-700 dark:bg-neutral-900">
                            @foreach ($transactions as $transaction)
                                @php
                                    $isHighlighted = (string) $highlightId === (string) $transaction->id;
                                    $isCancelled = $transaction->dibatalkan_pada !== null;
                                    $nearestInstallment = $transaction->relationLoaded('installments')
                                        ? $transaction->installments
                                            ->filter(fn ($installment) => $installment->paid_at === null)
                                            ->sortBy('due_date')
                                            ->first()
                                        : null;

                                    $hasPendingInstallment = $nearestInstallment !== null;
                                    $nearestDueDate = $nearestInstallment?->due_date;
                                    $isOverdue = $nearestDueDate ? $nearestDueDate->isPast() : false;

                                    if (! $hasPendingInstallment && $transaction->relationLoaded('installments') && ! $isCancelled) {
                                        $allInstallmentsPaid = $transaction->installments->isNotEmpty()
                                            && $transaction->installments->every(fn ($installment) => $installment->paid_at !== null);
                                    } else {
                                        $allInstallmentsPaid = false;
                                    }

                                    $items = $transaction->relationLoaded('items') ? $transaction->items : collect();
                                    $nasabahName = $transaction->nasabah->nama ?? __('Tidak diketahui');
                                    $packageLabel = $transaction->option_label
                                        ?? ($items->count() === 1
                                            ? ($items->first()->nama_barang ?? $transaction->pabrikan)
                                            : ($items->count() > 1
                                                ? __(':count barang', ['count' => $items->count()])
                                                : $transaction->pabrikan));
                                    $cancelSummary = __('Cicilan :nasabah • :paket', [
                                        'nasabah' => $nasabahName,
                                        'paket' => $packageLabel,
                                    ]);
                                    $canCancel = ! $isCancelled && ! $allInstallmentsPaid;
                                @endphp
                                <tr @class([
                                    'bg-emerald-50/60 dark:bg-emerald-500/10' => $isHighlighted,
                                ])>
                                    <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-neutral-900 dark:text-white">{{ optional($transaction->created_at)->translatedFormat('d M Y') }}</span>
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ optional($transaction->created_at)->format('H:i') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        @if ($isCancelled)
                                            <span class="text-sm font-semibold text-neutral-500 dark:text-neutral-400">{{ __('Dibatalkan') }}</span>
                                        @elseif ($hasPendingInstallment && $nearestDueDate)
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
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-neutral-900 dark:text-white">{{ $nasabahName }}</span>
                                            @if ($transaction->nasabah && $transaction->nasabah->kode_member)
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Kode Member: :kode', ['kode' => $transaction->nasabah->kode_member]) }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">
                                        <div class="flex flex-col">
                                            @if ($items->count() === 1)
                                                @php
                                                    $item = $items->first();
                                                @endphp
                                                <span class="font-semibold text-neutral-900 dark:text-white">{{ $item->nama_barang ?? $transaction->pabrikan }}</span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ number_format((float) ($item->berat ?? $transaction->berat_gram), 3, ',', '.') }} gr • {{ $item->kode_group ?? $item->kode_intern ?? $transaction->kadar }}</span>
                                            @elseif ($items->count() > 1)
                                                <span class="font-semibold text-neutral-900 dark:text-white">{{ __(':count barang', ['count' => $items->count()]) }}</span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ number_format((float) $transaction->berat_gram, 3, ',', '.') }} gr • {{ $transaction->kadar }}</span>
                                                <ul class="mt-1 list-disc space-y-1 ps-4 text-[11px] text-neutral-500 dark:text-neutral-400">
                                                    @foreach ($items->take(3) as $item)
                                                        <li>{{ $item->nama_barang }} • {{ number_format((float) ($item->berat ?? 0), 3, ',', '.') }} gr • {{ $item->kode_group ?? $item->kode_intern ?? '—' }}</li>
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
                                    <td class="px-4 py-3 align-top">
                                        <div class="relative flex justify-center" data-more-container>
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-full border border-neutral-200 bg-white p-2 text-neutral-500 transition hover:border-neutral-300 hover:text-neutral-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:border-neutral-500 dark:hover:text-white"
                                                data-more-toggle
                                                aria-haspopup="true"
                                                aria-expanded="false"
                                            >
                                                <span class="sr-only">{{ __('Menu aksi untuk cicilan') }}</span>
                                                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.008M12 12h.008M19 12h.008" />
                                                </svg>
                                            </button>
                                            <div
                                                class="absolute right-0 top-full z-20 mt-2 hidden w-48 rounded-lg border border-neutral-200 bg-white py-1 text-sm shadow-lg dark:border-neutral-600 dark:bg-neutral-900"
                                                data-more-menu
                                                role="menu"
                                            >
                                                <button
                                                    type="button"
                                                    class="flex w-full items-center gap-2 px-4 py-2 text-left text-neutral-700 transition hover:bg-neutral-50 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50 dark:text-neutral-200 dark:hover:bg-neutral-700/60"
                                                    data-cicilan-cancel-trigger
                                                    data-transaction-id="{{ $transaction->id }}"
                                                    data-summary="{{ e($cancelSummary) }}"
                                                    {{ $canCancel ? '' : 'disabled' }}
                                                    role="menuitem"
                                                >
                                                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg>
                                                    <span>{{ __('Batal Cicilan') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div
                        data-cicilan-pagination
                        data-table-id="cicilan-transactions-table"
                        class="flex flex-col gap-4 border-t border-neutral-200 bg-neutral-50 px-4 py-3 text-sm dark:border-neutral-700 dark:bg-neutral-900 sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div class="flex items-center gap-3">
                            <div class="flex flex-col gap-1 text-neutral-600 dark:text-neutral-300">
                                <label for="cicilan-rows-per-page" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                                    {{ __('Rows per page') }}
                                    <span data-rows-per-page-value class="ms-1 rounded-md bg-neutral-200 px-2 py-0.5 text-[11px] font-bold uppercase tracking-wider text-neutral-700 dark:bg-neutral-800 dark:text-neutral-100">10</span>
                                </label>
                                <select
                                    id="cicilan-rows-per-page"
                                    data-rows-per-page-select
                                    class="w-28 rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm font-medium text-neutral-700 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-200 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/30"
                                >
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>

                        <nav
                            aria-label="{{ __('Pagination') }}"
                            class="flex flex-wrap items-center justify-end gap-2 text-sm"
                            data-pagination-nav
                        ></nav>
                    </div>
                </div>
            @endif
        </section>
    </div>

    <div
        id="cicilan-cancel-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4 py-6 backdrop-blur-sm"
        role="dialog"
        aria-modal="true"
        aria-labelledby="cicilan-cancel-modal-title"
        data-open-on-load="{{ ($errors->has('alasan_pembatalan') || $errors->has('transaction_id')) ? 'true' : 'false' }}"
        data-initial-transaction="{{ $pendingCancelId ?? '' }}"
        data-initial-summary="{{ e($pendingCancelSummary) }}"
        data-initial-reason="{{ e($pendingCancelReason) }}"
        data-action-template="{{ route('cicil-emas.daftar-cicilan.cancel', ['transaction' => '__TRANSACTION__']) }}"
    >
        <div class="mx-auto w-full max-w-lg rounded-2xl bg-white shadow-xl dark:bg-neutral-900">
            <div class="flex items-center justify-between border-b border-neutral-200 px-6 py-4 dark:border-neutral-700">
                <div>
                    <h2 id="cicilan-cancel-modal-title" class="text-lg font-semibold text-neutral-900 dark:text-white">
                        {{ __('Batalkan Transaksi Cicilan') }}
                    </h2>
                    <p class="text-sm text-neutral-500 dark:text-neutral-300" data-cancel-summary></p>
                </div>
                <button
                    type="button"
                    class="text-neutral-400 transition hover:text-neutral-600 dark:text-neutral-500 dark:hover:text-neutral-300"
                    data-cancel-close
                >
                    <span class="sr-only">{{ __('Tutup modal') }}</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form
                method="POST"
                class="space-y-4 px-6 py-5"
                data-cancel-form
                action="{{ route('cicil-emas.daftar-cicilan.cancel', ['transaction' => '__TRANSACTION__']) }}"
            >
                @csrf
                <input type="hidden" name="transaction_id" value="{{ $pendingCancelId ?? '' }}" data-cancel-transaction>
                @error('transaction_id')
                    <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <label class="flex flex-col gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                    <span class="font-medium">{{ __('Alasan Pembatalan') }}</span>
                    <textarea
                        name="alasan_pembatalan"
                        rows="4"
                        required
                        class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:focus:border-red-400 dark:focus:ring-red-500/40"
                        placeholder="{{ __('Jelaskan mengapa cicilan ini dibatalkan…') }}"
                        data-cancel-reason
                    >{{ old('alasan_pembatalan') }}</textarea>
                    @error('alasan_pembatalan')
                        <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </label>
                <p class="text-xs text-neutral-500 dark:text-neutral-400">
                    {{ __('Pembatalan akan menghapus cicilan dari daftar aktif dan mencegah pencatatan pembayaran selanjutnya.') }}
                </p>
                <div class="flex items-center justify-between gap-3">
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-600 transition hover:bg-neutral-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-400 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800/70"
                        data-cancel-close
                    >
                        {{ __('Batal') }}
                    </button>
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-red-600 bg-red-600 px-4 py-2 text-sm font-semibold text-red shadow-sm transition hover:border-red-700 hover:bg-red-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 dark:border-red-500 dark:bg-red-500 dark:hover:border-red-400 dark:hover:bg-red-400"
                    >
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span>{{ __('Konfirmasi Batal') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
