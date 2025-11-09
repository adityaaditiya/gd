@php
    $barangJaminan = ($barangJaminan ?? null) ? (object) $barangJaminan : null;
    $penaksirList = $penaksirList ?? collect();
    $submitLabel = $submitLabel ?? __('Simpan');
    $hpsInput = old('hps', $barangJaminan?->hps ?? '');
    $estimatedTaksiran = is_numeric($hpsInput) ? number_format((float) $hpsInput * 0.94, 2, ',', '.') : null;
@endphp

<div class="space-y-6">
    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="no_sbg" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('No. SBG') }} <span class="text-red-500">*</span></label>
            <input
                id="no_sbg"
                name="no_sbg"
                type="text"
                value="{{ old('no_sbg', $barangJaminan?->transaksi?->no_sbg ?? '') }}"
                required
                class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
            />
            @error('no_sbg')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
            <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">{{ __('Masukkan nomor SBG untuk menghubungkan barang dengan kontrak gadai yang valid.') }}</p>
        </div>

        <div>
            <label for="jenis_barang" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Jenis Barang') }} <span class="text-red-500">*</span></label>
            <input
                id="jenis_barang"
                name="jenis_barang"
                type="text"
                value="{{ old('jenis_barang', $barangJaminan->jenis_barang ?? '') }}"
                required
                class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
            />
            @error('jenis_barang')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="merek" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Merek') }} <span class="text-red-500">*</span></label>
            <input
                id="merek"
                name="merek"
                type="text"
                value="{{ old('merek', $barangJaminan->merek ?? '') }}"
                required
                class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
            />
            @error('merek')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="usia_barang_thn" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Usia Barang (Tahun)') }}</label>
            <input
                id="usia_barang_thn"
                name="usia_barang_thn"
                type="number"
                min="0"
                max="100"
                value="{{ old('usia_barang_thn', $barangJaminan->usia_barang_thn ?? '') }}"
                class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
            />
            @error('usia_barang_thn')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="md:col-span-2">
            <label for="hps" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Harga Pasar Setempat (HPS)') }} <span class="text-red-500">*</span></label>
            <input
                id="hps"
                name="hps"
                type="number"
                step="0.01"
                min="0"
                required
                class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                value="{{ old('hps', $barangJaminan?->hps ?? '') }}"
            />
            @error('hps')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
            <p class="mt-2 text-xs text-neutral-600 dark:text-neutral-400">
                {{ __('Nilai taksiran akan dihitung otomatis sebesar 94% dari HPS.') }}
            </p>
            <p class="mt-1 text-sm font-semibold text-emerald-600 dark:text-emerald-300">
                {{ __('Perkiraan Nilai Taksiran:') }}
                <span>
                    @if ($estimatedTaksiran)
                        Rp {{ $estimatedTaksiran }}
                    @else
                        —
                    @endif
                </span>
            </p>
        </div>

        <div>
            <label for="pegawai_penaksir_id" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Penaksir') }}</label>
            <select
                id="pegawai_penaksir_id"
                name="pegawai_penaksir_id"
                class="mt-2 w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
            >
                <option value="">{{ __('Pilih Penaksir (Opsional)') }}</option>
                @foreach ($penaksirList as $penaksir)
                    <option value="{{ $penaksir->id }}" @selected(old('pegawai_penaksir_id', $barangJaminan->pegawai_penaksir_id ?? '') == $penaksir->id)>
                        {{ $penaksir->name }}
                    </option>
                @endforeach
            </select>
            @error('pegawai_penaksir_id')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="kondisi_fisik" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Kondisi Fisik') }}</label>
            <textarea
                id="kondisi_fisik"
                name="kondisi_fisik"
                rows="3"
                class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
            >{{ old('kondisi_fisik', $barangJaminan->kondisi_fisik ?? '') }}</textarea>
            @error('kondisi_fisik')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
        @for ($i = 1; $i <= 6; $i++)
            @php
                $field = 'foto_' . $i;
            @endphp
            <div>
                <label for="{{ $field }}" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Foto :nomor', ['nomor' => $i]) }}</label>
                <input
                    id="{{ $field }}"
                    name="{{ $field }}"
                    type="text"
                    value="{{ old($field, $barangJaminan->$field ?? '') }}"
                    class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                />
                @error($field)
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        @endfor
    </div>

    <div class="flex items-center justify-end gap-3">
        <a
            href="{{ route('gadai.lihat-barang-gadai') }}"
            wire:navigate
            class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-700 transition hover:border-neutral-400 hover:text-neutral-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-400 dark:border-neutral-600 dark:text-neutral-200 dark:hover:border-neutral-500 dark:hover:text-white"
        >
            {{ __('Batal') }}
        </a>
        <button
            type="submit"
            class="inline-flex items-center justify-center rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
        >
            {{ $submitLabel }}
        </button>
    </div>
</div>
