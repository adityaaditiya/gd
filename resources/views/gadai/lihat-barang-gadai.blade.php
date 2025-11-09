<x-layouts.app :title="__('Lihat Barang Gadai')">
    <div class="space-y-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Data Barang Jaminan') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Pantau seluruh barang elektronik yang digadaikan lengkap dengan detail kontrak, petugas, dan estimasi nilai.') }}
            </p>
        </div>

        <div class="overflow-x-auto rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <table class="min-w-full divide-y divide-neutral-200 text-left text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200">
                <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">{{ __('No. SBG') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Nasabah') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Jenis Barang') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Merek') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Tahun Pembuatan') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Nilai Taksiran') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Uang Pinjaman') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Status') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Petugas') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Tanggal Gadai') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Foto') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                    @forelse ($barangJaminan as $barang)
                        <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/70">
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
                            <td class="whitespace-nowrap px-4 py-3 font-semibold text-emerald-600 dark:text-emerald-300">Rp {{ number_format((float) $barang->nilai_taksiran, 0, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-4 py-3">Rp {{ number_format((float) ($barang->transaksi?->uang_pinjaman ?? 0), 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-neutral-700 dark:bg-neutral-700/60 dark:text-neutral-100">
                                    {{ __($barang->transaksi?->status_transaksi ?? 'Tidak Diketahui') }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-1">
                                    <span class="text-xs text-neutral-500 dark:text-neutral-300">{{ __('Kasir:') }} {{ $barang->transaksi?->kasir?->name ?? '—' }}</span>
                                    <span class="text-xs text-neutral-500 dark:text-neutral-300">{{ __('Penaksir:') }} {{ $barang->penaksir?->name ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ optional($barang->transaksi?->tanggal_gadai)->format('d M Y') ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $photos = collect([
                                        $barang->foto_1,
                                        $barang->foto_2,
                                        $barang->foto_3,
                                        $barang->foto_4,
                                        $barang->foto_5,
                                        $barang->foto_6,
                                    ])->filter();
                                @endphp
                                @if ($photos->isEmpty())
                                    <span class="text-xs text-neutral-500 dark:text-neutral-300">{{ __('Tidak ada foto') }}</span>
                                @else
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($photos as $index => $path)
                                            <a
                                                href="{{ $path }}"
                                                target="_blank"
                                                class="inline-flex size-9 items-center justify-center rounded-lg bg-neutral-100 text-xs font-semibold text-neutral-600 transition hover:bg-emerald-100 hover:text-emerald-700 dark:bg-neutral-700/60 dark:text-neutral-200 dark:hover:bg-emerald-500/20 dark:hover:text-emerald-300"
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
                            <td colspan="11" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-300">
                                {{ __('Belum ada data barang jaminan yang tersimpan.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $barangJaminan->links() }}
        </div>
    </div>
</x-layouts.app>
