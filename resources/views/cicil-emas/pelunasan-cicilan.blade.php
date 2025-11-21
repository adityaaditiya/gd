<x-layouts.app :title="__('Pelunasan Cicilan')">
    @php
        use Illuminate\Support\Carbon;

        $nomorCicilan = request('nomor_cicilan');
        $today = Carbon::now();
        $pelunasanSequenceToday = 1;
        $pelunasanNumber = $today->format('Ymd') . str_pad($pelunasanSequenceToday, 5, '0', STR_PAD_LEFT);

        $contract = $nomorCicilan
            ? [
                'nomor_cicilan' => $nomorCicilan,
                'nasabah' => 'Nadia Pratama',
                'paket' => 'Cicilan Emas Reguler',
                'sisa_utang' => 12500000,
                'total_angsuran' => 10,
                'angsuran_tercatat' => 9,
                'last_payment_at' => $today->copy()->subDays(35),
                'terlambat_hari' => 42,
                'due_date' => $today->copy()->addDays(10),
                'harga_pasar_emas' => 950000,
                'berat_gram' => 10,
            ]
            : null;

        $hasilPenjualan = $contract ? $contract['harga_pasar_emas'] * $contract['berat_gram'] : 0;
        $defisit = $contract ? $contract['sisa_utang'] - $hasilPenjualan : 0;
    @endphp

    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Pelunasan Cicilan') }}</h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                {{ __('Kelola proses penyelesaian cicilan emas, mulai dari validasi pelunasan hingga penyerahan emas fisik.') }}
            </p>
        </div>

        <section class="flex flex-col gap-4 rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <header class="flex flex-col gap-1">
                <span class="text-xs font-semibold uppercase tracking-wide text-sky-500">{{ __('Cari Cicilan') }}</span>
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Pencarian Nomor Cicilan') }}</h2>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Masukkan nomor cicilan untuk memunculkan opsi pelunasan sesuai kondisi kontrak.') }}
                </p>
            </header>

            <form method="GET" class="grid gap-3 rounded-lg border border-neutral-200 bg-neutral-50 p-4 text-sm dark:border-neutral-700 dark:bg-neutral-800/60 md:grid-cols-3">
                <label class="flex flex-col gap-1 md:col-span-2">
                    <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Nomor Cicilan') }}</span>
                    <input
                        type="search"
                        name="nomor_cicilan"
                        value="{{ $nomorCicilan }}"
                        placeholder="{{ __('Masukkan nomor cicilan 16 digit') }}"
                        class="rounded-md border border-neutral-300 px-3 py-2 text-sm text-neutral-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100"
                        required
                    >
                </label>
                <div class="flex items-end gap-2 md:justify-end">
                    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-sky-500 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500/40">
                        {{ __('Cari') }}
                    </button>
                    <a href="{{ route('cicil-emas.pelunasan-cicilan') }}" class="inline-flex items-center justify-center rounded-md border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-600 hover:text-neutral-800 focus:outline-none focus:ring-2 focus:ring-neutral-300/60 dark:border-neutral-600 dark:text-neutral-300 dark:hover:text-white">
                        {{ __('Atur Ulang') }}
                    </a>
                </div>
            </form>
        </section>

        @if ($nomorCicilan)
            <section class="space-y-4 rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <header class="flex flex-col gap-1">
                    <span class="text-xs font-semibold uppercase tracking-wide text-emerald-500">{{ __('Hasil Pencarian') }}</span>
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Detail Kontrak & Opsi Penyelesaian') }}</h2>
                    <p class="text-sm text-neutral-600 dark:text-neutral-300">
                        {{ __('Tentukan alur penyelesaian sesuai status angsuran terakhir dan kondisi kontrak.') }}
                    </p>
                </header>

                <div class="grid gap-4 rounded-lg border border-neutral-200 bg-neutral-50 p-4 text-sm text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800/60 dark:text-neutral-100 md:grid-cols-2">
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Ringkasan Kontrak') }}</p>
                        <p class="text-base font-semibold text-neutral-900 dark:text-white">{{ $contract['nomor_cicilan'] }}</p>
                        <p class="text-sm">{{ $contract['nasabah'] }} • {{ $contract['paket'] }}</p>
                        <p class="text-sm">{{ __('Sisa utang: Rp :amount', ['amount' => number_format($contract['sisa_utang'], 0, ',', '.')]) }}</p>
                    </div>
                    <div class="space-y-1 text-sm">
                        <p>{{ __('Angsuran tercatat: :count dari :total', ['count' => $contract['angsuran_tercatat'], 'total' => $contract['total_angsuran']]) }}</p>
                        <p>{{ __('Pembayaran terakhir: :tanggal', ['tanggal' => optional($contract['last_payment_at'])->translatedFormat('d M Y')]) }}</p>
                        <p>{{ __('Jatuh tempo akhir: :tanggal', ['tanggal' => optional($contract['due_date'])->translatedFormat('d M Y')]) }}</p>
                        <p class="font-semibold text-amber-600 dark:text-amber-300">{{ __('Keterlambatan: :hari hari', ['hari' => $contract['terlambat_hari']]) }}</p>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <section class="flex flex-col gap-3 rounded-lg border border-emerald-200 bg-emerald-50 p-4 shadow-sm dark:border-emerald-500/40 dark:bg-emerald-500/10">
                        <header class="flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-200">{{ __('Pelunasan Normal (Akhir Kontrak)') }}</span>
                            <p class="text-sm text-neutral-700 dark:text-neutral-100">{{ __('Catat pelunasan angsuran terakhir atau permintaan pelunasan dipercepat sebelum jatuh tempo.') }}</p>
                        </header>

                        <div class="space-y-3 text-sm text-neutral-700 dark:text-neutral-100">
                            <label class="flex flex-col gap-1">
                                <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Nomor Pelunasan (13 Digit)') }}</span>
                                <input
                                    type="text"
                                    value="{{ $pelunasanNumber }}"
                                    readonly
                                    class="rounded-md border border-emerald-200 bg-white px-3 py-2 text-sm text-neutral-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 dark:border-emerald-500/40 dark:bg-neutral-900 dark:text-neutral-100"
                                >
                                <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Format: YYYYMMDD + urutan pelunasan hari ini') }}</span>
                            </label>

                            <div class="rounded-md border border-emerald-200 bg-white p-3 text-xs dark:border-emerald-500/40 dark:bg-neutral-900">
                                <p class="font-semibold text-neutral-800 dark:text-neutral-100">{{ __('Deteksi Angsuran Terakhir') }}</p>
                                <p class="text-neutral-600 dark:text-neutral-300">{{ __('Angsuran ke-:current terdeteksi sebagai periode akhir. Sistem siap mencatat pelunasan dipercepat bila diajukan sebelum jatuh tempo.', ['current' => $contract['total_angsuran']]) }}</p>
                            </div>

                            <label class="flex flex-col gap-1">
                                <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Biaya Ongkos Kirim (opsional)') }}</span>
                                <input
                                    type="number"
                                    min="0"
                                    step="1000"
                                    placeholder="0"
                                    class="w-full rounded-md border border-neutral-300 px-3 py-2 text-sm text-neutral-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100"
                                >
                                <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Tambahkan biaya bila pengiriman emas diperlukan.') }}</span>
                            </label>

                            <div class="flex items-center justify-between rounded-md bg-emerald-100 px-3 py-2 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-100">
                                <span>{{ __('Status Transaksi') }}</span>
                                <span>{{ __('LUNAS') }}</span>
                            </div>

                            <button type="button" class="inline-flex items-center justify-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
                                {{ __('Catat Pelunasan Normal') }}
                            </button>
                        </div>
                    </section>

                    <section class="flex flex-col gap-3 rounded-lg border border-amber-200 bg-amber-50 p-4 shadow-sm dark:border-amber-500/40 dark:bg-amber-500/10">
                        <header class="flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wide text-amber-600 dark:text-amber-200">{{ __('Penyelesaian Kontrak Wanprestasi') }}</span>
                            <p class="text-sm text-neutral-700 dark:text-neutral-100">{{ __('Digunakan saat keterlambatan > 30 hari atau nasabah tidak respons.') }}</p>
                        </header>

                        <div class="space-y-3 text-sm text-neutral-700 dark:text-neutral-100">
                            <div class="rounded-md border border-amber-200 bg-white p-3 text-xs dark:border-amber-500/40 dark:bg-neutral-900">
                                <p class="font-semibold text-neutral-800 dark:text-neutral-100">{{ __('Deteksi Wanprestasi') }}</p>
                                <p class="text-neutral-600 dark:text-neutral-300">{{ __('Keterlambatan :hari hari terdeteksi (otomatis > 30 hari). Sistem memicu penyelesaian via eksekusi jaminan.', ['hari' => $contract['terlambat_hari']]) }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex flex-col gap-1">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Harga Pasar Emas / gr') }}</span>
                                    <input
                                        type="number"
                                        value="{{ number_format($contract['harga_pasar_emas'], 0, '.', '') }}"
                                        class="rounded-md border border-neutral-300 px-3 py-2 text-sm text-neutral-900 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100"
                                    >
                                </label>
                                <label class="flex flex-col gap-1">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Berat Emas (gr)') }}</span>
                                    <input
                                        type="number"
                                        value="{{ number_format($contract['berat_gram'], 3, '.', '') }}"
                                        class="rounded-md border border-neutral-300 px-3 py-2 text-sm text-neutral-900 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100"
                                    >
                                </label>
                                <label class="flex flex-col gap-1">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Estimasi Hasil Penjualan') }}</span>
                                    <input
                                        type="text"
                                        value="Rp {{ number_format($hasilPenjualan, 0, ',', '.') }}"
                                        readonly
                                        class="rounded-md border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-900 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100"
                                    >
                                </label>
                                <label class="flex flex-col gap-1">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Surplus / Defisit') }}</span>
                                    <input
                                        type="text"
                                        value="{{ $defisit >= 0 ? 'Defisit Rp ' . number_format($defisit, 0, ',', '.') : 'Surplus Rp ' . number_format(abs($defisit), 0, ',', '.') }}"
                                        readonly
                                        class="rounded-md border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-900 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100"
                                    >
                                </label>
                            </div>

                            <label class="flex flex-col gap-1">
                                <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Keterangan Eksekusi Jaminan') }}</span>
                                <textarea rows="3" class="w-full rounded-md border border-neutral-300 px-3 py-2 text-sm text-neutral-900 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100" placeholder="{{ __('Penjualan emas oleh lembaga, nomor berita acara, atau catatan khusus') }}"></textarea>
                            </label>

                            <div class="flex flex-col gap-1 rounded-md bg-amber-100 px-3 py-2 text-xs font-semibold text-amber-700 dark:bg-amber-500/20 dark:text-amber-100">
                                <span>{{ __('Pelunasan utang ditutup dari hasil penjualan jaminan.') }}</span>
                                <span>{{ __('Status Transaksi: SELESAI (Dieksekusi)') }}</span>
                            </div>

                            <button type="button" class="inline-flex items-center justify-center rounded-md bg-amber-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500/40">
                                {{ __('Catat Penyelesaian Wanprestasi') }}
                            </button>
                        </div>
                    </section>
                </div>
            </section>
        @else
            <section class="flex flex-col items-center justify-center gap-3 rounded-xl border border-dashed border-neutral-300 p-6 text-center text-neutral-600 dark:border-neutral-600 dark:text-neutral-300">
                <p class="text-base font-semibold text-neutral-800 dark:text-neutral-100">{{ __('Masukkan nomor cicilan untuk memulai proses pelunasan.') }}</p>
                <p class="text-sm">{{ __('Sistem akan menawarkan pelunasan normal atau penyelesaian wanprestasi sesuai status angsuran terakhir.') }}</p>
            </section>
        @endif
    </div>
</x-layouts.app>
