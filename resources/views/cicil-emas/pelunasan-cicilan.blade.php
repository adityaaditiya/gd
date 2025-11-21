<x-layouts.app :title="__('Pelunasan Cicilan')">
    @php
        $summary = $summary ?? null;
        $transaction = $transaction ?? null;
        $search = $search ?? '';
        $previewNumber = $previewNumber ?? null;
    @endphp

    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Pelunasan Cicilan') }}</h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                {{ __('Kelola proses penyelesaian cicilan emas, mulai dari validasi pelunasan hingga penyerahan emas fisik.') }}
            </p>
        </div>

        @if (session('status'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:border-rose-500/40 dark:bg-rose-500/10 dark:text-rose-200">
                {{ session('error') }}
            </div>
        @endif

        <section class="flex flex-col gap-4 rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <header class="flex flex-col gap-1">
                <span class="text-xs font-semibold uppercase tracking-wide text-sky-500">{{ __('Menu Pelunasan Cicilan') }}</span>
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Cari Nomor Cicilan Emas') }}</h2>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Gunakan pencarian berdasarkan nomor cicilan emas untuk menyiapkan pelunasan normal (akhir kontrak) atau pelunasan dipercepat.') }}
                </p>
            </header>

            <form method="GET" class="grid gap-3 rounded-lg border border-neutral-200 bg-neutral-50 p-4 text-sm dark:border-neutral-700 dark:bg-neutral-800/60 md:grid-cols-6">
                <div class="md:col-span-4">
                    <label class="flex flex-col gap-1">
                        <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Nomor Cicilan Emas') }}</span>
                        <input
                            type="search"
                            name="search"
                            value="{{ $search }}"
                            placeholder="{{ __('Masukkan nomor cicilan emas') }}"
                            class="rounded-md border border-neutral-300 px-3 py-2 text-sm text-neutral-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100"
                            autocomplete="off"
                            required
                        >
                    </label>
                </div>
                <div class="flex items-end gap-2 md:col-span-2 md:justify-end">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-md bg-sky-500 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500/40"
                    >
                        {{ __('Cari Cicilan') }}
                    </button>
                    @if ($search !== '')
                        <a
                            href="{{ route('cicil-emas.pelunasan-cicilan') }}"
                            class="inline-flex items-center justify-center rounded-md border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-600 hover:text-neutral-800 focus:outline-none focus:ring-2 focus:ring-neutral-300/60 dark:border-neutral-600 dark:text-neutral-300 dark:hover:text-white"
                        >
                            {{ __('Bersihkan') }}
                        </a>
                    @endif
                </div>
            </form>

            @if ($search !== '' && ! $transaction)
                <div class="flex flex-col gap-2 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-500/40 dark:bg-amber-500/10 dark:text-amber-100">
                    <p class="font-semibold">{{ __('Nomor cicilan tidak ditemukan') }}</p>
                    <p>{{ __('Periksa kembali nomor cicilan emas atau gunakan menu Angsuran Rutin sebagai referensi pencarian.') }}</p>
                </div>
            @endif

            @if ($transaction)
                <div class="grid gap-4 md:grid-cols-3">
                    <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Nomor Cicilan') }}</span>
                            <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700 dark:bg-sky-500/20 dark:text-sky-200">{{ $transaction->status === \App\Models\CicilEmasTransaction::STATUS_SETTLED ? __('Lunas') : __('Aktif') }}</span>
                        </div>
                        <p class="font-mono text-lg font-semibold text-neutral-900 dark:text-white">{{ $transaction->nomor_cicilan ?? '—' }}</p>
                        <p class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('Tenor :bulan bulan • Angsuran ke-:ke', ['bulan' => $transaction->tenor_bulan, 'ke' => $summary['lastSequence'] ?? '—']) }}</p>
                    </div>
                    <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                        <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Data Nasabah') }}</span>
                        <p class="text-base font-semibold text-neutral-900 dark:text-white">{{ $transaction->nasabah?->nama ?? __('Tidak diketahui') }}</p>
                        <p class="text-sm text-neutral-600 dark:text-neutral-300">{{ $transaction->nasabah?->telepon ?? __('Kontak tidak tersedia') }}</p>
                        @if ($transaction->nasabah?->kode_member)
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Kode Member: :kode', ['kode' => $transaction->nasabah->kode_member]) }}</p>
                        @endif
                    </div>
                    <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                        <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Ringkasan Pembayaran') }}</span>
                        <div class="mt-2 flex items-center gap-2">
                            <span class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Sisa: Rp :nominal', ['nominal' => number_format($summary['remainingAmount'], 0, ',', '.')]) }}</span>
                            @if ($summary['isAccelerated'])
                                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/20 dark:text-amber-100">{{ __('Pelunasan Dipercepat') }}</span>
                            @else
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-100">{{ __('Pelunasan Normal') }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">
                            {{ __('Terbayar :paid dari :total angsuran', ['paid' => $summary['paidInstallments'], 'total' => $summary['totalInstallments']]) }}
                        </p>
                        @if ($summary['nextDueDate'])
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Jatuh tempo terdekat: :date', ['date' => $summary['nextDueDate']->translatedFormat('d M Y')]) }}</p>
                        @endif
                    </div>
                </div>

                <form method="POST" action="{{ route('cicil-emas.pelunasan-cicilan.store') }}" class="grid gap-4 md:grid-cols-2">
                    @csrf
                    <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">

                    <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                        <h3 class="text-base font-semibold text-neutral-900 dark:text-white">{{ __('Detail Pelunasan Normal (Akhir Kontrak)') }}</h3>
                        <div class="mt-3 grid gap-3">
                            <label class="flex flex-col gap-1">
                                <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Nomor Pelunasan') }}</span>
                                <input
                                    type="text"
                                    value="{{ old('nomor_pelunasan', $previewNumber) }}"
                                    class="rounded-md border border-neutral-300 bg-neutral-100 px-3 py-2 font-mono text-sm text-neutral-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 dark:border-neutral-600 dark:bg-neutral-900/60 dark:text-neutral-100"
                                    readonly
                                >
                                <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Format: PE03 + Tanggal (yymmdd) + urutan harian. Contoh: :nomor', ['nomor' => 'PE03'.now()->format('ymd').'001']) }}</span>
                            </label>
                            <label class="flex flex-col gap-1">
                                <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Biaya Ongkos Kirim (opsional)') }}</span>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-sm text-neutral-500">Rp</span>
                                    <input
                                        type="number"
                                        name="biaya_ongkos_kirim"
                                        min="0"
                                        step="0.01"
                                        value="{{ old('biaya_ongkos_kirim') }}"
                                        class="w-full rounded-md border border-neutral-300 bg-white px-10 py-2 text-sm text-neutral-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100"
                                        placeholder="0.00"
                                    >
                                </div>
                                <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Isi jika ada biaya pengiriman emas ke nasabah.') }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800/60">
                        <h3 class="text-base font-semibold text-neutral-900 dark:text-white">{{ __('Rangkuman Tagihan') }}</h3>
                        <dl class="mt-3 space-y-2 text-sm text-neutral-700 dark:text-neutral-200">
                            <div class="flex items-center justify-between">
                                <dt>{{ __('Total Jadwal') }}</dt>
                                <dd class="font-semibold">Rp {{ number_format($summary['totalScheduled'], 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt>{{ __('Total Terbayar') }}</dt>
                                <dd class="font-semibold text-emerald-600 dark:text-emerald-300">Rp {{ number_format($summary['totalPaid'], 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt>{{ __('Sisa Tagihan') }}</dt>
                                <dd class="font-semibold text-rose-600 dark:text-rose-300">Rp {{ number_format($summary['remainingAmount'], 0, ',', '.') }}</dd>
                            </div>
                        </dl>
                        <p class="mt-3 text-xs text-neutral-500 dark:text-neutral-400">
                            {{ __('Pelunasan akan menandai seluruh angsuran sebagai terbayar dan mengubah status transaksi menjadi LUNAS.') }}
                        </p>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-md bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/40"
                            >
                                {{ __('Simpan Pelunasan') }}
                            </button>
                            <a
                                href="{{ route('cicil-emas.angsuran-rutin', ['search' => $transaction->nomor_cicilan]) }}"
                                class="inline-flex items-center justify-center rounded-md border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-600 hover:text-neutral-800 focus:outline-none focus:ring-2 focus:ring-neutral-300/60 dark:border-neutral-600 dark:text-neutral-300 dark:hover:text-white"
                            >
                                {{ __('Lihat Riwayat Angsuran') }}
                            </a>
                        </div>
                    </div>
                </form>
            @endif
        </section>
    </div>
</x-layouts.app>
