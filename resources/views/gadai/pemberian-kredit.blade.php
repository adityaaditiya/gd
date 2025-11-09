<x-layouts.app :title="__('Pemberian Kredit Gadai')">
    <div class="space-y-8">
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
                                <p class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('Kasir memulai proses dengan memilih aset yang belum terikat kontrak.') }}</p>
                            </div>
                        </div>

                        <div class="grid gap-4 lg:grid-cols-2">
                            <div class="flex flex-col gap-2">
                                <label for="barang_id" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Barang Jaminan') }}</label>
                                <select
                                    id="barang_id"
                                    name="barang_id"
                                    required
                                    class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                                >
                                    <option value="" disabled {{ old('barang_id') ? '' : 'selected' }}>{{ __('Pilih barang siap gadai') }}</option>
                                    @foreach ($barangSiapGadai as $barang)
                                        <option
                                            value="{{ $barang->barang_id }}"
                                            data-nilai="{{ $barang->nilai_taksiran }}"
                                            data-deskripsi="{{ $barang->jenis_barang }} — {{ $barang->merek }}"
                                            {{ (string) old('barang_id') === (string) $barang->barang_id ? 'selected' : '' }}
                                        >
                                            {{ $barang->jenis_barang }} — {{ $barang->merek }} ({{ __('Taksiran: :amount', ['amount' => number_format((float) $barang->nilai_taksiran, 2, ',', '.')]) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('barang_id')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="rounded-lg border border-dashed border-emerald-300 bg-emerald-50/70 p-4 text-sm text-emerald-900 dark:border-emerald-500 dark:bg-emerald-500/10 dark:text-emerald-200">
                                <p class="font-semibold">{{ __('Ringkasan Barang Terpilih') }}</p>
                                <dl class="mt-2 space-y-1">
                                    <div class="flex justify-between gap-2">
                                        <dt class="text-neutral-600 dark:text-neutral-300">{{ __('Deskripsi') }}</dt>
                                        <dd id="ringkasan-deskripsi" class="font-medium text-neutral-900 dark:text-white">—</dd>
                                    </div>
                                    <div class="flex justify-between gap-2">
                                        <dt class="text-neutral-600 dark:text-neutral-300">{{ __('Nilai Taksiran') }}</dt>
                                        <dd id="ringkasan-nilai" class="font-medium text-neutral-900 dark:text-white">—</dd>
                                    </div>
                                </dl>
                                <p class="mt-3 text-xs text-neutral-500 dark:text-neutral-400">{{ __('Nilai taksiran otomatis digunakan sebagai acuan batas plafon pinjaman.') }}</p>
                            </div>
                        </div>
                    </section>

                    <section class="space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-600 text-sm font-semibold text-white">2</span>
                            <div>
                                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Input Detail Kontrak') }}</h2>
                                <p class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('Lengkapi informasi kontrak dan pastikan plafon pinjaman tidak melebihi 94% dari nilai taksiran.') }}</p>
                            </div>
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
                                    required
                                    class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                                >
                                    <option value="" disabled {{ old('nasabah_id') ? '' : 'selected' }}>{{ __('Pilih nasabah') }}</option>
                                    @foreach ($nasabahList as $nasabah)
                                        <option value="{{ $nasabah->id }}" {{ (string) old('nasabah_id') === (string) $nasabah->id ? 'selected' : '' }}>
                                            {{ $nasabah->nama }} — {{ $nasabah->kode_member }}
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
                                    value="{{ old('tanggal_gadai') }}"
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
                                    value="{{ old('jatuh_tempo_awal') }}"
                                    required
                                    class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                                />
                                @error('jatuh_tempo_awal')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
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
                                    class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                                />
                                @error('biaya_admin')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </section>

                    <section class="space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-600 text-sm font-semibold text-white">3</span>
                            <div>
                                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Terbitkan Kontrak & Kunci Barang') }}</h2>
                                <p class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('Setelah disimpan, sistem akan membuat kontrak berstatus Aktif dan mengunci barang agar tidak dapat digunakan ulang.') }}</p>
                            </div>
                        </div>

                        <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 text-sm text-neutral-700 dark:border-neutral-700 dark:bg-neutral-900/50 dark:text-neutral-200">
                            <ul class="list-disc space-y-1 pl-5">
                                <li>{{ __('Nominal pinjaman otomatis divalidasi agar tidak melampaui plafon 94% dari nilai taksiran.') }}</li>
                                <li>{{ __('Barang jaminan yang dipilih tidak lagi tampil pada daftar siap gadai setelah kontrak terbit.') }}</li>
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
                            class="inline-flex items-center justify-center rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
                        >
                            {{ __('Terbitkan Kontrak') }}
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    @if (! $barangSiapGadai->isEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const select = document.getElementById('barang_id');
                const ringkasanDeskripsi = document.getElementById('ringkasan-deskripsi');
                const ringkasanNilai = document.getElementById('ringkasan-nilai');

                if (!select) {
                    return;
                }

                const formatCurrency = (value) => {
                    if (!value) {
                        return '—';
                    }

                    const number = Number.parseFloat(value);

                    if (Number.isNaN(number)) {
                        return '—';
                    }

                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 2,
                    }).format(number);
                };

                const updateSummary = () => {
                    const option = select.options[select.selectedIndex];

                    if (!option || !option.value) {
                        ringkasanDeskripsi.textContent = '—';
                        ringkasanNilai.textContent = '—';
                        return;
                    }

                    ringkasanDeskripsi.textContent = option.dataset.deskripsi ?? '—';
                    ringkasanNilai.textContent = formatCurrency(option.dataset.nilai);
                };

                select.addEventListener('change', updateSummary);
                updateSummary();
            });
        </script>
    @endif
</x-layouts.app>
