@if ($barangSiapGadai->isEmpty())
    <div class="rounded-xl border border-amber-300 bg-amber-50 p-4 text-sm text-amber-700 dark:border-amber-500/60 dark:bg-amber-500/10 dark:text-amber-300">
        {{ __('Tidak ada barang jaminan dengan status siap gadai. Tambahkan barang terlebih dahulu sebelum membuat kontrak.') }}
    </div>
@endif

<div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
    <form method="POST" action="{{ route('transaksi-gadai.store') }}" class="space-y-8 p-6">
        @csrf

        <div class="space-y-6">
            <div class="space-y-2">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('1. Pemilihan Barang') }}</h2>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Pilih barang jaminan dengan status siap gadai. Nilai taksiran akan ditampilkan otomatis.') }}
                </p>
            </div>
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="flex flex-col gap-2">
                    <label for="barang_id" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Barang Jaminan') }}</label>
                    <select
                        id="barang_id"
                        name="barang_id"
                        class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                        @disabled($barangSiapGadai->isEmpty())
                        required
                    >
                        <option value="" disabled {{ old('barang_id') ? '' : 'selected' }}>{{ __('Pilih barang jaminan') }}</option>
                        @foreach ($barangSiapGadai as $barang)
                            <option
                                value="{{ $barang->barang_id }}"
                                data-nilai="{{ number_format((float) $barang->nilai_taksiran, 2, ',', '.') }}"
                                @selected(old('barang_id') == $barang->barang_id)
                            >
                                {{ $barang->jenis_barang }} — {{ $barang->merek }}
                            </option>
                        @endforeach
                    </select>
                    @error('barang_id')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col gap-2">
                    <span class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Nilai Taksiran') }}</span>
                    <div
                        id="nilaiTaksiranPreview"
                        data-template="{{ __('Rp :value', ['value' => ':value']) }}"
                        class="rounded-lg border border-dashed border-neutral-300 bg-neutral-50 px-4 py-3 text-sm font-semibold text-neutral-700 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
                    >
                        {{ old('barang_id') ? __('Rp :value', ['value' => number_format((float) optional($barangSiapGadai->firstWhere('barang_id', old('barang_id')))->nilai_taksiran, 2, ',', '.')]) : __('Pilih barang untuk melihat nilai taksiran') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="space-y-2">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('2. Input Pinjaman') }}</h2>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Masukkan detail kontrak dan pastikan nilai pinjaman tidak melebihi 94% dari nilai taksiran.') }}
                </p>
            </div>
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="flex flex-col gap-2">
                    <label for="no_sbg" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Nomor SBG') }}</label>
                    <input
                        type="text"
                        id="no_sbg"
                        name="no_sbg"
                        value="{{ old('no_sbg') }}"
                        required
                        class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                    />
                    @error('no_sbg')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col gap-2">
                    <label for="nasabah_id" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Nasabah') }}</label>
                    <select
                        id="nasabah_id"
                        name="nasabah_id"
                        class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                        required
                    >
                        <option value="" disabled {{ old('nasabah_id') ? '' : 'selected' }}>{{ __('Pilih nasabah') }}</option>
                        @foreach ($nasabahList as $nasabah)
                            <option value="{{ $nasabah->id }}" @selected(old('nasabah_id') == $nasabah->id)>
                                {{ $nasabah->nama }} @if ($nasabah->kode_member) — {{ $nasabah->kode_member }} @endif
                            </option>
                        @endforeach
                    </select>
                    @error('nasabah_id')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col gap-2">
                    <label for="tanggal_gadai" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Tanggal Gadai') }}</label>
                    <input
                        type="date"
                        id="tanggal_gadai"
                        name="tanggal_gadai"
                        value="{{ old('tanggal_gadai', now()->toDateString()) }}"
                        required
                        class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                    />
                    @error('tanggal_gadai')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col gap-2">
                    <label for="jatuh_tempo_awal" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Jatuh Tempo Awal') }}</label>
                    <input
                        type="date"
                        id="jatuh_tempo_awal"
                        name="jatuh_tempo_awal"
                        value="{{ old('jatuh_tempo_awal', now()->addMonths(4)->toDateString()) }}"
                        required
                        class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                    />
                    @error('jatuh_tempo_awal')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col gap-2">
                    <label for="uang_pinjaman" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Uang Pinjaman yang Disetujui') }}</label>
                    <input
                        type="text"
                        inputmode="decimal"
                        id="uang_pinjaman"
                        name="uang_pinjaman"
                        value="{{ old('uang_pinjaman') }}"
                        required
                        class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                        placeholder="{{ __('Maksimal 94% dari nilai taksiran') }}"
                    />
                    @error('uang_pinjaman')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col gap-2">
                    <label for="biaya_admin" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Biaya Admin') }}</label>
                    <input
                        type="text"
                        inputmode="decimal"
                        id="biaya_admin"
                        name="biaya_admin"
                        value="{{ old('biaya_admin', '0') }}"
                        required
                        class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                    />
                    @error('biaya_admin')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="space-y-2">
            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('3. Penerbitan Kontrak') }}</h2>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Periksa kembali informasi yang diisi. Setelah disimpan, barang tidak dapat dipilih ulang.') }}
            </p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
            <a
                href="{{ route('transaksi-gadai.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-400 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-700/60"
            >
                {{ __('Batal') }}
            </a>
            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
                @disabled($barangSiapGadai->isEmpty())
            >
                {{ __('Simpan Kontrak') }}
            </button>
        </div>
    </form>
</div>

@once
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const selectBarang = document.getElementById('barang_id');
            const nilaiPreview = document.getElementById('nilaiTaksiranPreview');

            if (!selectBarang || !nilaiPreview) {
                return;
            }

            const updatePreview = () => {
                const option = selectBarang.options[selectBarang.selectedIndex];
                const nilai = option ? option.getAttribute('data-nilai') : null;

                if (nilai) {
                    const template = nilaiPreview.dataset.template || 'Rp :value';
                    nilaiPreview.textContent = template.replace(':value', nilai);
                } else {
                    nilaiPreview.textContent = '{{ __('Pilih barang untuk melihat nilai taksiran') }}';
                }
            };

            selectBarang.addEventListener('change', updatePreview);
            updatePreview();
        });
    </script>
@endonce
