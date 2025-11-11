<x-layouts.app :title="__('Laporan Lelang')">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Laporan Lelang') }}</h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                {{ __('Rekap seluruh jadwal dan hasil lelang berikut distribusi dana ke perusahaan maupun nasabah.') }}
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
                <label for="status" class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Status Jadwal') }}</label>
                <select id="status" name="status" class="form-select rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
                    <option value="">{{ __('Semua Status') }}</option>
                    @foreach ($statusOptions as $option)
                        <option value="{{ $option }}" @selected(($filters['status'] ?? null) === $option)>{{ __($option) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label for="search" class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Cari No. SBG / Barang') }}</label>
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
                <button type="reset" onclick="window.location='{{ route('laporan.lelang') }}'" class="rounded-md border border-neutral-300 px-4 py-2 text-sm font-medium text-neutral-700 transition hover:bg-neutral-100 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800">
                    {{ __('Atur Ulang') }}
                </button>
                <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('Terapkan') }}
                </button>
            </div>
        </form>

        <div class="grid gap-3 rounded-lg border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900 md:grid-cols-4">
            <div class="flex flex-col gap-1">
                <span class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Total Harga Laku') }}</span>
                <span class="text-xl font-semibold text-neutral-900 dark:text-white">Rp {{ number_format((float) $summary['total_harga_laku'], 0, ',', '.') }}</span>
            </div>
            <div class="flex flex-col gap-1">
                <span class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Total Biaya Lelang') }}</span>
                <span class="text-xl font-semibold text-neutral-900 dark:text-white">Rp {{ number_format((float) $summary['total_biaya_lelang'], 0, ',', '.') }}</span>
            </div>
            <div class="flex flex-col gap-1">
                <span class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Distribusi ke Perusahaan') }}</span>
                <span class="text-xl font-semibold text-neutral-900 dark:text-white">Rp {{ number_format((float) $summary['total_distribusi_perusahaan'], 0, ',', '.') }}</span>
            </div>
            <div class="flex flex-col gap-1">
                <span class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Distribusi ke Nasabah / Piutang') }}</span>
                <span class="text-xl font-semibold text-neutral-900 dark:text-white">Rp {{ number_format((float) $summary['total_distribusi_nasabah'], 0, ',', '.') }} / Rp {{ number_format((float) $summary['total_piutang_sisa'], 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-50 dark:bg-neutral-800/80">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300">{{ __('Tanggal Rencana') }}</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300">{{ __('Kontrak / Nasabah') }}</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300">{{ __('Barang') }}</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300">{{ __('Status') }}</th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300">{{ __('Harga Laku') }}</th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300">{{ __('Distribusi Perusahaan') }}</th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300">{{ __('Distribusi Nasabah') }}</th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300">{{ __('Piutang Sisa') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse ($jadwalLelang as $jadwal)
                        <tr class="text-sm text-neutral-800 dark:text-neutral-100">
                            <td class="px-4 py-3">{{ optional($jadwal->tanggal_rencana)->translatedFormat('d F Y') ?? __('Belum dijadwalkan') }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $jadwal->transaksi?->no_sbg ?? '—' }}</div>
                                <div class="text-xs text-neutral-500 dark:text-neutral-400">{{ $jadwal->transaksi?->nasabah?->nama ?? __('Nasabah tidak ditemukan') }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $jadwal->barang?->jenis_barang ?? __('Tidak ada data barang') }}</div>
                                <div class="text-xs text-neutral-500 dark:text-neutral-400">{{ $jadwal->barang?->merek }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full bg-neutral-100 px-2 py-0.5 text-xs font-semibold text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">{{ __($jadwal->status) }}</span>
                                @if ($jadwal->hasil_status === 'laku')
                                    <div class="mt-1 text-xs text-emerald-600 dark:text-emerald-300">{{ __('Laku pada :tanggal', ['tanggal' => optional($jadwal->tanggal_selesai)->translatedFormat('d F Y')]) }}</div>
                                @elseif ($jadwal->hasil_status === 'tidak_laku')
                                    <div class="mt-1 text-xs text-rose-600 dark:text-rose-300">{{ __('Belum laku, jadwalkan ulang') }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">{{ $jadwal->harga_laku !== null ? 'Rp ' . number_format((float) $jadwal->harga_laku, 0, ',', '.') : '—' }}</td>
                            <td class="px-4 py-3 text-right">{{ $jadwal->distribusi_perusahaan !== null ? 'Rp ' . number_format((float) $jadwal->distribusi_perusahaan, 0, ',', '.') : '—' }}</td>
                            <td class="px-4 py-3 text-right">{{ $jadwal->distribusi_nasabah !== null ? 'Rp ' . number_format((float) $jadwal->distribusi_nasabah, 0, ',', '.') : '—' }}</td>
                            <td class="px-4 py-3 text-right">{{ $jadwal->piutang_sisa !== null ? 'Rp ' . number_format((float) $jadwal->piutang_sisa, 0, ',', '.') : '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-400">{{ __('Belum ada data lelang yang sesuai filter.') }}</td>
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
