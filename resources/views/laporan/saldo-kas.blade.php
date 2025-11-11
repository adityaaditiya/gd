<x-layouts.app :title="__('Laporan Saldo Kas')">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Laporan Saldo Kas') }}</h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                {{ __('Pantau mutasi kas masuk dan keluar termasuk hasil distribusi lelang dalam periode tertentu.') }}
            </p>
        </div>

        <form method="GET" class="grid grid-cols-1 gap-4 rounded-lg border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900 md:grid-cols-5">
            <div class="flex flex-col gap-1">
                <label for="tanggal_dari" class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Tanggal Dari') }}</label>
                <input id="tanggal_dari" name="tanggal_dari" type="date" value="{{ $filters['tanggal_dari'] ?? '' }}" class="form-input rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
            </div>
            <div class="flex flex-col gap-1">
                <label for="tanggal_sampai" class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Tanggal Sampai') }}</label>
                <input id="tanggal_sampai" name="tanggal_sampai" type="date" value="{{ $filters['tanggal_sampai'] ?? '' }}" class="form-input rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
            </div>
            <div class="flex flex-col gap-1">
                <label for="tipe" class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Tipe Mutasi') }}</label>
                <select id="tipe" name="tipe" class="form-select rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
                    <option value="">{{ __('Semua Tipe') }}</option>
                    @foreach ($tipeOptions as $option)
                        <option value="{{ $option }}" @selected(($filters['tipe'] ?? null) === $option)>{{ __($option) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label for="search" class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Cari Referensi / Keterangan') }}</label>
                <input id="search" name="search" type="search" value="{{ $filters['search'] ?? '' }}" class="form-input rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white" placeholder="{{ __('Masukkan kata kunci') }}">
            </div>
            <div class="flex flex-col gap-1">
                <label for="per_page" class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Data per halaman') }}</label>
                <select id="per_page" name="per_page" class="form-select rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
                    @foreach ([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" @selected(($filters['per_page'] ?? 25) == $size)>{{ $size }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-5 flex items-center justify-end gap-2">
                <button type="reset" onclick="window.location='{{ route('laporan.saldo-kas') }}'" class="rounded-md border border-neutral-300 px-4 py-2 text-sm font-medium text-neutral-700 transition hover:bg-neutral-100 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800">
                    {{ __('Atur Ulang') }}
                </button>
                <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('Terapkan') }}
                </button>
            </div>
        </form>

        <div class="grid gap-3 rounded-lg border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900 md:grid-cols-3">
            <div class="flex flex-col gap-1">
                <span class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Total Masuk') }}</span>
                <span class="text-xl font-semibold text-emerald-600 dark:text-emerald-300">Rp {{ number_format((float) $totalMasuk, 0, ',', '.') }}</span>
            </div>
            <div class="flex flex-col gap-1">
                <span class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Total Keluar') }}</span>
                <span class="text-xl font-semibold text-rose-600 dark:text-rose-300">Rp {{ number_format((float) $totalKeluar, 0, ',', '.') }}</span>
            </div>
            <div class="flex flex-col gap-1">
                <span class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Saldo Bersih Periode') }}</span>
                <span class="text-xl font-semibold text-neutral-900 dark:text-white">Rp {{ number_format((float) $saldo, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-50 dark:bg-neutral-800/80">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300">{{ __('Tanggal') }}</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300">{{ __('Referensi') }}</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300">{{ __('Keterangan') }}</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300">{{ __('Sumber') }}</th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300">{{ __('Masuk') }}</th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300">{{ __('Keluar') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse ($mutasiKas as $mutasi)
                        <tr class="text-sm text-neutral-800 dark:text-neutral-100">
                            <td class="px-4 py-3">{{ optional($mutasi->tanggal)->translatedFormat('d F Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $mutasi->referensi }}</div>
                                @if ($mutasi->jadwalLelang)
                                    <div class="text-xs text-neutral-500 dark:text-neutral-400">{{ $mutasi->jadwalLelang->transaksi?->no_sbg }} — {{ $mutasi->jadwalLelang->barang?->jenis_barang }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $mutasi->keterangan ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $mutasi->sumber ?? '—' }}</td>
                            <td class="px-4 py-3 text-right">{{ $mutasi->tipe === 'masuk' ? 'Rp ' . number_format((float) $mutasi->jumlah, 0, ',', '.') : '—' }}</td>
                            <td class="px-4 py-3 text-right">{{ $mutasi->tipe === 'keluar' ? 'Rp ' . number_format((float) $mutasi->jumlah, 0, ',', '.') : '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-400">{{ __('Belum ada data mutasi kas pada periode ini.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $mutasiKas->links() }}
        </div>
    </div>
</x-layouts.app>
