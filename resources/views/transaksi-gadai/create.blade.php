<x-layouts.app :title="__('Buat Kontrak Gadai')">
    <div class="space-y-8" id="transaksi-gadai-create">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Buat Kontrak Gadai') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Ikuti alur pemilihan barang, penetapan pinjaman, dan simpan untuk menerbitkan kontrak gadai.') }}
            </p>
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <form method="POST" action="{{ route('transaksi-gadai.store') }}" class="space-y-8 p-6">
                @csrf

                <section class="space-y-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-300">{{ __('Langkah 1') }}</p>
                        <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Pemilihan Barang & Nasabah') }}</h2>
                        <p class="text-sm text-neutral-600 dark:text-neutral-300">
                            {{ __('Mulai dengan memilih nasabah dan barang jaminan yang statusnya siap digadaikan.') }}
                        </p>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-2">
                        <div class="flex flex-col gap-2">
                            <label for="no_sbg" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Nomor Kontrak (No. SBG)') }}</label>
                            <input
                                type="text"
                                id="no_sbg"
                                name="no_sbg"
                                value="{{ old('no_sbg') }}"
                                placeholder="SBG-{{ now()->format('YmdHis') }}"
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
                                    <option value="{{ $nasabah->id }}" {{ (string) old('nasabah_id') === (string) $nasabah->id ? 'selected' : '' }}>
                                        {{ $nasabah->nama }} — {{ $nasabah->kode_member ?? __('Tanpa Kode') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('nasabah_id')
                                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-2 lg:col-span-2">
                            <label for="barang_id" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Barang Jaminan') }}</label>
                            <select
                                id="barang_id"
                                name="barang_id"
                                class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                                required
                                @disabled($barangSiapGadai->isEmpty())
                            >
                                <option value="" disabled {{ old('barang_id') ? '' : 'selected' }}>
                                    {{ __('Pilih barang siap gadai') }}
                                </option>
                                @foreach ($barangSiapGadai as $barang)
                                    <option
                                        value="{{ $barang->barang_id }}"
                                        data-taksiran="{{ number_format((float) $barang->nilai_taksiran, 2, ',', '.') }}"
                                        data-raw-taksiran="{{ (float) $barang->nilai_taksiran }}"
                                        {{ (string) old('barang_id') === (string) $barang->barang_id ? 'selected' : '' }}
                                    >
                                        {{ $barang->jenis_barang }} — {{ $barang->merek }} (ID: {{ $barang->barang_id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('barang_id')
                                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            @if ($barangSiapGadai->isEmpty())
                                <p class="text-sm text-amber-600 dark:text-amber-300">{{ __('Belum ada barang dengan status siap gadai. Tambahkan atau perbarui data barang terlebih dahulu.') }}</p>
                            @endif
                        </div>
                    </div>
                </section>

                <section class="space-y-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-300">{{ __('Langkah 2') }}</p>
                        <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Penetapan Pinjaman') }}</h2>
                        <p class="text-sm text-neutral-600 dark:text-neutral-300">
                            {{ __('Tinjau nilai taksiran barang terpilih, kemudian tentukan nominal pinjaman dan biaya administrasi.') }}
                        </p>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-2">
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Nilai Taksiran Barang') }}</label>
                            <div id="nilaiTaksiranDisplay" class="rounded-lg border border-dashed border-neutral-300 bg-neutral-50 px-3 py-2 text-sm font-semibold text-neutral-800 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
                                {{ __('Pilih barang untuk melihat nilai taksiran') }}
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Batas Maksimum Pinjaman (94%)') }}</label>
                            <div id="maksPinjamanDisplay" class="rounded-lg border border-dashed border-neutral-300 bg-neutral-50 px-3 py-2 text-sm font-semibold text-neutral-800 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
                                {{ __('Nilai maksimum akan muncul setelah memilih barang') }}
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="tanggal_gadai" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Tanggal Gadai') }}</label>
                            <input
                                type="date"
                                id="tanggal_gadai"
                                name="tanggal_gadai"
                                value="{{ old('tanggal_gadai', $defaultTanggalGadai) }}"
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
                                value="{{ old('jatuh_tempo_awal', $defaultJatuhTempo) }}"
                                required
                                class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                            />
                            @error('jatuh_tempo_awal')
                                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="uang_pinjaman" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Nominal Uang Pinjaman') }}</label>
                            <input
                                type="text"
                                inputmode="decimal"
                                id="uang_pinjaman"
                                name="uang_pinjaman"
                                value="{{ old('uang_pinjaman') }}"
                                placeholder="0,00"
                                required
                                class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
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
                                value="{{ old('biaya_admin') }}"
                                placeholder="0,00"
                                class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                            />
                            @error('biaya_admin')
                                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>

                <section class="space-y-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-300">{{ __('Langkah 3') }}</p>
                        <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Penerbitan Kontrak') }}</h2>
                        <p class="text-sm text-neutral-600 dark:text-neutral-300">
                            {{ __('Periksa kembali informasi di atas sebelum menyimpan. Barang terpilih akan langsung terikat dengan kontrak baru.') }}
                        </p>
                    </div>

                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <a
                            href="{{ route('transaksi-gadai.index') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-400 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-700/60"
                        >
                            {{ __('Batal') }}
                        </a>
                        <button
                            type="submit"
                            @disabled($barangSiapGadai->isEmpty())
                            class="inline-flex items-center justify-center rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 disabled:cursor-not-allowed disabled:border-neutral-300 disabled:bg-neutral-300 disabled:text-neutral-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
                        >
                            {{ __('Simpan Kontrak') }}
                        </button>
                    </div>
                </section>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const barangSelect = document.getElementById('barang_id');
            const taksiranDisplay = document.getElementById('nilaiTaksiranDisplay');
            const maksDisplay = document.getElementById('maksPinjamanDisplay');
            const taksiranPlaceholder = @js(__('Pilih barang untuk melihat nilai taksiran'));
            const maksPlaceholder = @js(__('Nilai maksimum akan muncul setelah memilih barang'));

            const formatCurrency = (value) => {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 2,
                }).format(value || 0);
            };

            const updateDisplay = () => {
                if (!barangSelect || !barangSelect.value) {
                    taksiranDisplay.textContent = taksiranPlaceholder;
                    maksDisplay.textContent = maksPlaceholder;
                    return;
                }

                const option = barangSelect.options[barangSelect.selectedIndex];
                const raw = parseFloat(option.dataset.rawTaksiran || '0');

                taksiranDisplay.textContent = formatCurrency(raw);
                maksDisplay.textContent = formatCurrency(raw * 0.94);
            };

            if (barangSelect) {
                barangSelect.addEventListener('change', updateDisplay);
                updateDisplay();
            }
        });
    </script>
</x-layouts.app>
