<x-layouts.app :title="__('Data Barang')">
    <div class="space-y-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Data Barang') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Catat dan kelola master barang emas yang digunakan pada simulasi cicilan, lengkap dengan kode dan harga terkini.') }}
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
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Daftar Barang') }}</h2>
                    <span class="text-sm text-neutral-500 dark:text-neutral-400">
                        {{ trans_choice('{0}Tidak ada barang|{1}1 barang|[2,*]:count barang', $barangs->count(), ['count' => $barangs->count()]) }}
                    </span>
                </div>

                <a
                    href="{{ route('barang.data-barang.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-blue-600 shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
                >
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>{{ __('Tambah Data') }}</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 text-left text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200">
                    <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-950 dark:text-neutral-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">#</th>
                            <th scope="col" class="px-4 py-3">{{ __('Kode Barcode') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Nama Barang') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Kode Intern') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('SKU') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Kode Group') }}</th>
                            <th scope="col" class="px-4 py-3 text-right">{{ __('Kadar (%)') }}</th>
                            <th scope="col" class="px-4 py-3 text-right">{{ __('Berat (gram)') }}</th>
                            <th scope="col" class="px-4 py-3 text-right">{{ __('Harga') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Dibuat') }}</th>
                            <th scope="col" class="px-4 py-3 text-right">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-800 dark:bg-neutral-900">
                        @forelse ($barangs as $barang)
                            <tr class="transition hover:bg-neutral-50 dark:hover:bg-neutral-800/60">
                                <td class="px-4 py-3 align-top text-neutral-500 dark:text-neutral-400">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 align-top font-medium text-neutral-900 dark:text-white">{{ $barang->kode_barcode }}</td>
                                <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">{{ $barang->nama_barang }}</td>
                                <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">{{ $barang->kode_intern }}</td>
                                <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">{{ filled($barang->sku) ? $barang->sku : '–' }}</td>
                                <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">{{ $barang->kode_group }}</td>
                                <td class="px-4 py-3 align-top text-right text-neutral-700 dark:text-neutral-200">
                                    {{ $barang->kadar !== null ? number_format((float) $barang->kadar, 2, ',', '.') : '–' }}
                                </td>
                                <td class="px-4 py-3 align-top text-right text-neutral-700 dark:text-neutral-200">{{ number_format((float) $barang->berat, 3, ',', '.') }}</td>
                                <td class="px-4 py-3 align-top text-right font-semibold text-neutral-900 dark:text-white">{{ 'Rp '.number_format((float) $barang->harga, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 align-top text-neutral-500 dark:text-neutral-400">{{ optional($barang->created_at)->format('d M Y') }}</td>
                                <td class="px-4 py-3 align-top">
                                    <div class="flex justify-end gap-2">
                                        <a
                                            href="{{ route('barang.data-barang.edit', $barang) }}"
                                            class="inline-flex items-center gap-1 rounded-lg border border-neutral-300 px-3 py-1.5 text-xs font-semibold text-neutral-700 transition hover:border-emerald-500 hover:text-emerald-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-neutral-600 dark:text-neutral-200 dark:hover:border-emerald-400 dark:hover:text-emerald-300"
                                        >
                                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                            </svg>
                                            <span>{{ __('Ubah') }}</span>
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('barang.data-barang.destroy', $barang) }}"
                                            onsubmit="return confirm('{{ __('Apakah Anda yakin ingin menghapus data ini?') }}');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg border border-rose-500 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:border-rose-600 hover:bg-rose-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-500 dark:border-rose-500 dark:text-rose-300 dark:hover:border-rose-400 dark:hover:bg-rose-500/10"
                                            >
                                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                                <span>{{ __('Hapus') }}</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-400">
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
