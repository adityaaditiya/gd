<x-layouts.app :title="__('Tambah Nasabah')">
    <div class="space-y-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Tambah Nasabah') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Isi formulir berikut untuk mendaftarkan member baru dan mendapatkan kode member otomatis setelah data tersimpan.') }}
            </p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-700 dark:border-emerald-500/60 dark:bg-emerald-500/10 dark:text-emerald-300">
                <p class="font-semibold">{{ session('status') }}</p>
                @if (session('kode_member'))
                    <p class="mt-1 text-sm">{{ __('Kode member otomatis:') }}</p>
                    <input
                        type="text"
                        readonly
                        value="{{ session('kode_member') }}"
                        class="mt-2 w-full rounded-lg border border-emerald-300 bg-white px-3 py-2 font-semibold tracking-wide text-emerald-700 shadow-sm dark:border-emerald-500/60 dark:bg-neutral-900 dark:text-emerald-300"
                    />
                    <p class="mt-1 text-xs">{{ __('Simpan kode ini untuk keperluan verifikasi dan layanan selanjutnya.') }}</p>
                @endif
            </div>
        @endif

        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <form
                method="POST"
                action="{{ route('nasabah.data-nasabah.store') }}"
                class="space-y-6 p-6"
            >
                @csrf
                <input type="hidden" name="redirect_to" value="nasabah.tambah-nasabah">

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="nik" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('NIK') }} <span class="text-red-500">*</span></label>
                        <input
                            id="nik"
                            name="nik"
                            type="text"
                            value="{{ old('nik') }}"
                            required
                            class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                        />
                        @error('nik')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nama" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Nama') }} <span class="text-red-500">*</span></label>
                        <input
                            id="nama"
                            name="nama"
                            type="text"
                            value="{{ old('nama') }}"
                            required
                            class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                        />
                        @error('nama')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tempat_lahir" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Tempat Lahir') }} <span class="text-red-500">*</span></label>
                        <input
                            id="tempat_lahir"
                            name="tempat_lahir"
                            type="text"
                            value="{{ old('tempat_lahir') }}"
                            required
                            class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                        />
                        @error('tempat_lahir')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_lahir" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Tanggal Lahir') }} <span class="text-red-500">*</span></label>
                        <input
                            id="tanggal_lahir"
                            name="tanggal_lahir"
                            type="date"
                            value="{{ old('tanggal_lahir') }}"
                            required
                            class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                        />
                        @error('tanggal_lahir')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telepon" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Telepon') }} <span class="text-red-500">*</span></label>
                        <input
                            id="telepon"
                            name="telepon"
                            type="tel"
                            inputmode="tel"
                            value="{{ old('telepon') }}"
                            required
                            class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                        />
                        @error('telepon')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kota" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Kota') }}</label>
                        <input
                            id="kota"
                            name="kota"
                            type="text"
                            value="{{ old('kota') }}"
                            class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                        />
                        @error('kota')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kelurahan" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Kelurahan') }}</label>
                        <input
                            id="kelurahan"
                            name="kelurahan"
                            type="text"
                            value="{{ old('kelurahan') }}"
                            class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                        />
                        @error('kelurahan')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kecamatan" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Kecamatan') }}</label>
                        <input
                            id="kecamatan"
                            name="kecamatan"
                            type="text"
                            value="{{ old('kecamatan') }}"
                            class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                        />
                        @error('kecamatan')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="npwp" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('No NPWP') }}</label>
                        <input
                            id="npwp"
                            name="npwp"
                            type="text"
                            value="{{ old('npwp') }}"
                            class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                        />
                        @error('npwp')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="id_lain" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('No Identitas Lain') }}</label>
                        <input
                            id="id_lain"
                            name="id_lain"
                            type="text"
                            value="{{ old('id_lain') }}"
                            class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                        />
                        @error('id_lain')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="alamat" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Alamat') }} <span class="text-red-500">*</span></label>
                    <textarea
                        id="alamat"
                        name="alamat"
                        rows="3"
                        required
                        class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                    >{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-2">
                    <input
                        id="nasabah_lama"
                        name="nasabah_lama"
                        type="checkbox"
                        value="1"
                        @checked(old('nasabah_lama'))
                        class="size-4 rounded border-neutral-300 text-emerald-600 focus:ring-emerald-500 dark:border-neutral-600 dark:bg-neutral-900 dark:focus:ring-emerald-400"
                    />
                    <label for="nasabah_lama" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Nasabah Lama') }}</label>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <a
                        href="{{ route('nasabah.data-nasabah') }}"
                        wire:navigate
                        class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-600 transition hover:bg-neutral-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800"
                    >
                        {{ __('Batal') }}
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500"
                    >
                        {{ __('Simpan') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
