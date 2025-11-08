@props([
    'nasabah' => null,
    'redirectRoute' => null,
    'submitLabel' => null,
])

@php
    $nasabah = $nasabah ? (object) $nasabah : null;
    $submitLabel = $submitLabel ?? __('Simpan');
@endphp

@if ($redirectRoute)
    <input type="hidden" name="redirect_to" value="{{ $redirectRoute }}">
@endif

<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="nik" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('NIK') }} <span class="text-red-500">*</span></label>
        <input
            id="nik"
            name="nik"
            type="text"
            value="{{ old('nik', $nasabah->nik ?? '') }}"
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
            value="{{ old('nama', $nasabah->nama ?? '') }}"
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
            value="{{ old('tempat_lahir', $nasabah->tempat_lahir ?? '') }}"
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
            value="{{ old('tanggal_lahir', isset($nasabah->tanggal_lahir) ? (is_string($nasabah->tanggal_lahir) ? $nasabah->tanggal_lahir : optional($nasabah->tanggal_lahir)->format('Y-m-d')) : '') }}"
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
            value="{{ old('telepon', $nasabah->telepon ?? '') }}"
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
            value="{{ old('kota', $nasabah->kota ?? '') }}"
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
            value="{{ old('kelurahan', $nasabah->kelurahan ?? '') }}"
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
            value="{{ old('kecamatan', $nasabah->kecamatan ?? '') }}"
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
            value="{{ old('npwp', $nasabah->npwp ?? '') }}"
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
            value="{{ old('id_lain', $nasabah->id_lain ?? '') }}"
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
    >{{ old('alamat', $nasabah->alamat ?? '') }}</textarea>
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
        @checked(old('nasabah_lama', $nasabah->nasabah_lama ?? false))
        class="size-4 rounded border-neutral-300 text-emerald-600 focus:ring-emerald-500 dark:border-neutral-600 dark:bg-neutral-900 dark:focus:ring-emerald-400"
    />
    <label for="nasabah_lama" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Nasabah Lama') }}</label>
</div>

@if (!empty($nasabah?->kode_member))
    <div>
        <label class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Kode Member') }}</label>
        <input
            type="text"
            value="{{ $nasabah->kode_member }}"
            readonly
            class="mt-2 w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm font-semibold tracking-wide text-neutral-800 shadow-sm dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100"
        />
        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">{{ __('Kode member dibuat otomatis dan tidak dapat diubah.') }}</p>
    </div>
@endif

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
        class="inline-flex items-center justify-center rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-blue-600 shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
    >
        {{ $submitLabel }}
    </button>
</div>
