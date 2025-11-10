<x-layouts.app :title="__('Lihat Barang Gadai')">
    <div class="space-y-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Data Barang Jaminan') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Pantau seluruh barang elektronik yang digadaikan lengkap dengan detail kontrak, petugas, dan estimasi nilai.') }}
            </p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-black-700 shadow-sm dark:border-black-500/60 dark:bg-black-500/10 dark:text-black">
                {{ session('status') }}
            </div>
        @endif

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form
                method="GET"
                action="{{ route('gadai.lihat-barang-gadai') }}"
                class="flex flex-wrap items-center gap-2 text-sm"
            >
                <input type="hidden" name="per_page" value="{{ $perPage }}">
                <label for="status" class="text-neutral-600 dark:text-neutral-300">{{ __('Filter Status') }}</label>
                <select
                    id="status"
                    name="status"
                    class="rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-700 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                    onchange="this.form.requestSubmit()"
                >
                    <option value="">{{ __('Semua Status') }}</option>
                    @foreach ($statusOptions as $option)
                        <option value="{{ $option }}" @selected($statusFilter === $option)>
                            {{ __($option) }}
                        </option>
                    @endforeach
                </select>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-lg border border-emerald-600 px-3 py-2 font-semibold text-emerald-700 transition hover:bg-emerald-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-400 dark:text-emerald-300 dark:hover:bg-emerald-500/10"
                >
                    {{ __('Terapkan') }}
                </button>
                @if ($statusFilter)
                    <a
                        href="{{ route('gadai.lihat-barang-gadai', ['per_page' => $perPage]) }}"
                        class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-3 py-2 font-semibold text-neutral-600 transition hover:bg-neutral-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-400 dark:border-neutral-500 dark:text-neutral-300 dark:hover:bg-neutral-700/60"
                    >
                        {{ __('Reset') }}
                    </a>
                @endif
            </form>
            <a
                href="{{ route('gadai.barang-jaminan.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-blue-600 shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
            >
                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>{{ __('Tambah Data') }}</span>
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <table class="min-w-full divide-y divide-neutral-200 text-left text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200">
                <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">{{ __('Aksi') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('No. SBG') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Nasabah') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Jenis Barang') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Merek') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Tahun Pembuatan') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Harga Pasar Setempat') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Nilai Taksiran') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Uang Pinjaman') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Tenor (Hari)') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Bunga Terhitung') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Status') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Petugas') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Tanggal Gadai') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Kondisi Fisik') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Kelengkapan') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Foto') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                    @forelse ($barangJaminan as $barang)
                        <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/70">
                            <td class="whitespace-nowrap px-4 py-3">
                                @php
                                    $statusTransaksi = $barang->transaksi?->status_transaksi;
                                @endphp
                                @if ($statusTransaksi === 'Aktif')
                                    <span class="text-xs font-medium text-neutral-400 dark:text-neutral-500">{{ __('Tidak tersedia') }}</span>
                                @else
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a
                                            href="{{ route('gadai.barang-jaminan.edit', $barang) }}"
                                            class="inline-flex items-center justify-center rounded-lg border border-emerald-600 px-3 py-1 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50 dark:border-emerald-400 dark:text-emerald-300 dark:hover:bg-emerald-500/10"
                                        >
                                            {{ __('Ubah') }}
                                        </a>
                                        <form
                                            method="POST"
                                            action="{{ route('gadai.barang-jaminan.destroy', $barang) }}"
                                            onsubmit="return confirm('{{ __('Apakah Anda yakin ingin menghapus data ini?') }}');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center rounded-lg border border-red-500 px-3 py-1 text-xs font-semibold text-red-600 transition hover:bg-red-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 dark:border-red-400 dark:text-red-300 dark:hover:bg-red-500/10"
                                            >
                                                {{ __('Hapus') }}
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 font-semibold text-neutral-900 dark:text-white">{{ $barang->transaksi?->no_sbg ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="font-medium text-neutral-900 dark:text-white">{{ $barang->transaksi?->nasabah?->nama ?? '—' }}</span>
                                    <span class="text-xs text-neutral-500 dark:text-neutral-300">{{ $barang->transaksi?->nasabah?->kode_member ?? '' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $barang->jenis_barang }}</td>
                            <td class="px-4 py-3">{{ $barang->merek }}</td>
                            <td class="px-4 py-3">{{ $barang->usia_barang_thn ? $barang->usia_barang_thn . ' ' . __('th') : '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-3">Rp {{ number_format((float) $barang->hps, 0, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-4 py-3 font-semibold text-emerald-600 dark:text-emerald-300">Rp {{ number_format((float) $barang->nilai_taksiran, 0, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-4 py-3">Rp {{ number_format((float) ($barang->transaksi?->uang_pinjaman ?? 0), 0, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-4 py-3">{{ $barang->transaksi?->tenor_hari ? $barang->transaksi->tenor_hari . ' ' . __('hari') : '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-3">Rp {{ number_format((float) ($barang->transaksi?->total_bunga ?? 0), 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-neutral-700 dark:bg-black-700/60 dark:text-black-100">
                                    {{ __($barang->transaksi?->status_transaksi ?? 'Belum Aktif') }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-1">
                                    <span class="text-xs text-neutral-500 dark:text-neutral-300">{{ __('Kasir:') }} {{ $barang->transaksi?->kasir?->name ?? '—' }}</span>
                                    <span class="text-xs text-neutral-500 dark:text-neutral-300">{{ __('Penaksir:') }} {{ $barang->penaksir?->name ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ optional($barang->transaksi?->tanggal_gadai)->format('d M Y') ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-300">
                                <div class="max-w-xs whitespace-pre-line">{{ $barang->kondisi_fisik ?? '—' }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-300">
                                <div class="max-w-xs whitespace-pre-line">{{ $barang->kelengkapan ?? '—' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $photos = collect([
                                        $barang->foto_1,
                                        $barang->foto_2,
                                        $barang->foto_3,
                                        $barang->foto_4,
                                        $barang->foto_5,
                                        $barang->foto_6,
                                    ])
                                        ->filter()
                                        ->map(function ($path) {
                                            if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '/'])) {
                                                return $path;
                                            }

                                            return \Illuminate\Support\Facades\Storage::url($path);
                                        });
                                @endphp
                                @if ($photos->isEmpty())
                                    <span class="text-xs text-neutral-500 dark:text-black-300">{{ __('Tidak ada foto') }}</span>
                                @else
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($photos as $index => $path)
                                            <a
                                                href="{{ $path }}"
                                                target="_blank"
                                                class="inline-flex size-9 items-center justify-center rounded-lg bg-neutral-100 text-xs font-semibold text-neutral-600 transition hover:bg-emerald-100 hover:text-emerald-700 dark:bg-neutral-700/60 dark:text-black-200 dark:hover:bg-emerald-500/20 dark:hover:text-emerald-300"
                                            >
                                                {{ __('Foto') }} {{ $index + 1 }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="17" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-300">
                                {{ __('Belum ada data barang jaminan yang tersimpan.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-table.pagination-controls
            :paginator="$barangJaminan"
            :per-page="$perPage"
            :per-page-options="$perPageOptions"
            :form-action="route('gadai.lihat-barang-gadai')"
        />
    </div>
</x-layouts.app>
