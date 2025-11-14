<x-layouts.app :title="__('Pemberian Kredit Gadai')">
    <div
        class="space-y-8"
        id="pemberian-gadai-page"
        data-initialized="false"
        data-today="{{ ($today ?? now()->toDateString()) }}"
    >
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Pemberian Kredit Gadai') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Ikuti tiga langkah berikut untuk menerbitkan kontrak gadai elektronik berdasarkan barang jaminan yang siap diproses.') }}
            </p>
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            @if ($barangSiapGadai->isEmpty())
                <div class="p-6 text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Belum ada barang dengan status siap gadai. Tambahkan data barang terlebih dahulu di menu Barang Gadai.') }}
                </div>
            @else
                <form method="POST" action="{{ route('gadai.transaksi-gadai.store') }}" class="space-y-8 p-6">
                    @csrf

                    <section class="space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-600 text-sm font-semibold text-white">1</span>
                            <div>
                                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Pilih Barang Jaminan Siap Gadai') }}</h2>
                                <!-- <p class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('Kasir memulai proses dengan memilih aset yang belum terikat kontrak.') }}</p> -->
                            </div>
                        </div>

                        <div class="grid gap-4 lg:grid-cols-2">
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center justify-between gap-2">
                                    <label for="barang_ids" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Barang Jaminan') }}</label>
                                    <label for="barang_search" class="sr-only">{{ __('Cari Barang Jaminan') }}</label>
                                    <input
                                        type="search"
                                        id="barang_search"
                                        placeholder="{{ __('Cari barang…') }}"
                                        class="block w-48 rounded-lg border border-neutral-300 bg-white px-2 py-1 text-xs text-neutral-700 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-200 dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                                    />
                                </div>
                                @php
                                    $barangDipilih = collect(old('barang_ids', []))->map(fn ($id) => (string) $id)->all();
                                @endphp
                                <select
                                    id="barang_ids"
                                    name="barang_ids[]"
                                    required
                                    multiple
                                    size="6"
                                    class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                                >
                                    @foreach ($barangSiapGadai as $barang)
                                        <option
                                            value="{{ $barang->barang_id }}"
                                            data-nilai="{{ $barang->nilai_taksiran }}"
                                            data-deskripsi="{{ $barang->jenis_barang }} — {{ $barang->merek }}"
                                            data-search="{{ strtolower($barang->jenis_barang . ' ' . $barang->merek . ' ' . ($barang->kode_barang ?? '')) }}"
                                            {{ in_array((string) $barang->barang_id, $barangDipilih, true) ? 'selected' : '' }}
                                        >
                                            {{ $barang->jenis_barang }} — {{ $barang->merek }} ({{ __('Taksiran: :amount', ['amount' => number_format((float) $barang->nilai_taksiran, 0, ',', '.')]) }})
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Gunakan kursor untuk memblok langsung atau Ctrl/Cmd + klik untuk memilih lebih dari satu barang.') }}</p>
                                @error('barang_ids')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                @if ($errors->has('barang_ids.*'))
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $errors->first('barang_ids.*') }}</p>
                                @endif
                            </div>

                            <div class="rounded-lg border border-dashed border-emerald-300 bg-emerald-50/70 p-4 text-sm text-emerald-900 dark:border-emerald-500 dark:bg-emerald-500/10 dark:text-emerald-200">
                                <p class="font-semibold">{{ __('Ringkasan Barang Terpilih') }}</p>
                                <dl class="mt-2 space-y-2 text-xs">
                                    <div class="flex justify-between gap-2">
                                        <dt class="text-neutral-600 dark:text-neutral-300">{{ __('Jumlah Barang') }}</dt>
                                        <dd id="ringkasan-jumlah" class="font-semibold text-neutral-900 dark:text-white">0</dd>
                                    </div>
                                    <div class="flex justify-between gap-2">
                                        <dt class="text-neutral-600 dark:text-neutral-300">{{ __('Total Nilai Taksiran') }}</dt>
                                        <dd id="ringkasan-total-nilai" class="font-semibold text-neutral-900 dark:text-white">—</dd>
                                    </div>
                                    <div class="flex justify-between gap-2">
                                        <dt class="text-neutral-600 dark:text-neutral-300">{{ __('Plafon Maksimal (94%)') }}</dt>
                                        <dd id="ringkasan-plafon" class="font-semibold text-neutral-900 dark:text-white">—</dd>
                                    </div>
                                </dl>
                                <div class="mt-3 rounded-lg bg-white/60 p-3 text-xs text-neutral-700 shadow-sm dark:bg-neutral-900/40 dark:text-neutral-200">
                                    <p class="font-semibold">{{ __('Daftar Barang') }}</p>
                                    <ul id="ringkasan-daftar" class="mt-2 space-y-1">
                                        <li class="italic text-neutral-500 dark:text-neutral-400">{{ __('Belum ada barang dipilih.') }}</li>
                                    </ul>
                                </div>
                                <p class="mt-3 text-xs text-neutral-500 dark:text-neutral-400">{{ __('Total nilai taksiran digunakan sebagai acuan batas plafon pinjaman 94%.') }}</p>
                            </div>
                        </div>
                    </section>

                    <section class="space-y-4">
                        <!-- <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-600 text-sm font-semibold text-white">2</span>
                            <div>
                                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Input Detail Kontrak') }}</h2>
                                <p class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('Lengkapi informasi kontrak dan pastikan plafon pinjaman tidak melebihi 94% dari nilai taksiran.') }}</p>
                            </div>
                        </div> -->
