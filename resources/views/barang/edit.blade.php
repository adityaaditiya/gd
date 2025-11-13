<x-layouts.app :title="__('Ubah Data Barang')">
    <div class="space-y-8">
        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-3 text-sm text-neutral-500 dark:text-neutral-400">
                <a href="{{ route('barang.data-barang') }}" class="inline-flex items-center gap-1 font-medium text-emerald-600 hover:text-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:text-emerald-400 dark:hover:text-emerald-300">
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    <span>{{ __('Kembali') }}</span>
                </a>
            </div>

            <div class="space-y-1">
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Ubah Data Barang') }}</h1>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Perbarui informasi barang emas kemudian simpan perubahan ke database.') }}
                </p>
            </div>
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <form method="POST" action="{{ route('barang.data-barang.update', $barang) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="space-y-1.5">
                    <label for="kode_barcode" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Kode Barcode') }}</label>
                    <input
                        type="text"
                        id="kode_barcode"
                        name="kode_barcode"
                        value="{{ old('kode_barcode', $barang->kode_barcode) }}"
                        required
                        maxlength="191"
                        autofocus
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
                        value="{{ old('nama_barang', $barang->nama_barang) }}"
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
                        value="{{ old('kode_intern', $barang->kode_intern) }}"
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
                        value="{{ old('kode_group', $barang->kode_group) }}"
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
                        value="{{ old('berat', $barang->berat) }}"
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
                            value="{{ old('harga', $barang->harga) }}"
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

                <div class="flex items-center justify-end gap-3">
                    <a
                        href="{{ route('barang.data-barang') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-600 transition hover:border-neutral-400 hover:text-neutral-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-400 dark:border-neutral-600 dark:text-neutral-200 dark:hover:border-neutral-500 dark:hover:text-white"
                    >
                        {{ __('Batal') }}
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-blue-600 shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
                    >
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span>{{ __('Simpan Perubahan') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
