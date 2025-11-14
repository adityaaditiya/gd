<x-layouts.app :title="__('Daftar Cicilan')">
    @php
        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\CicilEmasTransaction> $transactions */
        $highlightId = session('transaction_summary.transaksi_id');
    @endphp

    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Daftar Cicilan') }}</h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                {{ __('Kelola jadwal cicilan emas aktif, lengkap dengan rincian jatuh tempo dan ketentuan denda.') }}
            </p>
        </div>

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
                                                    @click.prevent="$dispatch('cicilan:cancel', { id: {{ \Illuminate\Support\Js::from($transaction->id) }} }); open = false"
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
</x-layouts.app>
