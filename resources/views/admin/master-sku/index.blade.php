<x-layouts.app :title="__('Master SKU')">
    <div class="space-y-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Master SKU') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Kelola dan sinkronkan SKU serta harga barang agar sesuai dengan data master barang.') }}
            </p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-700 dark:border-emerald-500/60 dark:bg-emerald-500/10 dark:text-emerald-300">
                <p class="font-semibold">{{ session('status') }}</p>
            </div>
        @endif

        <div class="space-y-4 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Daftar SKU Barang') }}</h2>
                    <span class="text-sm text-neutral-500 dark:text-neutral-400">
                        {{ trans_choice('{0}Tidak ada barang|{1}1 barang|[2,*]:count barang', $barangs->count(), ['count' => $barangs->count()]) }}
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 text-left text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200">
                    <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-950 dark:text-neutral-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">#</th>
                            <th scope="col" class="px-4 py-3">{{ __('Nama Barang') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Kode Intern') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('SKU') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Harga') }}</th>
                            <th scope="col" class="px-4 py-3 text-right">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-800 dark:bg-neutral-900">
                        @forelse ($barangs as $barang)
                            @php
                                $isCurrentRow = (int) old('barang_id') === $barang->id;
                            @endphp
                            <form method="POST" action="{{ route('admin.master-sku.update', $barang) }}" class="contents">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                                <tr class="transition hover:bg-neutral-50 dark:hover:bg-neutral-800/60">
                                    <td class="px-4 py-3 align-top text-neutral-500 dark:text-neutral-400">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 align-top font-medium text-neutral-900 dark:text-white">{{ $barang->nama_barang }}</td>
                                    <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">{{ $barang->kode_intern }}</td>
                                    <td class="px-4 py-3 align-top">
                                        <div class="space-y-2">
                                            <input
                                                type="text"
                                                name="sku"
                                                value="{{ $isCurrentRow ? old('sku') : $barang->sku }}"
                                                class="block w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
                                                placeholder="{{ __('Masukkan SKU') }}"
                                            >
                                            @if ($isCurrentRow && $errors->has('sku'))
                                                <p class="text-xs text-rose-600 dark:text-rose-400">{{ $errors->first('sku') }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <div class="space-y-2">
                                            <input
                                                type="number"
                                                name="harga"
                                                step="0.01"
                                                min="0"
                                                value="{{ $isCurrentRow ? old('harga') : $barang->harga }}"
                                                class="block w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
                                                placeholder="{{ __('Masukkan harga') }}"
                                            >
                                            @if ($isCurrentRow && $errors->has('harga'))
                                                <p class="text-xs text-rose-600 dark:text-rose-400">{{ $errors->first('harga') }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <div class="flex justify-end">
                                            <button
                                                type="submit"
                                                class="inline-flex items-center gap-2 rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
                                            >
                                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                </svg>
                                                <span>{{ __('Simpan') }}</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </form>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                    {{ __('Belum ada data barang yang tersimpan.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
