<x-layouts.app :title="__('Lihat Data Lelang')">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Lihat Data Lelang') }}</h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                {{ __('Pantau penjadwalan, pelaksanaan, dan hasil distribusi dana untuk setiap barang gadai yang mengikuti proses lelang.') }}
            </p>
        </div>

        @if (session('status'))
            <div class="rounded-md border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950 dark:text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-md border border-rose-200 bg-rose-50 p-4 text-rose-800 dark:border-rose-900 dark:bg-rose-950 dark:text-rose-200">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="GET" class="grid grid-cols-1 gap-4 rounded-lg border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900 md:grid-cols-4">
            <div class="flex flex-col gap-1">
                <label for="status" class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Status Jadwal') }}</label>
                <select id="status" name="status" class="form-select w-full rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
                    <option value="">{{ __('Semua Status') }}</option>
                    @foreach ($statusOptions as $option)
                        <option value="{{ $option }}" @selected($statusFilter === $option)>{{ __($option) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col gap-1 md:col-span-2">
                <label for="search" class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Cari No. SBG / Nasabah / Barang') }}</label>
                <input id="search" name="search" type="search" value="{{ $search }}" class="form-input w-full rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white" placeholder="{{ __('Masukkan kata kunci...') }}">
            </div>
            <div class="flex flex-col gap-1">
                <label for="per_page" class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Data per halaman') }}</label>
                <select id="per_page" name="per_page" class="form-select w-full rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
                    @foreach ([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" @selected($perPage === $size)>{{ $size }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-4 flex items-center justify-end gap-2">
                <button type="reset" class="rounded-md border border-neutral-300 px-4 py-2 text-sm font-medium text-neutral-700 transition hover:bg-neutral-100 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800" onclick="window.location='{{ route('gadai.lihat-data-lelang') }}'">
                    {{ __('Atur Ulang') }}
                </button>
                <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('Terapkan') }}
                </button>
            </div>
        </form>

        <div class="overflow-x-auto rounded-lg border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Kontrak & Barang') }}</th>
                        <th scope="col" class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Jadwal & Penanggung Jawab') }}</th>
                        <th scope="col" class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Limit & Biaya') }}</th>
                        <th scope="col" class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Hasil & Distribusi') }}</th>
                        <th scope="col" class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse ($jadwalLelang as $jadwal)
                        @php
                            $transaksi = $jadwal->transaksi;
                            $barang = $jadwal->barang;
                            $nasabah = $transaksi?->nasabah;
                        @endphp
                        <tr class="align-top">
                            <td class="px-4 py-3 text-sm text-neutral-800 dark:text-neutral-100">
                                <div class="font-semibold">{{ $transaksi?->no_sbg ?? __('Tanpa Nomor SBG') }}</div>
                                <div class="text-neutral-600 dark:text-neutral-400">
                                    {{ $nasabah?->nama ?? __('Nasabah tidak ditemukan') }}
                                </div>
                                <div class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                                    {{ __('Barang') }}: {{ $barang?->jenis_barang }} {{ $barang?->merek ? '— ' . $barang->merek : '' }}
                                </div>
                                <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                    {{ __('Status Kontrak') }}: <span class="font-medium text-indigo-600 dark:text-indigo-300">{{ __($transaksi?->status_transaksi ?? 'Aktif') }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-800 dark:text-neutral-100">
                                <div class="flex flex-col gap-1">
                                    <span class="font-medium">{{ optional($jadwal->tanggal_rencana)->translatedFormat('d F Y') ?? __('Belum Dijadwalkan') }}</span>
                                    <span class="text-neutral-600 dark:text-neutral-400">{{ $jadwal->lokasi ?? __('Lokasi belum diisi') }}</span>
                                    <span class="text-neutral-600 dark:text-neutral-400">{{ $jadwal->petugas ? __('Petugas: :nama', ['nama' => $jadwal->petugas]) : __('Petugas belum ditetapkan') }}</span>
                                    <span class="inline-flex w-fit items-center rounded-full bg-neutral-100 px-2 py-0.5 text-xs font-medium text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">{{ __($jadwal->status) }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-800 dark:text-neutral-100">
                                <div>{{ __('Limit Harga') }}: <span class="font-semibold">{{ $jadwal->harga_limit !== null ? 'Rp ' . number_format((float) $jadwal->harga_limit, 0, ',', '.') : __('-') }}</span></div>
                                <div>{{ __('Estimasi Biaya') }}: <span class="font-semibold">{{ $jadwal->estimasi_biaya !== null ? 'Rp ' . number_format((float) $jadwal->estimasi_biaya, 0, ',', '.') : __('-') }}</span></div>
                                <div class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">{{ $jadwal->catatan ?: __('Catatan belum tersedia.') }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-800 dark:text-neutral-100">
                                @if ($jadwal->hasil_status === 'laku')
                                    <div>{{ __('Harga Laku') }}: <span class="font-semibold">Rp {{ number_format((float) $jadwal->harga_laku, 0, ',', '.') }}</span></div>
                                    <div>{{ __('Biaya Lelang') }}: <span class="font-semibold">Rp {{ number_format((float) $jadwal->biaya_lelang, 0, ',', '.') }}</span></div>
                                    <div>{{ __('Ke Perusahaan') }}: <span class="font-semibold">Rp {{ number_format((float) $jadwal->distribusi_perusahaan, 0, ',', '.') }}</span></div>
                                    <div>{{ __('Ke Nasabah') }}: <span class="font-semibold">Rp {{ number_format((float) $jadwal->distribusi_nasabah, 0, ',', '.') }}</span></div>
                                    <div>{{ __('Piutang Sisa') }}: <span class="font-semibold">Rp {{ number_format((float) $jadwal->piutang_sisa, 0, ',', '.') }}</span></div>
                                @elseif ($jadwal->hasil_status === 'tidak_laku')
                                    <div class="font-medium text-rose-600 dark:text-rose-300">{{ __('Belum laku') }}</div>
                                    <div class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Jadwalkan ulang untuk kesempatan berikutnya.') }}</div>
                                @else
                                    <div class="text-neutral-500 dark:text-neutral-400">{{ __('Belum ada hasil lelang.') }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-800 dark:text-neutral-100">
                                <details class="group">
                                    <summary class="cursor-pointer select-none rounded-md border border-neutral-300 px-3 py-1.5 text-sm font-medium text-neutral-700 transition hover:bg-neutral-100 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800">
                                        {{ __('Kelola Jadwal') }}
                                    </summary>
                                    <div class="mt-3 space-y-4 rounded-md border border-dashed border-neutral-300 p-3 dark:border-neutral-700">
                                        <form action="{{ route('gadai.jadwal-lelang.update', $jadwal) }}" method="POST" class="space-y-3">
                                            @csrf
                                            @method('PUT')
                                            <div class="grid grid-cols-1 gap-3">
                                                <label class="flex flex-col gap-1 text-xs font-medium">
                                                    <span>{{ __('Tanggal Rencana') }}</span>
                                                    <input type="date" name="tanggal_rencana" value="{{ old('tanggal_rencana', optional($jadwal->tanggal_rencana)->toDateString()) }}" class="form-input rounded-md border-neutral-300 text-sm dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
                                                </label>
                                                <label class="flex flex-col gap-1 text-xs font-medium">
                                                    <span>{{ __('Lokasi') }}</span>
                                                    <input type="text" name="lokasi" value="{{ old('lokasi', $jadwal->lokasi) }}" class="form-input rounded-md border-neutral-300 text-sm dark:border-neutral-600 dark:bg-neutral-900 dark:text-white" placeholder="{{ __('Tulis lokasi lelang') }}">
                                                </label>
                                                <label class="flex flex-col gap-1 text-xs font-medium">
                                                    <span>{{ __('Petugas Penanggung Jawab') }}</span>
                                                    <input type="text" name="petugas" value="{{ old('petugas', $jadwal->petugas) }}" class="form-input rounded-md border-neutral-300 text-sm dark:border-neutral-600 dark:bg-neutral-900 dark:text-white" placeholder="{{ __('Nama petugas') }}">
                                                </label>
                                                <label class="flex flex-col gap-1 text-xs font-medium">
                                                    <span>{{ __('Harga Limit') }}</span>
                                                    <input type="text" inputmode="decimal" name="harga_limit" value="{{ old('harga_limit', $jadwal->harga_limit) }}" class="form-input rounded-md border-neutral-300 text-sm dark:border-neutral-600 dark:bg-neutral-900 dark:text-white" data-currency-input>
                                                </label>
                                                <label class="flex flex-col gap-1 text-xs font-medium">
                                                    <span>{{ __('Estimasi Biaya') }}</span>
                                                    <input type="text" inputmode="decimal" name="estimasi_biaya" value="{{ old('estimasi_biaya', $jadwal->estimasi_biaya) }}" class="form-input rounded-md border-neutral-300 text-sm dark:border-neutral-600 dark:bg-neutral-900 dark:text-white" data-currency-input>
                                                </label>
                                                <label class="flex flex-col gap-1 text-xs font-medium">
                                                    <span>{{ __('Catatan') }}</span>
                                                    <textarea name="catatan" rows="3" class="form-textarea rounded-md border-neutral-300 text-sm dark:border-neutral-600 dark:bg-neutral-900 dark:text-white" placeholder="{{ __('Tambahkan keterangan tambahan') }}">{{ old('catatan', $jadwal->catatan) }}</textarea>
                                                </label>
                                            </div>
                                            <button type="submit" class="w-full rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                                {{ __('Simpan Jadwal') }}
                                            </button>
                                        </form>

                                        <form action="{{ route('gadai.jadwal-lelang.finalize', $jadwal) }}" method="POST" class="space-y-3 border-t border-dashed border-neutral-300 pt-3 dark:border-neutral-700">
                                            @csrf
                                            <div class="flex flex-col gap-2">
                                                <span class="text-xs font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Catat Hasil Lelang') }}</span>
                                                <label class="flex items-center gap-2 text-xs font-medium">
                                                    <input type="radio" name="hasil_status" value="laku" @checked(old('hasil_status', $jadwal->hasil_status) === 'laku') class="text-indigo-600">
                                                    <span>{{ __('Barang Laku Terjual') }}</span>
                                                </label>
                                                <label class="flex items-center gap-2 text-xs font-medium">
                                                    <input type="radio" name="hasil_status" value="tidak_laku" @checked(old('hasil_status', $jadwal->hasil_status) === 'tidak_laku') class="text-indigo-600">
                                                    <span>{{ __('Belum Laku / Dijadwalkan Ulang') }}</span>
                                                </label>
                                            </div>
                                            <div class="grid grid-cols-1 gap-3">
                                                <label class="flex flex-col gap-1 text-xs font-medium">
                                                    <span>{{ __('Harga Laku') }}</span>
                                                    <input type="text" inputmode="decimal" name="harga_laku" value="{{ old('harga_laku', $jadwal->harga_laku) }}" class="form-input rounded-md border-neutral-300 text-sm dark:border-neutral-600 dark:bg-neutral-900 dark:text-white" data-currency-input>
                                                </label>
                                                <label class="flex flex-col gap-1 text-xs font-medium">
                                                    <span>{{ __('Biaya Lelang Aktual') }}</span>
                                                    <input type="text" inputmode="decimal" name="biaya_lelang" value="{{ old('biaya_lelang', $jadwal->biaya_lelang) }}" class="form-input rounded-md border-neutral-300 text-sm dark:border-neutral-600 dark:bg-neutral-900 dark:text-white" data-currency-input>
                                                </label>
                                                <label class="flex flex-col gap-1 text-xs font-medium">
                                                    <span>{{ __('Catatan Hasil') }}</span>
                                                    <textarea name="catatan_hasil" rows="3" class="form-textarea rounded-md border-neutral-300 text-sm dark:border-neutral-600 dark:bg-neutral-900 dark:text-white" placeholder="{{ __('Ringkasan jalannya lelang') }}">{{ old('catatan_hasil', $jadwal->catatan) }}</textarea>
                                                </label>
                                            </div>
                                            <button type="submit" class="w-full rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                {{ __('Simpan Hasil') }}
                                            </button>
                                        </form>
                                    </div>
                                </details>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-400">{{ __('Belum ada data lelang yang dapat ditampilkan.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $jadwalLelang->links() }}
        </div>
    </div>
</x-layouts.app>