<div></div>
                        <div class="grid gap-6 lg:grid-cols-2">
                            <div class="flex flex-col gap-2">
                                <label for="no_sbg" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Nomor SBG') }}</label>
                                <input
                                    type="text"
                                    id="no_sbg"
                                    name="no_sbg"
                                    value="{{ old('no_sbg', $defaultNoSbg) }}"
                                    readonly
                                    class="block w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm font-semibold text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                                />
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Nomor SBG dibuat otomatis dan mengikuti format GE02 + tanggal (YYMMDD) + urutan harian tiga digit.') }}</p>
                            </div>

                            <div class="flex flex-col gap-2">
                                <div class="flex items-center justify-between gap-2">
                                    <label for="nasabah_id" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Nasabah') }}</label>
                                    <label for="nasabah_search" class="sr-only">{{ __('Cari Nasabah') }}</label>
                                    <input
                                        type="search"
                                        id="nasabah_search"
                                        placeholder="{{ __('Cari nasabah…') }}"
                                        class="block w-48 rounded-lg border border-neutral-300 bg-white px-2 py-1 text-xs text-neutral-700 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-200 dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                                    />
                                </div>
                                <select
                                    id="nasabah_id"
                                    name="nasabah_id"
                                    required
                                    class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                                >
                                    <option value="" disabled {{ old('nasabah_id') ? '' : 'selected' }} data-placeholder="true">{{ __('Pilih nasabah') }}</option>
                                    @foreach ($nasabahList as $nasabah)
                                        <option
                                            value="{{ $nasabah->id }}"
                                            data-search="{{ strtolower($nasabah->nama . ' ' . $nasabah->kode_member . ' ' . ($nasabah->kelurahan ?? '') . ' ' . $nasabah->alamat) }}"
                                            {{ (string) old('nasabah_id') === (string) $nasabah->id ? 'selected' : '' }}
                                        >
                                            {{ $nasabah->nama }} — {{ $nasabah->kode_member }} ({{ $nasabah->kelurahan ?? '-' }}) — {{ $nasabah->alamat }}
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
                                    value="{{ old('tanggal_gadai', $today ?? now()->toDateString()) }}"
                                    required
                                    readonly
                                    min="{{ $today ?? now()->toDateString() }}"
                                    max="{{ $today ?? now()->toDateString() }}"
                                    class="block w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-neutral-300 focus:outline-none focus:ring-0 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
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
                                    value="{{ old('jatuh_tempo_awal') }}"
                                    required
                                    class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                                />
                                @error('jatuh_tempo_awal')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="tenor_display" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Tenor (Hari)') }}</label>
                                <input
                                    type="text"
                                    id="tenor_display"
                                    value="—"
                                    readonly
                                    class="block w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-neutral-300 focus:outline-none focus:ring-0 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
                                />
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Tenor dihitung otomatis dari tanggal gadai dan jatuh tempo secara inklusif dengan minimum 1 hari.') }}</p>
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="uang_pinjaman" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Nominal Pinjaman') }}</label>
                                <input
                                    type="text"
                                    inputmode="decimal"
                                    id="uang_pinjaman"
                                    name="uang_pinjaman"
                                    value="{{ old('uang_pinjaman') }}"
                                    required
                                    data-currency-input
                                    class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                                />
                                @error('uang_pinjaman')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="biaya_admin" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Biaya Administrasi') }}</label>
                                <input
                                    type="text"
                                    inputmode="decimal"
                                    id="biaya_admin"
                                    name="biaya_admin"
                                    value="{{ old('biaya_admin') }}"
                                    data-currency-input
                                    class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                                />
                                @error('biaya_admin')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="premi" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Premi') }}</label>
                                <input
                                    type="text"
                                    inputmode="decimal"
                                    id="premi"
                                    name="premi"
                                    value="{{ old('premi') }}"
                                    data-currency-input
                                    class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                                />
                                @error('premi')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="total_potongan_display" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Total Potongan (Admin + Premi)') }}</label>
                                <input
                                    type="text"
                                    id="total_potongan_display"
                                    value="Rp 0"
                                    readonly
                                    class="block w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-neutral-300 focus:outline-none focus:ring-0 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
                                />
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="uang_cair_display" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Uang Cair (Diterima Nasabah)') }}</label>
                                <input
                                    type="text"
                                    id="uang_cair_display"
                                    value="Rp 0"
                                    readonly
                                    class="block w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-neutral-300 focus:outline-none focus:ring-0 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
                                />
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Nilai ini otomatis muncul di nota kontrak sebagai dana bersih yang diterima nasabah.') }}</p>
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="estimasi_bunga_display" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Estimasi Bunga (0,15%/hari)') }}</label>
                                <input
                                    type="text"
                                    id="estimasi_bunga_display"
                                    value="Rp 0"
                                    readonly
                                    class="block w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-neutral-300 focus:outline-none focus:ring-0 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
                                />
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Nilai bunga mengikuti tarif flat 0,15% per hari dikalikan dengan nominal pinjaman dan tenor.') }}</p>
                            </div>
                        </div>
                    </section>

                    <section class="space-y-4">
                        <!-- <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-600 text-sm font-semibold text-white">3</span>
                            <div>
                                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Terbitkan Kontrak & Kunci Barang') }}</h2>
                                <p class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('Setelah disimpan, sistem akan membuat kontrak berstatus Aktif dan mengunci barang agar tidak dapat digunakan ulang.') }}</p>
                            </div>
                        </div> -->

                        <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 text-sm text-neutral-700 dark:border-neutral-700 dark:bg-neutral-900/50 dark:black">
                            <ul class="list-disc space-y-1 pl-5">
                                <li>{{ __('Nominal pinjaman otomatis divalidasi agar tidak melampaui plafon 94% dari nilai taksiran.') }}</li>
                                <li>{{ __('Barang jaminan yang dipilih tidak lagi tampil pada daftar siap gadai setelah SBG terbit.') }}</li>
                                <li>{{ __('Kasir akan diarahkan ke halaman indeks kontrak untuk menindaklanjuti proses berikutnya.') }}</li>
                            </ul>
                        </div>
                    </section>

                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <a
                            href="{{ route('gadai.lihat-gadai') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-400 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-700/60"
                        >
                            {{ __('Batal') }}
                        </a>
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-blue-600 shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
                        >
                            {{ __('Simpan Transaksi') }}
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    @if (! $barangSiapGadai->isEmpty())
        <script>
            // Namespace global aman (tanpa redeclare)
            window.KRESNO = window.KRESNO || {};
            window.KRESNO.gadaiPage ??= {
                init() {
                    const root = document.getElementById('pemberian-gadai-page');
                    if (!root || root.dataset.initialized === 'true') return;
                    root.dataset.initialized = 'true';

                    const select = document.getElementById('barang_ids');
                    const ringkasanJumlah = document.getElementById('ringkasan-jumlah');
                    const ringkasanTotal = document.getElementById('ringkasan-total-nilai');
                    const ringkasanPlafon = document.getElementById('ringkasan-plafon');
                    const ringkasanDaftar = document.getElementById('ringkasan-daftar');
                    const tanggalGadaiInput = document.getElementById('tanggal_gadai');
                    const jatuhTempoInput = document.getElementById('jatuh_tempo_awal');
                    const pinjamanInput = document.getElementById('uang_pinjaman');
                    const biayaAdminInput = document.getElementById('biaya_admin');
                    const premiInput = document.getElementById('premi');
                    const tenorDisplay = document.getElementById('tenor_display');
                    const bungaDisplay = document.getElementById('estimasi_bunga_display');
                    const totalPotonganDisplay = document.getElementById('total_potongan_display');
                    const uangCairDisplay = document.getElementById('uang_cair_display');
                    const barangSearchInput = document.getElementById('barang_search');
                    const nasabahSelect = document.getElementById('nasabah_id');
                    const nasabahSearchInput = document.getElementById('nasabah_search');

                    if (!select) return;

                    const formatCurrency = (value) => {
                        if (value === null || value === undefined || value === '') return '—';
                        const number = Number.parseFloat(value);
                        if (Number.isNaN(number)) return '—';
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0,
                        }).format(number);
                    };

                    const parseDecimal = (rawValue) => {
                        if (rawValue === null || rawValue === undefined) return 0;
                        if (typeof rawValue !== 'string') {
                            const numeric = Number(rawValue);
                            return Number.isNaN(numeric) ? 0 : numeric;
                        }

                        let value = rawValue.trim();
                        if (value === '') return 0;

                        value = value.replace(/[^0-9,.-]/g, '');
                        const lastComma = value.lastIndexOf(',');
                        const lastDot = value.lastIndexOf('.');

                        if (lastComma !== -1 && lastDot !== -1) {
                            if (lastComma > lastDot) {
                                value = value.replace(/\./g, '');
                                value = value.replace(/,/g, '.');
                            } else {
                                value = value.replace(/,/g, '');
                            }
                        } else if (lastComma !== -1) {
                            value = value.replace(/\./g, '');
                            value = value.replace(/,/g, '.');
                        } else {
                            value = value.replace(/,/g, '');
                        }

                        const parsed = Number.parseFloat(value);
                        return Number.isNaN(parsed) ? 0 : parsed;
                    };

                    const MILLISECONDS_PER_DAY = 24 * 60 * 60 * 1000;

                    const isInvalidDate = (date) => !(date instanceof Date) || Number.isNaN(date.getTime());

                    const calculateActualDays = (startDate, endDate) => {
                        if (isInvalidDate(startDate) || isInvalidDate(endDate)) {
                            return 0;
                        }

                        const startUtc = Date.UTC(
                            startDate.getFullYear(),
                            startDate.getMonth(),
                            startDate.getDate(),
                        );
                        const endUtc = Date.UTC(
                            endDate.getFullYear(),
                            endDate.getMonth(),
                            endDate.getDate(),
                        );

                        if (endUtc < startUtc) {
                            return 0;
                        }

                        const diffDays = Math.floor((endUtc - startUtc) / MILLISECONDS_PER_DAY);
                        return Math.max(1, diffDays + 1);
                    };

                    let totalNilaiTerpilih = 0;
                    const emptyListMessage = @json(__('Belum ada barang dipilih.'));

                    const filterSelectOptions = (inputEl, selectEl) => {
                        if (!inputEl || !selectEl) return;

                        const term = inputEl.value.trim().toLowerCase();
                        Array.from(selectEl.options).forEach((option) => {
                            if (!option) return;

                            if (!option.value && option.dataset.placeholder === 'true') {
                                option.hidden = false;
                                return;
                            }

                            const searchable = (option.dataset.search ?? option.textContent ?? '').toLowerCase();
                            option.hidden = term !== '' && !searchable.includes(term);
                        });
                    };

                    const updateSummary = () => {
                        const options = Array.from(select?.selectedOptions ?? []).filter((option) => option.value);
                        totalNilaiTerpilih = 0;

                        if (!ringkasanJumlah || !ringkasanTotal || !ringkasanPlafon || !ringkasanDaftar) {
                            return;
                        }

                        ringkasanJumlah.textContent = options.length.toString();

                        if (options.length === 0) {
                            ringkasanTotal.textContent = '—';
                            ringkasanPlafon.textContent = '—';
                            ringkasanDaftar.innerHTML = `<li class="italic text-neutral-500 dark:text-neutral-400">${emptyListMessage}</li>`;
                            select?.setAttribute('data-total-nilai', '0');
                            return;
                        }

                        ringkasanDaftar.innerHTML = '';

                        options.forEach((option) => {
                            const nilai = parseDecimal(option.dataset.nilai ?? '0');
                            totalNilaiTerpilih += nilai;

                            const item = document.createElement('li');
                            item.className = 'rounded-md bg-emerald-100/70 px-3 py-2 text-neutral-700 dark:bg-emerald-500/10 dark:text-neutral-100';
                            item.innerHTML = `<span class="font-semibold text-neutral-900 dark:text-white">${option.dataset.deskripsi ?? option.textContent ?? ''}</span><div>${formatCurrency(nilai)}</div>`;
                            ringkasanDaftar.appendChild(item);
                        });

                        ringkasanTotal.textContent = formatCurrency(totalNilaiTerpilih);
                        ringkasanPlafon.textContent = formatCurrency(totalNilaiTerpilih * 0.94);
                        select?.setAttribute('data-total-nilai', totalNilaiTerpilih.toString());
                    };

                    const updateBunga = () => {
                        if (!tenorDisplay || !bungaDisplay) return;

                        const tanggalGadai = tanggalGadaiInput?.value ? new Date(tanggalGadaiInput.value) : null;
                        const jatuhTempo = jatuhTempoInput?.value ? new Date(jatuhTempoInput.value) : null;
                        const ratePerDay = 0.0015;
                        const tenor = calculateActualDays(tanggalGadai, jatuhTempo);

                        tenorDisplay.value = tenor > 0 ? `${tenor} hari` : '—';

                        const pinjaman = parseDecimal(pinjamanInput?.value ?? '');
                        const adminCost = parseDecimal(biayaAdminInput?.value ?? '');
                        const premiCost = parseDecimal(premiInput?.value ?? '');
                        const totalPotongan = Math.max(0, adminCost + premiCost);
                        const uangCair = Math.max(0, pinjaman - totalPotongan);
                        const bunga = tenor > 0 && pinjaman > 0 ? pinjaman * ratePerDay * tenor : 0;

                        bungaDisplay.value = tenor > 0 && pinjaman > 0 ? formatCurrency(bunga) : formatCurrency(0);
                        if (totalPotonganDisplay) {
                            totalPotonganDisplay.value = formatCurrency(totalPotongan);
                        }
                        if (uangCairDisplay) {
                            uangCairDisplay.value = formatCurrency(uangCair);
                        }
                    };

                    select.addEventListener('change', () => {
                        updateSummary();
                        updateBunga();
                    });
                    barangSearchInput?.addEventListener('input', () => {
                        filterSelectOptions(barangSearchInput, select);
                    });
                    nasabahSearchInput?.addEventListener('input', () => {
                        filterSelectOptions(nasabahSearchInput, nasabahSelect);
                    });
                    tanggalGadaiInput?.addEventListener('change', updateBunga);
                    jatuhTempoInput?.addEventListener('change', updateBunga);
                    pinjamanInput?.addEventListener('input', updateBunga);
                    biayaAdminInput?.addEventListener('input', updateBunga);
                    premiInput?.addEventListener('input', updateBunga);

                    updateSummary();
                    updateBunga();
                    filterSelectOptions(barangSearchInput, select);
                    filterSelectOptions(nasabahSearchInput, nasabahSelect);
                    window.KRESNO?.initCurrencyInputs?.();
                }
            };

            // Boot pertama + saat Livewire navigasi
            (function bootGadaiPage() {
                const run = () => window.KRESNO.gadaiPage.init?.();
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', run, { once: true });
                } else {
                    run();
                }
                document.addEventListener('livewire:navigated', () => {
                    // reset guard agar halaman ini bisa re-init saat kembali dari halaman lain
                    const root = document.getElementById('pemberian-gadai-page');
                    if (root) root.dataset.initialized = 'false';
                    run();
                });
            })();
        </script>
    @endif
</x-layouts.app>
