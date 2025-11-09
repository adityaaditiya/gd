@php
    $barang = $barangJaminan ?? null;
    $selectedTransaksi = old('transaksi_id', $barang?->transaksi_id);
    $selectedPenaksir = old('pegawai_penaksir_id', $barang?->pegawai_penaksir_id);
    $resolvePhotoUrl = static function (?string $path): ?string {
        if (!$path) {
            return null;
        }

        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return \Illuminate\Support\Facades\Storage::url($path);
    };
@endphp

<div class="grid gap-6 lg:grid-cols-2">
    <div class="flex flex-col gap-2">
        <label for="transaksi_id" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Transaksi Gadai') }}</label>
        <select
            id="transaksi_id"
            name="transaksi_id"
            class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
            required
        >
            <option value="" disabled {{ $selectedTransaksi ? '' : 'selected' }}>{{ __('Pilih transaksi') }}</option>
            @foreach ($transaksiList as $transaksi)
                <option value="{{ $transaksi->transaksi_id }}" {{ (string) $selectedTransaksi === (string) $transaksi->transaksi_id ? 'selected' : '' }}>
                    {{ $transaksi->no_sbg }} — {{ $transaksi->nasabah?->nama ?? __('Nasabah Tidak Diketahui') }}
                </option>
            @endforeach
        </select>
        @error('transaksi_id')
            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex flex-col gap-2">
        <label for="pegawai_penaksir_id" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Petugas Penaksir') }}</label>
        <select
            id="pegawai_penaksir_id"
            name="pegawai_penaksir_id"
            class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
        >
            <option value="">{{ __('Pilih penaksir (opsional)') }}</option>
            @foreach ($penaksirList as $penaksir)
                <option value="{{ $penaksir->id }}" {{ (string) $selectedPenaksir === (string) $penaksir->id ? 'selected' : '' }}>{{ $penaksir->name }}</option>
            @endforeach
        </select>
        @error('pegawai_penaksir_id')
            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex flex-col gap-2">
        <label for="jenis_barang" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Jenis Barang') }}</label>
        <input
            type="text"
            id="jenis_barang"
            name="jenis_barang"
            value="{{ old('jenis_barang', $barang?->jenis_barang) }}"
            required
            class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
        />
        @error('jenis_barang')
            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex flex-col gap-2">
        <label for="merek" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Merek') }}</label>
        <input
            type="text"
            id="merek"
            name="merek"
            value="{{ old('merek', $barang?->merek) }}"
            required
            class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
        />
        @error('merek')
            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex flex-col gap-2">
        <label for="usia_barang_thn" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Usia Barang (Tahun)') }}</label>
        <input
            type="number"
            id="usia_barang_thn"
            name="usia_barang_thn"
            value="{{ old('usia_barang_thn', $barang?->usia_barang_thn) }}"
            min="0"
            max="120"
            class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
        />
        @error('usia_barang_thn')
            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex flex-col gap-2">
        <label for="hps" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Harga Pasar Setempat (HPS)') }}</label>
        <input
            type="text"
            inputmode="decimal"
            id="hps"
            name="hps"
            value="{{ old('hps', $barang ? number_format((float) $barang->hps, 2, ',', '.') : '') }}"
            required
            class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
        />
        @error('hps')
            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex flex-col gap-2">
        <label for="nilai_taksiran" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Nilai Taksiran') }}</label>
        <input
            type="text"
            inputmode="decimal"
            id="nilai_taksiran"
            name="nilai_taksiran"
            value="{{ old('nilai_taksiran', $barang ? number_format((float) $barang->nilai_taksiran, 2, ',', '.') : '') }}"
            required
            class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
        />
        @error('nilai_taksiran')
            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="flex flex-col gap-2">
    <label for="kondisi_fisik" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Kondisi Fisik') }}</label>
    <textarea
        id="kondisi_fisik"
        name="kondisi_fisik"
        rows="4"
        class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
    >{{ old('kondisi_fisik', $barang?->kondisi_fisik) }}</textarea>
    @error('kondisi_fisik')
        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>

<div class="grid gap-6 lg:grid-cols-2">
    @foreach (range(1, 6) as $index)
        @php
            $currentPhoto = $barang?->{'foto_' . $index};
            $currentPhotoUrl = $resolvePhotoUrl($currentPhoto);
        @endphp
        <div class="flex flex-col gap-2">
            <label for="foto_{{ $index }}" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">
                {{ __('Foto :number', ['number' => $index]) }}
            </label>
            <input
                type="file"
                id="foto_{{ $index }}"
                name="foto_{{ $index }}"
                accept="image/*"
                class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
            />
            @error('foto_' . $index)
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
            @if ($currentPhotoUrl)
                <p class="text-xs text-neutral-500 dark:text-neutral-300">
                    {{ __('Foto saat ini:') }}
                    <a
                        href="{{ $currentPhotoUrl }}"
                        target="_blank"
                        class="font-semibold text-emerald-600 hover:underline dark:text-emerald-300"
                    >
                        {{ __('Lihat') }}
                    </a>
                </p>
            @endif
        </div>
    @endforeach
</div>
