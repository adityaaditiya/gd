<x-layouts.app :title="__('Lihat Barang Gadai')">
    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Data Barang Jaminan') }}</h1>
                @if (session('status'))
                    <p class="mt-1 text-sm text-emerald-600 dark:text-emerald-300">{{ session('status') }}</p>
                @endif
            </div>
            <a
                href="{{ route('gadai.barang-jaminan.create') }}"
                wire:navigate
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
            >
                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>{{ __('Tambah Barang Jaminan') }}</span>
            </a>
        </div>

        @if (session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-500/60 dark:bg-red-500/10 dark:text-red-300">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <table class="min-w-full divide-y divide-neutral-200 text-left text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200">
                <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">{{ __('Aksi') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('No. SBG') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Nasabah') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Jenis Barang') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Merek') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Usia (Tahun)') }}</th>
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
                            <td class="px-4 py-3">
                                <a
                                    href="{{ route('gadai.barang-jaminan.edit', $barang) }}"
                                    wire:navigate
                                    class="inline-flex items-center gap-1 rounded-lg border border-neutral-300 px-3 py-2 text-xs font-semibold text-neutral-700 transition hover:border-emerald-500 hover:text-emerald-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-neutral-600 dark:text-neutral-200 dark:hover:border-emerald-400 dark:hover:text-emerald-300"
                                >
                                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.25 2.25 0 0 1 3.182 3.182L8.94 18.773a4.5 4.5 0 0 1-1.897 1.13l-2.685.8a.75.75 0 0 1-.927-.927l.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487Zm0 0L19.5 7.125" />
                                    </svg>
                                    <span>{{ __('Edit') }}</span>
                                </a>
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
                            <td colspan="12" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-300">
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
