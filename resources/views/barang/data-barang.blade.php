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

        <div class="grid gap-6 lg:grid-cols-[380px,1fr]">
            <div class="space-y-6 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="space-y-1">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Tambah Data Barang') }}</h2>
                    <p class="text-sm text-neutral-600 dark:text-neutral-300">
                        {{ __('Lengkapi formulir di bawah untuk menyimpan data barang emas ke database.') }}
                    </p>
                </div>

                <form method="POST" action="{{ route('barang.data-barang.store') }}" class="space-y-5">
                    @csrf

                    <div class="space-y-1.5">
                        <label for="kode_barcode" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Kode Barcode') }}</label>
                        <input
                            type="text"
                            id="kode_barcode"
                            name="kode_barcode"
                            value="{{ old('kode_barcode') }}"
                            required
                            maxlength="191"
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-950 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                        />
                        @error('kode_barcode')
                            <p class="text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="nama_barang" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Nama Barang') }}</label>
                        <input
                            type="text"
                            id="nama_barang"
                            name="nama_barang"
                            value="{{ old('nama_barang') }}"
                            required
                            maxlength="191"
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-950 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                        />
                        @error('nama_barang')
                            <p class="text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="kode_intern" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Kode Intern') }}</label>
                        <input
                            type="text"
                            id="kode_intern"
                            name="kode_intern"
                            value="{{ old('kode_intern') }}"
                            required
                            maxlength="191"
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-950 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                        />
                        @error('kode_intern')
                            <p class="text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="kode_group" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Kode Group') }}</label>
                        <input
                            type="text"
                            id="kode_group"
                            name="kode_group"
                            value="{{ old('kode_group') }}"
                            required
                            maxlength="191"
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-950 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                        />
                        @error('kode_group')
                            <p class="text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="berat" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Berat (gram)') }}</label>
                        <input
                            type="number"
                            id="berat"
                            name="berat"
                            value="{{ old('berat') }}"
                            required
                            step="0.001"
                            min="0"
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-950 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                        />
                        @error('berat')
                            <p class="text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="harga" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Harga (Rp)') }}</label>
                        <div class="flex rounded-lg border border-neutral-300 bg-white text-sm shadow-sm focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-950 dark:focus-within:border-emerald-400 dark:focus-within:ring-emerald-900/40">
                            <span class="flex items-center px-3 text-neutral-500 dark:text-neutral-400">Rp</span>
                            <input
                                type="number"
                                id="harga"
                                name="harga"
                                value="{{ old('harga') }}"
                                required
                                step="0.01"
                                min="0"
                                class="w-full rounded-r-lg border-0 bg-transparent px-3 py-2 text-neutral-900 focus:outline-none focus:ring-0 dark:text-white"
                            />
                        </div>
                        @error('harga')
                            <p class="text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
                        >
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>{{ __('Simpan Barang') }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="space-y-4 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Daftar Barang') }}</h2>
                    <span class="text-sm text-neutral-500 dark:text-neutral-400">
                        {{ trans_choice('{0}Tidak ada barang|{1}1 barang|[2,*]:count barang', $barangs->count(), ['count' => $barangs->count()]) }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 text-left text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200">
                        <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-950 dark:text-neutral-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">#</th>
                                <th scope="col" class="px-4 py-3">{{ __('Kode Barcode') }}</th>
                                <th scope="col" class="px-4 py-3">{{ __('Nama Barang') }}</th>
                                <th scope="col" class="px-4 py-3">{{ __('Kode Intern') }}</th>
                                <th scope="col" class="px-4 py-3">{{ __('Kode Group') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('Berat (gram)') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('Harga') }}</th>
                                <th scope="col" class="px-4 py-3">{{ __('Dibuat') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-800 dark:bg-neutral-900">
                            @forelse ($barangs as $barang)
                                <tr class="transition hover:bg-neutral-50 dark:hover:bg-neutral-800/60">
                                    <td class="px-4 py-3 align-top text-neutral-500 dark:text-neutral-400">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 align-top font-medium text-neutral-900 dark:text-white">{{ $barang->kode_barcode }}</td>
                                    <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">{{ $barang->nama_barang }}</td>
                                    <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">{{ $barang->kode_intern }}</td>
                                    <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">{{ $barang->kode_group }}</td>
                                    <td class="px-4 py-3 align-top text-right text-neutral-700 dark:text-neutral-200">{{ number_format((float) $barang->berat, 3, ',', '.') }}</td>
                                    <td class="px-4 py-3 align-top text-right font-semibold text-neutral-900 dark:text-white">{{ 'Rp '.number_format((float) $barang->harga, 2, ',', '.') }}</td>
                                    <td class="px-4 py-3 align-top text-neutral-500 dark:text-neutral-400">{{ optional($barang->created_at)->format('d M Y') }}</td>
                                </tr>
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
    </div>
</x-layouts.app>
