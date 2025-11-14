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
                    <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                        <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left">{{ __('Tanggal') }}</th>
                                <th scope="col" class="px-4 py-3 text-left">{{ __('Nasabah') }}</th>
                                <th scope="col" class="px-4 py-3 text-left">{{ __('Paket Emas') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('Harga') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('Uang Muka') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('Margin') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('Administrasi') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('Angsuran / Bln') }}</th>
                                <th scope="col" class="px-4 py-3 text-center">{{ __('Tenor') }}</th>
                                <th scope="col" class="px-4 py-3 text-left">{{ __('Jatuh Tempo') }}</th>
                                <th scope="col" class="px-4 py-3 text-center">{{ __('Aksi') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 bg-white text-sm dark:divide-neutral-700 dark:bg-neutral-900">
                            @foreach ($transactions as $transaction)
                                @php
                                    $isHighlighted = (string) $highlightId === (string) $transaction->id;
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
                                    <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-neutral-900 dark:text-white">{{ $transaction->nasabah->nama ?? __('Tidak diketahui') }}</span>
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
                                    <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">
                                        @php
                                            $dueDate = $transaction->nearest_due_date instanceof \Illuminate\Support\Carbon
                                                ? $transaction->nearest_due_date->copy()
                                                : ($transaction->nearest_due_date ? \Illuminate\Support\Carbon::parse($transaction->nearest_due_date) : null);
                                            $today = \Illuminate\Support\Carbon::now()->startOfDay();
                                        @endphp
                                        @if ($dueDate)
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-neutral-900 dark:text-white">{{ $dueDate->translatedFormat('d M Y') }}</span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">
                                                    @if ($dueDate->lt($today))
                                                        {{ __('Terlambat :days hari', ['days' => $dueDate->diffInDays($today)]) }}
                                                    @elseif ($dueDate->isSameDay($today))
                                                        {{ __('Jatuh tempo hari ini') }}
                                                    @else
                                                        {{ __(':days hari lagi', ['days' => $today->diffInDays($dueDate)]) }}
                                                    @endif
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Belum tersedia') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 align-top text-center text-neutral-700 dark:text-neutral-200">
                                        @php
                                            $packageLabelForCancel = $transaction->option_label
                                                ?? ($transaction->items->count() === 1
                                                    ? ($transaction->items->first()->nama_barang ?? $transaction->pabrikan)
                                                    : ($transaction->items->count() > 1
                                                        ? __(':count barang', ['count' => $transaction->items->count()])
                                                        : $transaction->pabrikan));
                                            $cancelSummary = __('Cicilan :nasabah • :paket', [
                                                'nasabah' => $transaction->nasabah?->nama ?? __('Nasabah tidak diketahui'),
                                                'paket' => $packageLabelForCancel,
                                            ]);
                                        @endphp

                                        <div
                                            x-data="{ open: false }"
                                            class="relative inline-flex text-left"
                                        >
                                            <button
                                                type="button"
                                                class="inline-flex items-center justify-center rounded-lg border border-neutral-200 bg-white p-2 text-neutral-500 transition hover:border-neutral-300 hover:text-neutral-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-400 dark:hover:border-neutral-600 dark:hover:text-neutral-200"
                                                @click="open = !open"
                                                @keydown.enter.prevent="open = !open"
                                                @keydown.space.prevent="open = !open"
                                                @keydown.escape.window="open = false"
                                                :aria-expanded="open.toString()"
                                                aria-haspopup="true"
                                            >
                                                <span class="sr-only">{{ __('Buka menu aksi') }}</span>
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                                                    aria-hidden="true"
                                                >
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 6a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 6a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                                </svg>
                                            </button>

                                            <div
                                                x-cloak
                                                x-show="open"
                                                x-transition.origin.top.right
                                                @click.outside="open = false"
                                                class="absolute right-0 z-20 mt-2 w-44 origin-top-right rounded-lg border border-neutral-200 bg-white py-1 shadow-lg ring-1 ring-black/5 focus:outline-none dark:border-neutral-700 dark:bg-neutral-900"
                                                role="menu"
                                                aria-orientation="vertical"
                                                tabindex="-1"
                                            >
                                                <button
                                                    type="button"
                                                    class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-neutral-700 transition hover:bg-neutral-100 dark:text-neutral-200 dark:hover:bg-neutral-800"
                                                    @click.prevent="open = false; window.dispatchEvent(new CustomEvent('cicilan:cancel', { detail: { id: {{ \Illuminate\Support\Js::from($transaction->id) }}, summary: {{ \Illuminate\Support\Js::from($cancelSummary) }} } }))"
                                                    role="menuitem"
                                                >
                                                    {{ __('Batal Cicilan') }}
                                                </button>
                                                <a
                                                    href="{{ route('cicil-emas.angsuran-rutin', ['transaksi' => $transaction->id]) }}"
                                                    class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-neutral-700 transition hover:bg-neutral-100 dark:text-neutral-200 dark:hover:bg-neutral-800"
                                                    @click="open = false"
                                                    role="menuitem"
                                                    wire:navigate
                                                >
                                                    {{ __('Bayar Angsuran') }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
