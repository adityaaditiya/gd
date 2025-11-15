<x-layouts.app :title="__('Transaksi Emas')">
    @php
        $packagesCollection = collect($packages ?? []);
        $selectedPackageIds = collect(old('package_ids', []))
            ->map(function ($value) {
                if (is_int($value)) {
                    return $value;
                }

                if (is_string($value)) {
                    $trimmed = trim($value);

                    if ($trimmed !== '' && ctype_digit($trimmed)) {
                        return (int) $trimmed;
                    }
                }

                if (is_numeric($value) && ctype_digit((string) $value)) {
                    return (int) $value;
                }

                return null;
            })
            ->filter(fn ($value) => $value !== null)
            ->unique()
            ->values();
        $selectedPackageKeys = $selectedPackageIds->map(fn ($id) => (string) $id);
        $selectedPackages = $packagesCollection
            ->filter(fn ($package) => $selectedPackageKeys->contains((string) ($package['id'] ?? '')))
            ->values();
        $selectedSummaryLines = $selectedPackages->map(function ($package) {
            $code = $package['kode_intern'] ?? $package['kode_baki'] ?? '—';
            $barcode = $package['kode_barcode'] ?? '—';
            $weight = number_format((float) ($package['berat'] ?? 0), 3, ',', '.');
            $price = number_format((float) ($package['harga'] ?? 0), 0, ',', '.');

            return ($package['nama_barang'] ?? __('Barang')).' • '.$weight.' gr • '.$code.' • '.$barcode.' • Rp '.$price;
        });
        $selectedTotalWeight = (float) $selectedPackages->sum(fn ($pkg) => (float) ($pkg['berat'] ?? 0));
        $selectedTotalPrice = (float) $selectedPackages->sum(fn ($pkg) => (float) ($pkg['harga'] ?? 0));
        $selectedSummaryCount = $selectedPackages->count();
        $selectedSummaryWeightText = $selectedSummaryCount > 0
            ? number_format($selectedTotalWeight, 3, ',', '.').' gr'
            : '—';
        $selectedSummaryPriceText = $selectedSummaryCount > 0
            ? 'Rp '.number_format($selectedTotalPrice, 0, ',', '.')
            : '—';
        $selectedSummaryCountText = $selectedSummaryCount > 0
            ? number_format($selectedSummaryCount, 0, ',', '.')
            : '0';
        $selectedMetaText = match (true) {
            $selectedSummaryCount === 0 => __('Belum ada barang dipilih. Gunakan kolom pencarian untuk menemukan barang.'),
            $selectedSummaryCount === 1 => $selectedSummaryLines->first(),
            default => $selectedSummaryCountText.' '.__('barang dipilih').' • '.number_format($selectedTotalWeight, 3, ',', '.').' gr • Rp '.number_format($selectedTotalPrice, 0, ',', '.'),
        };
        $tenorCollection = collect($tenorOptions ?? [])
            ->filter(fn ($value) => is_numeric($value) && $value > 0)
            ->map(fn ($value) => (int) $value)
            ->unique()
            ->sort()
            ->values();
        $selectedTenor = old('tenor_bulan');
        $downPaymentMode = old('down_payment_mode', 'nominal');
        if (! in_array($downPaymentMode, ['nominal', 'percentage'], true)) {
            $downPaymentMode = 'nominal';
        }
        $defaultDownPaymentValue = old('estimasi_uang_muka');
        if (! is_numeric($defaultDownPaymentValue)) {
            $defaultDownPaymentValue = $defaultDownPayment ?? 1_000_000;
        }
        $defaultDownPaymentValue = max((float) $defaultDownPaymentValue, 0);
        $defaultDownPaymentPercentageValue = old('down_payment_percentage');
        if (! is_numeric($defaultDownPaymentPercentageValue)) {
            $defaultDownPaymentPercentageValue = $defaultDownPaymentPercentage ?? 10;
        }
        $defaultDownPaymentPercentageValue = min(max((float) $defaultDownPaymentPercentageValue, 0), 100);

        $marginConfigCollection = collect($marginConfig ?? []);
        $marginDefaultPercentage = (float) ($marginConfigCollection['default_percentage'] ?? 0);
        $marginOverridesCollection = collect($marginConfigCollection['tenor_overrides'] ?? [])
            ->filter(fn ($value, $key) => is_numeric($key) && is_numeric($value))
            ->mapWithKeys(fn ($value, $key) => [(int) $key => (float) $value]);
        $resolvedMarginConfig = [
            'default_percentage' => $marginDefaultPercentage,
            'tenor_overrides' => $marginOverridesCollection->all(),
        ];
    @endphp
    <div class="space-y-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Transaksi Cicil Emas') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Lakukan simulasi cicilan dengan memilih nasabah, barang emas, serta menentukan uang muka dan jangka waktu untuk menghasilkan estimasi pembayaran yang otomatis tersimpan.') }}
            </p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-6 text-emerald-700 shadow-sm dark:border-emerald-500/60 dark:bg-emerald-500/10 dark:text-emerald-300">
                <p class="text-base font-semibold text-emerald-900 dark:text-emerald-200">{{ session('status') }}</p>

                @if ($summary = session('transaction_summary'))
                    <dl class="mt-4 grid gap-4 text-sm text-neutral-700 dark:text-neutral-200 md:grid-cols-2">
                        <div class="space-y-1">
                            <dt class="font-semibold text-neutral-900 dark:text-white">{{ __('Nasabah') }}</dt>
                            <dd>{{ $summary['nasabah'] ?? __('Tidak diketahui') }}</dd>
                            @if (!empty($summary['kode_member']))
                                <dd class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Kode Member:') }} {{ $summary['kode_member'] }}</dd>
                            @endif
                        </div>
                        <div class="space-y-1">
                            <dt class="font-semibold text-neutral-900 dark:text-white">{{ __('Paket Emas') }}</dt>
                            @if (!empty($summary['packages']) && is_array($summary['packages']))
                                <dd class="space-y-1">
                                    <ul class="list-disc space-y-1 ps-4 text-xs text-neutral-600 dark:text-neutral-300">
                                        @foreach ($summary['packages'] as $package)
                                            <li>
                                                {{ $package['nama_barang'] ?? __('Barang') }} •
                                                {{ number_format((float) ($package['berat'] ?? 0), 3, ',', '.') }} gr •
                                                {{ $package['kode'] ?? '—' }} •
                                                {{ $package['barcode'] ?? '—' }} •
                                                Rp {{ number_format((float) ($package['harga'] ?? 0), 0, ',', '.') }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </dd>
                            @else
                                <dd>{{ $summary['paket'] ?? '—' }}</dd>
                            @endif
                        </div>
                        <div class="space-y-1">
                            <dt class="font-semibold text-neutral-900 dark:text-white">{{ __('Jangka Waktu') }}</dt>
                            <dd>{{ $summary['jangka_waktu'] ?? '—' }}</dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="font-semibold text-neutral-900 dark:text-white">{{ __('Estimasi DP') }}</dt>
                            @if (isset($summary['dp']))
                                <dd class="space-y-1">
                                    <span>{{ number_format($summary['dp'], 2, ',', '.') }}</span>
                                    @if (isset($summary['dp_percentage']))
                                        <span class="block text-xs text-neutral-500 dark:text-neutral-400">
                                            {{ __('Sekitar :persen% dari harga', ['persen' => number_format($summary['dp_percentage'], 2, ',', '.')]) }}
                                        </span>
                                    @endif
                                </dd>
                            @else
                                <dd>—</dd>
                            @endif
                        </div>
                        <div class="space-y-1">
                            <dt class="font-semibold text-neutral-900 dark:text-white">{{ __('Margin Pembiayaan') }}</dt>
                            @if (isset($summary['margin_amount']))
                                <dd class="space-y-1">
                                    <span>{{ number_format($summary['margin_amount'], 2, ',', '.') }}</span>
                                    <span class="block text-xs text-neutral-500 dark:text-neutral-400">
                                        {{ __('Tarif margin :persen%', ['persen' => number_format($summary['margin_percentage'] ?? 0, 2, ',', '.')]) }}
                                    </span>
                                </dd>
                            @else
                                <dd>—</dd>
                            @endif
                        </div>
                        <div class="space-y-1">
                            <dt class="font-semibold text-neutral-900 dark:text-white">{{ __('Biaya Administrasi') }}</dt>
                            @if (isset($summary['administrasi']))
                                <dd>{{ number_format($summary['administrasi'], 0, ',', '.') }}</dd>
                            @else
                                <dd>—</dd>
                            @endif
                        </div>
                        <div class="space-y-1">
                            <dt class="font-semibold text-neutral-900 dark:text-white">{{ __('Total Pembiayaan') }}</dt>
                            @if (isset($summary['total_pembiayaan']))
                                <dd class="space-y-1">
                                    <span>{{ number_format($summary['total_pembiayaan'], 2, ',', '.') }}</span>
                                    @if (isset($summary['pokok_pembiayaan']))
                                        <span class="block text-xs text-neutral-500 dark:text-neutral-400">
                                            {{ __('Pokok :pokok', ['pokok' => number_format($summary['pokok_pembiayaan'], 2, ',', '.')]) }}
                                        </span>
                                    @endif
                                </dd>
                            @else
                                <dd>—</dd>
                            @endif
                        </div>
                        <div class="space-y-1">
                            <dt class="font-semibold text-neutral-900 dark:text-white">{{ __('Angsuran Bulanan') }}</dt>
                            <dd>{{ isset($summary['angsuran']) ? number_format($summary['angsuran'], 2, ',', '.') : '—' }}</dd>
                            <dd class="text-xs text-neutral-500 dark:text-neutral-400">
                                {{ __('Tenor: :bulan bulan', ['bulan' => $summary['tenor'] ?? '—']) }}
                            </dd>
                        </div>
                    </dl>
                @endif
            </div>
        @endif

        <!-- <div class="grid gap-6 xl:grid-cols-3">
            <div class="space-y-4">
                <section class="flex flex-col gap-3 rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                    <header class="flex flex-col gap-1">
                        <span class="text-xs font-semibold uppercase tracking-wide text-indigo-500">{{ __('Langkah 1') }}</span>
                        <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Simulasi & Pemilihan Paket') }}</h2>
                    </header>
                    <ul class="list-disc space-y-2 ps-5 text-sm text-neutral-700 dark:text-neutral-200">
                        <li>{{ __('Pilih nasabah yang telah lulus verifikasi sebagai pemohon cicilan.') }}</li>
                        <li>{{ __('Tentukan barang emas berdasarkan data master (kode, berat, dan grup) yang tersedia.') }}</li>
                        <li>{{ __('Sesuaikan uang muka dan jangka waktu cicilan untuk melihat estimasi angsuran yang dihitung otomatis.') }}</li>
                    </ul>
                </section>

                <section class="flex flex-col gap-3 rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                    <header class="flex flex-col gap-1">
                        <span class="text-xs font-semibold uppercase tracking-wide text-amber-500">{{ __('Langkah 2') }}</span>
                        <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Pembayaran Uang Muka') }}</h2>
                    </header>
                    <ul class="list-disc space-y-2 ps-5 text-sm text-neutral-700 dark:text-neutral-200">
                        <li>{{ __('Validasi metode pembayaran DP (transfer atau tunai) dan simpan bukti transaksi.') }}</li>
                        <li>{{ __('Harga emas terkunci setelah DP diterima sehingga jadwal angsuran dapat digenerasikan.') }}</li>
                        <li>{{ __('Data simulasi akan menjadi acuan untuk penjadwalan angsuran berikutnya.') }}</li>
                    </ul>
                </section>
            </div> -->

            <div class="xl:col-span-2">
                <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                    <form
                        method="POST"
                        action="{{ route('cicil-emas.transaksi-emas.store') }}"
                        class="space-y-6 p-6"
                        id="cicil-emas-form"
                    >
                        @csrf

                        <div class="grid gap-6 md:grid-cols-2">
                            <div class="md:col-span-2" data-customer-selector>
                                <div class="mb-2 flex items-center justify-between gap-2">
                                    <label for="nasabah_id" class="block text-sm font-semibold text-neutral-800 dark:text-neutral-200">
                                        {{ __('Nasabah') }}
                                    </label>
                                    <label for="nasabah_search" class="sr-only">{{ __('Cari Nasabah') }}</label>
                                    <input
                                        type="search"
                                        id="nasabah_search"
                                        data-customer-search
                                        placeholder="{{ __('Cari nasabah…') }}"
                                        class="block w-48 rounded-lg border border-neutral-300 bg-white px-2 py-1 text-xs text-neutral-700 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-200 dark:focus:border-indigo-400 dark:focus:ring-indigo-900/40"
                                    />
                                </div>
                                <select
                                    id="nasabah_id"
                                    name="nasabah_id"
                                    data-customer-select
                                    class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white"
                                    required
                                >
                                    <option value="">{{ __('Pilih nasabah') }}</option>
                                    @foreach ($nasabahs as $nasabah)
                                        @php
                                            $searchTokens = strtolower(trim(($nasabah->nama ?? '').' '.($nasabah->kode_member ?? '')));
                                        @endphp
                                        <option value="{{ $nasabah->id }}" data-search="{{ $searchTokens }}" @selected(old('nasabah_id') == $nasabah->id)>
                                            {{ $nasabah->nama }}
                                            @if ($nasabah->kode_member)
                                                ({{ $nasabah->kode_member }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400" data-customer-meta>
                                    {{ __('Gunakan kotak pencarian untuk memfilter daftar nasabah.') }}
                                </p>
                                @error('nasabah_id')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-neutral-800 dark:text-neutral-200">
                                    {{ __('Data Barang') }}
                                </label>
                                <div class="space-y-4" data-package-selector>
                                    <div class="grid gap-4 lg:grid-cols-2">
                                        <div class="flex flex-col gap-2">
                                            <div class="flex items-center justify-between gap-2">
                                                <label for="package_ids" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Daftar Barang') }}</label>
                                                <label for="package_search" class="sr-only">{{ __('Cari Barang Emas') }}</label>
                                                <input
                                                    type="search"
                                                    id="package_search"
                                                    data-package-search
                                                    placeholder="{{ __('Cari barang…') }}"
                                                    class="block w-48 rounded-lg border border-neutral-300 bg-white px-2 py-1 text-xs text-neutral-700 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-200 dark:focus:border-indigo-400 dark:focus:ring-indigo-900/40"
                                                />
                                            </div>
                                            <select
                                                id="package_ids"
                                                name="package_ids[]"
                                                multiple
                                                size="8"
                                                data-package-select
                                                class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
                                            >
                                                @foreach ($packagesCollection as $package)
                                                    @php
                                                        $searchTokens = strtolower(
                                                            trim(
                                                                ($package['nama_barang'] ?? '').' '.
                                                                ($package['kode_intern'] ?? '').' '.
                                                                ($package['kode_baki'] ?? '').' '.
                                                                ($package['kode_jenis'] ?? '').' '.
                                                                ($package['kode_barcode'] ?? '')
                                                            ),
                                                        );
                                                    @endphp
                                                    <option
                                                        value="{{ $package['id'] }}"
                                                        data-name="{{ $package['nama_barang'] ?? __('Barang') }}"
                                                        data-baki="{{ $package['kode_baki'] ?? '' }}"
                                                        data-intern="{{ $package['kode_intern'] ?? '' }}"
                                                        data-jenis="{{ $package['kode_jenis'] ?? '' }}"
                                                        data-barcode="{{ $package['kode_barcode'] ?? '' }}"
                                                        data-weight="{{ (float) ($package['berat'] ?? 0) }}"
                                                        data-price="{{ (float) ($package['harga'] ?? 0) }}"
                                                        data-search="{{ $searchTokens }}"
                                                        {{ $selectedPackageKeys->contains((string) ($package['id'] ?? '')) ? 'selected' : '' }}
                                                    >
                                                        {{ $package['nama_barang'] ?? __('Barang') }} — {{ $package['kode_intern'] ?? '—' }} • {{ $package['kode_barcode'] ?? '—' }} ({{ number_format((float) ($package['berat'] ?? 0), 3, ',', '.') }} gr • Rp {{ number_format((float) ($package['harga'] ?? 0), 0, ',', '.') }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Gunakan Ctrl/Cmd + klik untuk memilih lebih dari satu barang.') }}</p>
                                        </div>
                                        <div class="rounded-lg border border-dashed border-indigo-300 bg-indigo-50/70 p-4 text-sm text-indigo-900 dark:border-indigo-500 dark:bg-indigo-500/10 dark:text-indigo-200">
                                            <p class="font-semibold">{{ __('Ringkasan Barang Terpilih') }}</p>
                                            <dl class="mt-2 space-y-2 text-xs">
                                                <div class="flex justify-between gap-2">
                                                    <dt class="text-neutral-600 dark:text-neutral-300">{{ __('Jumlah Barang') }}</dt>
                                                    <dd class="font-semibold text-neutral-900 dark:text-white" data-package-summary-count>{{ $selectedSummaryCountText }}</dd>
                                                </div>
                                                <div class="flex justify-between gap-2">
                                                    <dt class="text-neutral-600 dark:text-neutral-300">{{ __('Total Berat') }}</dt>
                                                    <dd class="font-semibold text-neutral-900 dark:text-white" data-package-summary-weight>{{ $selectedSummaryWeightText }}</dd>
                                                </div>
                                                <div class="flex justify-between gap-2">
                                                    <dt class="text-neutral-600 dark:text-neutral-300">{{ __('Total Harga') }}</dt>
                                                    <dd class="font-semibold text-neutral-900 dark:text-white" data-package-summary-price>{{ $selectedSummaryPriceText }}</dd>
                                                </div>
                                            </dl>
                                            <div class="mt-3 rounded-lg bg-white/60 p-3 text-xs text-neutral-700 shadow-sm dark:bg-neutral-900/40 dark:text-neutral-200">
                                                <p class="font-semibold">{{ __('Daftar Barang') }}</p>
                                                <ul class="mt-2 space-y-1" data-package-summary-list>
                                                    @forelse ($selectedSummaryLines as $line)
                                                        <li>{{ $line }}</li>
                                                    @empty
                                                        <li class="italic text-neutral-500 dark:text-neutral-400" data-package-empty>{{ __('Belum ada barang dipilih.') }}</li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                            <p class="mt-3 text-xs text-neutral-500 dark:text-neutral-400">{{ __('Total harga akan menjadi dasar perhitungan uang muka dan angsuran cicilan.') }}</p>
                                        </div>
                                    </div>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400" data-package-meta>{{ $selectedMetaText }}</p>
                                </div>
                                @error('package_ids')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                                @error('package_ids.*')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                                @if ($packagesCollection->isEmpty())
                                    <p class="mt-3 text-xs text-neutral-500 dark:text-neutral-400">
                                        {{ __('Belum ada data barang yang dapat dipilih?') }}
                                        <a href="{{ route('barang.data-barang') }}" class="font-semibold underline underline-offset-2">
                                            {{ __('Buka Data Barang') }}
                                        </a>
                                    </p>
                                @endif
                            </div>

                            <div class="md:col-span-2">
                                <label for="uang_muka_display" class="mb-2 block text-sm font-semibold text-neutral-800 dark:text-neutral-200">
                                    {{ __('Uang Muka') }}
                                </label>
                                <div class="mb-3 inline-flex rounded-lg border border-neutral-300 bg-neutral-100 p-1 text-xs font-semibold text-neutral-600 shadow-sm dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-300" data-down-payment-mode-group>
                                    <button
                                        type="button"
                                        data-down-payment-mode-button="nominal"
                                        class="flex-1 rounded-md px-8 py-2 transition whitespace-nowrap" 
                                    >
                                        {{ __('Nominal (Rp)') }}
                                    </button>
                                    <button
                                        type="button"
                                        data-down-payment-mode-button="percentage"
                                        class="flex-1 rounded-md px-8 py-2 transition whitespace-nowrap"
                                    >
                                        {{ __('Persentase (%)') }}
                                    </button>
                                </div>
                                <div class="flex overflow-hidden rounded-lg border border-neutral-300 bg-white shadow-sm dark:border-neutral-600 dark:bg-neutral-800">
                                    <span class="flex items-center justify-center bg-neutral-100 px-3 text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:bg-neutral-700 dark:text-neutral-200" data-down-payment-label>
                                        {{ $downPaymentMode === 'percentage' ? __('Persen') : __('Rupiah') }}
                                    </span>
                                    <input
                                        id="uang_muka_display"
                                        type="text"
                                        inputmode="decimal"
                                        data-down-payment-input
                                        value="{{ $downPaymentMode === 'percentage'
                                            ? number_format((float) $defaultDownPaymentPercentageValue, 2, ',', '.')
                                            : number_format((float) $defaultDownPaymentValue, 0, ',', '.') }}"
                                        class="w-full border-0 bg-transparent px-3 py-2 text-sm font-semibold text-neutral-900 focus:outline-none focus:ring-0 dark:text-white"
                                        autocomplete="off"
                                    />
                                </div>
                                <input type="hidden" name="down_payment_mode" data-down-payment-mode value="{{ $downPaymentMode }}">
                                <input type="hidden" name="down_payment_percentage" data-down-payment-percentage value="{{ number_format((float) $defaultDownPaymentPercentageValue, 2, '.', '') }}">
                                <input type="hidden" name="estimasi_uang_muka" data-down-payment-hidden value="{{ number_format((float) $defaultDownPaymentValue, 2, '.', '') }}">
                                @error('down_payment_mode')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                                @error('down_payment_percentage')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                                @error('estimasi_uang_muka')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400" data-down-payment-display>
                                    {{ $downPaymentMode === 'percentage'
                                        ? __('Masukkan persentase uang muka (0-100%) untuk melihat estimasi cicilan.')
                                        : __('Masukkan uang muka untuk menghitung besaran cicilan.') }}
                                </p>
                            </div>

                            <div class="md:col-span-2">
                                <span class="mb-2 block text-sm font-semibold text-neutral-800 dark:text-neutral-200">
                                    {{ __('Jangka Waktu') }}
                                </span>
                                <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3" data-tenor-list>
                                    @forelse ($tenorCollection as $tenorOption)
                                        @php
                                            $isSelectedTenor = (string) $tenorOption === (string) ($selectedTenor ?? '');
                                        @endphp
                                        <label class="relative flex cursor-pointer flex-col gap-1 rounded-lg border border-neutral-300 bg-white px-4 py-3 text-sm shadow-sm transition focus-within:ring-2 focus-within:ring-indigo-500/20 dark:border-neutral-600 dark:bg-neutral-800" data-tenor-card>
                                            <input
                                                type="radio"
                                                name="tenor_option_display"
                                                value="{{ $tenorOption }}"
                                                class="sr-only"
                                                data-tenor-option
                                                @checked($isSelectedTenor)
                                            />
                                            <span class="text-sm font-semibold text-neutral-900 dark:text-white" data-tenor-label>{{ $tenorOption }} {{ __('Bulan') }}</span>
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400" data-tenor-caption>{{ __('Pilih untuk menghitung angsuran.') }}</span>
                                        </label>
                                    @empty
                                        <p class="col-span-full text-xs text-amber-600 dark:text-amber-400">{{ __('Belum ada opsi tenor yang dapat dipilih.') }}</p>
                                    @endforelse
                                </div>
                                <input type="hidden" name="tenor_bulan" data-tenor-input value="{{ $selectedTenor ?? ($tenorCollection->first() ?? '') }}">
                                @error('tenor_bulan')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400" data-tenor-meta>
                                    {{ __('Pilih jangka waktu cicilan untuk melihat estimasi angsuran per bulan.') }}
                                </p>
                            </div>

                            <div class="md:col-span-1">
                                <label for="administrasi" class="mb-2 block text-sm font-semibold text-neutral-800 dark:text-neutral-200">
                                    {{ __('Biaya Administrasi') }}
                                    <span class="font-normal text-xs text-neutral-500 dark:text-neutral-400">({{ __('opsional') }})</span>
                                </label>
                                <div class="flex overflow-hidden rounded-lg border border-neutral-300 bg-white shadow-sm dark:border-neutral-600 dark:bg-neutral-800">
                                    <span class="flex items-center justify-center bg-neutral-100 px-3 text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                                        {{ __('Rp') }}
                                    </span>
                                    <input
                                        id="administrasi"
                                        name="administrasi"
                                        type="number"
                                        min="0"
                                        step="1"
                                        value="{{ old('administrasi', '0') }}"
                                        class="w-full border-0 bg-transparent px-3 py-2 text-sm font-semibold text-neutral-900 focus:outline-none focus:ring-0 dark:text-white"
                                        data-administration-input
                                    >
                                </div>
                                @error('administrasi')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400" data-administration-display>
                                    {{ __('Jika diisi, biaya administrasi akan ditambahkan ke total pembiayaan cicilan.') }}
                                </p>
                            </div>

                            <div class="md:col-span-1">
                                <label for="besaran_angsuran_display" class="mb-2 block text-sm font-semibold text-neutral-800 dark:text-neutral-200">
                                    {{ __('Estimasi Angsuran Bulanan') }}
                                </label>
                                <input
                                    type="text"
                                    id="besaran_angsuran_display"
                                    data-installment-output
                                    value="{{ old('besaran_angsuran') ? number_format((float) old('besaran_angsuran'), 0, ',', '.') : '' }}"
                                    readonly
                                    class="w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm font-semibold text-neutral-900 shadow-sm focus:outline-none dark:border-neutral-600 dark:bg-neutral-800 dark:text-white"
                                />
                                <input type="hidden" name="besaran_angsuran" data-installment value="{{ old('besaran_angsuran') }}">
                                @error('besaran_angsuran')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400" data-installment-display>
                                    {{ __('Besaran angsuran dihitung dari sisa harga emas dibagi tenor yang dipilih.') }}
                                </p>
                                <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400" data-margin-display>
                                    {{ __('Margin akan dihitung setelah paket dan tenor dipilih.') }}
                                </p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400" data-financing-display>
                                    {{ __('Total pembiayaan akan tampil setelah simulasi lengkap.') }}
                                </p>
                            </div>
                        </div>

                        <div class="rounded-lg bg-neutral-100 px-4 py-3 text-sm text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200" data-summary-panel hidden>
                            <p class="font-semibold text-neutral-900 dark:text-white">{{ __('Ringkasan Barang') }}</p>
                            <div class="mt-2 space-y-2">
                                <p data-summary-package>{{ __('Barang belum dipilih.') }}</p>
                                <ul class="space-y-1 text-xs text-neutral-600 dark:text-neutral-400" data-summary-items></ul>
                                <p data-summary-price></p>
                                <p data-summary-option></p>
                                <p data-summary-principal></p>
                                <p data-summary-margin></p>
                                <p data-summary-administration></p>
                                <p data-summary-financing></p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition-colors duration-200 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-900"
                            >
                                {{ __('Simpan Simulasi') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

        <script>
        (() => {
            function initCicilEmas() {
                const packages = @json($packagesCollection);
                const marginConfig = @json($resolvedMarginConfig);
                const customerSelector = document.querySelector('[data-customer-selector]');
                const customerSelect = customerSelector?.querySelector('[data-customer-select]') ?? null;
                const customerSearch = customerSelector?.querySelector('[data-customer-search]') ?? null;
                const customerMeta = customerSelector?.querySelector('[data-customer-meta]') ?? null;
                const packageSelector = document.querySelector('[data-package-selector]');
                const packageSelect = packageSelector?.querySelector('[data-package-select]') ?? null;
                const packageSearch = packageSelector?.querySelector('[data-package-search]') ?? null;
                const packageMeta = packageSelector?.querySelector('[data-package-meta]') ?? null;
                const packageSummaryCount = packageSelector?.querySelector('[data-package-summary-count]') ?? null;
                const packageSummaryWeight = packageSelector?.querySelector('[data-package-summary-weight]') ?? null;
                const packageSummaryPrice = packageSelector?.querySelector('[data-package-summary-price]') ?? null;
                const packageSummaryList = packageSelector?.querySelector('[data-package-summary-list]') ?? null;
                const downPaymentInput = document.querySelector('[data-down-payment-input]');
                const downPaymentHidden = document.querySelector('[data-down-payment-hidden]');
                const downPaymentDisplay = document.querySelector('[data-down-payment-display]');
                const downPaymentModeButtons = Array.from(document.querySelectorAll('[data-down-payment-mode-button]'));
                const downPaymentModeHidden = document.querySelector('[data-down-payment-mode]');
                const downPaymentLabel = document.querySelector('[data-down-payment-label]');
                const downPaymentPercentageHidden = document.querySelector('[data-down-payment-percentage]');
                const tenorHidden = document.querySelector('[data-tenor-input]');
                const tenorMeta = document.querySelector('[data-tenor-meta]');
                const tenorCards = Array.from(document.querySelectorAll('[data-tenor-card]'));
                const tenorOptions = Array.from(document.querySelectorAll('[data-tenor-option]'));
                const installmentHidden = document.querySelector('[data-installment]');
                const installmentOutput = document.querySelector('[data-installment-output]');
                const installmentDisplay = document.querySelector('[data-installment-display]');
                const marginDisplay = document.querySelector('[data-margin-display]');
                const financingDisplay = document.querySelector('[data-financing-display]');
                const summaryPanel = document.querySelector('[data-summary-panel]');
                const summaryPackage = document.querySelector('[data-summary-package]');
                const summaryItemsList = document.querySelector('[data-summary-items]');
                const summaryPrice = document.querySelector('[data-summary-price]');
                const summaryOption = document.querySelector('[data-summary-option]');
                const summaryPrincipal = document.querySelector('[data-summary-principal]');
                const summaryMargin = document.querySelector('[data-summary-margin]');
                const summaryAdministration = document.querySelector('[data-summary-administration]');
                const summaryFinancing = document.querySelector('[data-summary-financing]');
                const administrationInput = document.querySelector('[data-administration-input]');
                const administrationDisplay = document.querySelector('[data-administration-display]');

                const normalizeText = (value) => {
                    const raw = String(value ?? '').toLowerCase();
                    const normalized = typeof raw.normalize === 'function' ? raw.normalize('NFKD') : raw;
                    return normalized
                        .replace(/[^a-z0-9\s]/g, ' ')
                        .replace(/\s+/g, ' ')
                        .trim();
                };

                const customerOptions = customerSelect ? Array.from(customerSelect.options) : [];
                const totalCustomers = customerOptions.filter((option) => option.value).length;
                const defaultCustomerMeta = @json(__('Menampilkan :visible dari :total nasabah. Gunakan pencarian untuk menyaring daftar.'));
                const foundCustomerMeta = @json(__('Ditemukan :visible nasabah yang cocok.'));
                const emptyCustomerMeta = @json(__('Tidak ada nasabah yang cocok dengan pencarian ini.'));

                const updateCustomerMeta = (visibleCount, term) => {
                    if (!customerMeta) {
                        return;
                    }

                    if (!term) {
                        customerMeta.textContent = defaultCustomerMeta
                            .replace(':visible', String(visibleCount))
                            .replace(':total', String(totalCustomers));
                        return;
                    }

                    if (visibleCount === 0) {
                        customerMeta.textContent = emptyCustomerMeta;
                        return;
                    }

                    customerMeta.textContent = foundCustomerMeta.replace(':visible', String(visibleCount));
                };

                const filterCustomers = (term) => {
                    if (!customerOptions.length) {
                        updateCustomerMeta(0, normalizeText(term));
                        return;
                    }

                    const normalized = normalizeText(term);
                    let visible = 0;

                    customerOptions.forEach((option) => {
                        if (!option.value) {
                            option.hidden = false;
                            option.disabled = false;
                            return;
                        }

                        const optionTokens = normalizeText(option.dataset.search ?? option.textContent ?? '');
                        const matches = !normalized || optionTokens.includes(normalized);
                        const shouldHide = !matches && !option.selected;
                        option.hidden = shouldHide;
                        option.disabled = shouldHide;

                        if (!shouldHide) {
                            visible += 1;
                        }
                    });

                    updateCustomerMeta(visible, normalized);
                };

                if (customerSearch && customerSelect) {
                    filterCustomers(customerSearch.value ?? '');
                    customerSearch.addEventListener('input', (event) => {
                        filterCustomers(event.target.value ?? '');
                    });
                } else if (customerMeta) {
                    updateCustomerMeta(totalCustomers, '');
                }

                if (!packageSelector || !packageSelect) {
                    return;
                }

                if (packageSelector.dataset.initialized === 'true') {
                    return;
                }

                packageSelector.dataset.initialized = 'true';

                const packageMap = new Map(
                    Array.isArray(packages)
                        ? packages.map((pkg) => [String(pkg.id), pkg])
                        : [],
                );

                const formatCurrency = (value) =>
                    new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        maximumFractionDigits: 0,
                    }).format(Number.isFinite(value) ? value : 0);

                const formatNumber = (value) =>
                    new Intl.NumberFormat('id-ID', {
                        maximumFractionDigits: 0,
                    }).format(Number.isFinite(value) ? Math.round(value) : 0);

                const formatPercentage = (value) =>
                    new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 2,
                    }).format(Number.isFinite(value) ? value : 0);

                const formatWeight = (value) =>
                    Number(value ?? 0).toLocaleString('id-ID', {
                        minimumFractionDigits: 3,
                        maximumFractionDigits: 3,
                    });

                const parseCurrencyInput = (value) => {
                    const sanitized = String(value ?? '').replace(/[^0-9]/g, '');
                    return sanitized ? Number.parseInt(sanitized, 10) : 0;
                };

                const parsePercentageInput = (value) => {
                    const sanitized = String(value ?? '')
                        .replace(/[^0-9.,-]/g, '')
                        .replace(',', '.');
                    const parsed = Number.parseFloat(sanitized);
                    return Number.isFinite(parsed) ? parsed : 0;
                };

                const refreshModeButtons = (mode) => {
                    downPaymentModeButtons.forEach((button) => {
                        const buttonMode = button.getAttribute('data-down-payment-mode-button');
                        const isActive = buttonMode === mode;
                        button.classList.toggle('bg-white', isActive);
                        button.classList.toggle('text-indigo-600', isActive);
                        button.classList.toggle('shadow', isActive);
                        button.classList.toggle('dark:bg-neutral-700', isActive);
                        button.classList.toggle('dark:text-white', isActive);
                    });
                };

                const refreshModeLabel = (mode) => {
                    if (!downPaymentLabel) {
                        return;
                    }

                    downPaymentLabel.textContent =
                        mode === 'percentage'
                            ? '{{ __('Persen') }}'
                            : '{{ __('Rupiah') }}';
                };

                const getDownPaymentMode = () =>
                    downPaymentModeHidden?.value === 'percentage' ? 'percentage' : 'nominal';

                const getNominalValue = () => {
                    const value = Number.parseFloat(downPaymentHidden?.value ?? '0');
                    return Number.isFinite(value) ? value : 0;
                };

                const setNominalValue = (value) => {
                    const sanitized = Number.isFinite(value) ? Math.max(value, 0) : 0;
                    if (downPaymentHidden) {
                        downPaymentHidden.value = (Math.round(sanitized * 100) / 100).toFixed(2);
                    }
                    if (getDownPaymentMode() === 'nominal' && downPaymentInput) {
                        downPaymentInput.value = formatNumber(sanitized);
                    }
                };

                const getPercentageValue = () => {
                    const value = Number.parseFloat(downPaymentPercentageHidden?.value ?? '0');
                    return Number.isFinite(value) ? value : 0;
                };

                const setPercentageValue = (value) => {
                    const sanitized = Number.isFinite(value) ? Math.min(Math.max(value, 0), 100) : 0;
                    if (downPaymentPercentageHidden) {
                        downPaymentPercentageHidden.value = (Math.round(sanitized * 100) / 100).toFixed(2);
                    }
                    if (getDownPaymentMode() === 'percentage' && downPaymentInput) {
                        downPaymentInput.value = formatPercentage(sanitized);
                    }
                };

                const refreshDownPaymentInput = () => {
                    if (!downPaymentInput) {
                        return;
                    }

                    if (getDownPaymentMode() === 'percentage') {
                        downPaymentInput.value = formatPercentage(getPercentageValue());
                    } else {
                        downPaymentInput.value = formatNumber(getNominalValue());
                    }
                };

                const resolveMarginPercentage = (tenor) => {
                    const overrides = marginConfig?.tenor_overrides ?? {};
                    const tenorKey = String(tenor);
                    if (Object.prototype.hasOwnProperty.call(overrides, tenorKey)) {
                        const overrideValue = Number(overrides[tenorKey]);
                        if (Number.isFinite(overrideValue)) {
                            return overrideValue;
                        }
                    }
                    const defaultValue = Number(marginConfig?.default_percentage ?? 0);
                    return Number.isFinite(defaultValue) ? defaultValue : 0;
                };

                const getAdministrationValue = () => {
                    if (!administrationInput) {
                        return 0;
                    }

                    return parseCurrencyInput(administrationInput.value);
                };

                const getSelectedPackageOptions = () =>
                    Array.from(packageSelect?.selectedOptions ?? []);

                const ensurePackageFromOption = (option) => {
                    const id = String(option?.value ?? '');
                    if (!id) {
                        return null;
                    }

                    if (packageMap.has(id)) {
                        return packageMap.get(id);
                    }

                    const pkg = {
                        id,
                        barang_id: Number.parseInt(option.value, 10) || null,
                        nama_barang: option.dataset.name ?? option.textContent?.trim() ?? '{{ __('Barang') }}',
                        kode_baki: option.dataset.baki ?? null,
                        kode_jenis: option.dataset.jenis ?? null,
                        kode_intern: option.dataset.intern ?? null,
                        kode_barcode: option.dataset.barcode ?? null,
                        berat: Number.parseFloat(option.dataset.weight ?? '0') || 0,
                        harga: Number.parseFloat(option.dataset.price ?? '0') || 0,
                    };

                    packageMap.set(id, pkg);
                    return pkg;
                };

                const getSelectedPackages = () =>
                    getSelectedPackageOptions()
                        .map((option) => ensurePackageFromOption(option))
                        .filter((pkg) => Boolean(pkg));

                const buildSummaryLines = (selectedPackages) =>
                    selectedPackages.map((pkg) => {
                        const code = pkg?.kode_intern ?? pkg?.kode_baki ?? '—';
                        const barcode = pkg?.kode_barcode ?? '—';
                        return `${pkg?.nama_barang ?? '{{ __('Barang') }}'} • ${formatWeight(pkg?.berat ?? 0)} gr • ${code} • ${barcode} • ${formatCurrency(Number(pkg?.harga ?? 0))}`;
                    });

                const updatePackageSummaryList = (summaryLines) => {
                    if (!packageSummaryList) {
                        return;
                    }

                    packageSummaryList.innerHTML = '';

                    if (!summaryLines.length) {
                        const li = document.createElement('li');
                        li.textContent = '{{ __('Belum ada barang dipilih.') }}';
                        li.className = 'italic text-neutral-500 dark:text-neutral-400';
                        li.setAttribute('data-package-empty', '');
                        packageSummaryList.appendChild(li);
                        return;
                    }

                    summaryLines.forEach((line) => {
                        const li = document.createElement('li');
                        li.textContent = line;
                        packageSummaryList.appendChild(li);
                    });
                };

                const updateSummaryItemsList = (summaryLines) => {
                    if (!summaryItemsList) {
                        return;
                    }

                    summaryItemsList.innerHTML = '';

                    summaryLines.forEach((line) => {
                        const li = document.createElement('li');
                        li.textContent = line;
                        summaryItemsList.appendChild(li);
                    });
                };

                const toggleTenorCardsDisabled = (disabled) => {
                    tenorCards.forEach((card) => {
                        const option = card.querySelector('[data-tenor-option]');
                        if (!option) {
                            return;
                        }

                        option.disabled = Boolean(disabled);
                        card.classList.toggle('pointer-events-none', Boolean(disabled));
                        card.classList.toggle('opacity-60', Boolean(disabled));
                    });
                };

                const updateTenorCardsState = (selectedValue) => {
                    tenorCards.forEach((card) => {
                        const option = card.querySelector('[data-tenor-option]');
                        if (!option) {
                            return;
                        }

                        const isSelected = String(option.value) === String(selectedValue ?? '');
                        card.classList.toggle('border-indigo-500', isSelected);
                        card.classList.toggle('ring-2', isSelected);
                        card.classList.toggle('ring-indigo-500/40', isSelected);
                        card.classList.toggle('bg-indigo-50', isSelected);
                        card.classList.toggle('dark:bg-indigo-500/10', isSelected);
                    });
                };

                const updateTenorCaptions = (totalPrice, downPaymentAmount) => {
                    tenorOptions.forEach((option) => {
                        const card = option.closest('[data-tenor-card]');
                        const caption = card?.querySelector('[data-tenor-caption]');
                        if (!caption) {
                            return;
                        }

                        if (!Number.isFinite(Number(totalPrice)) || totalPrice <= 0) {
                            caption.textContent = '{{ __('Pilih barang terlebih dahulu.') }}';
                            return;
                        }

                        const tenorValue = Number(option.value);
                        if (!Number.isFinite(tenorValue) || tenorValue <= 0) {
                            caption.textContent = '{{ __('Tenor tidak tersedia.') }}';
                            return;
                        }

                        const principalBalance = Math.max(totalPrice - downPaymentAmount, 0);
                        const marginPercentage = resolveMarginPercentage(tenorValue);
                        const marginAmount = Math.round(principalBalance * (marginPercentage / 100) * 100) / 100;
                        const totalFinanced = principalBalance + marginAmount;
                        const installment = tenorValue > 0
                            ? Math.round((totalFinanced / tenorValue) * 100) / 100
                            : 0;

                        caption.textContent = `${formatCurrency(installment)} {{ __('per bulan') }}`;
                    });
                };

                const setCheckedTenor = (value) => {
                    tenorOptions.forEach((option) => {
                        option.checked = String(option.value) === String(value ?? '');
                    });
                    updateTenorCardsState(value);
                };

                const ensureTenorSelection = () => {
                    if (!tenorOptions.length) {
                        if (tenorHidden) {
                            tenorHidden.value = '';
                        }
                        return '';
                    }

                    const currentValue = tenorHidden?.value ?? '';
                    if (
                        currentValue &&
                        tenorOptions.some((option) => String(option.value) === String(currentValue))
                    ) {
                        setCheckedTenor(currentValue);
                        return currentValue;
                    }

                    const firstValue = tenorOptions[0].value;
                    setCheckedTenor(firstValue);
                    if (tenorHidden) {
                        tenorHidden.value = firstValue;
                    }
                    return firstValue;
                };

                const filterOptions = () => {
                    if (!packageSelect) {
                        return;
                    }

                    const term = normalizeText(packageSearch?.value ?? '');

                    Array.from(packageSelect.options).forEach((option) => {
                        if (!option) {
                            return;
                        }

                        if (!term) {
                            option.hidden = false;
                            return;
                        }

                        const datasetValue = normalizeText(option.dataset.search ?? option.textContent ?? '');
                        const match = datasetValue.includes(term);
                        option.hidden = !match && !option.selected;
                    });
                };

                const applyDownPaymentMode = (mode) => {
                    const sanitizedMode = mode === 'percentage' ? 'percentage' : 'nominal';
                    if (downPaymentModeHidden) {
                        downPaymentModeHidden.value = sanitizedMode;
                    }

                    const totalPrice = getSelectedPackages().reduce(
                        (total, pkg) => total + Number(pkg?.harga ?? 0),
                        0,
                    );

                    if (sanitizedMode === 'percentage') {
                        const nominal = getNominalValue();
                        const percent = totalPrice > 0 ? (nominal / totalPrice) * 100 : 0;
                        setPercentageValue(percent);
                    } else {
                        const percent = getPercentageValue();
                        const nominalFromPercent = totalPrice > 0 ? (totalPrice * percent) / 100 : 0;
                        setNominalValue(nominalFromPercent);
                    }

                    refreshModeButtons(sanitizedMode);
                    refreshModeLabel(sanitizedMode);
                    refreshDownPaymentInput();
                    updateSummary();
                };

                const updateSummary = () => {
                    const selectedPackages = getSelectedPackages();
                    const totalPrice = selectedPackages.reduce(
                        (total, pkg) => total + Number(pkg?.harga ?? 0),
                        0,
                    );
                    const totalWeight = selectedPackages.reduce(
                        (total, pkg) => total + Number(pkg?.berat ?? 0),
                        0,
                    );
                    const hasPackages = selectedPackages.length > 0;

                    toggleTenorCardsDisabled(!hasPackages);

                    const mode = getDownPaymentMode();
                    let downPaymentAmount = 0;
                    let percentValue = 0;

                    if (mode === 'percentage') {
                        let percent = getPercentageValue();
                        if (!Number.isFinite(percent)) {
                            percent = 0;
                        }
                        percent = Math.min(Math.max(percent, 0), 100);
                        setPercentageValue(percent);
                        percentValue = percent;
                        const computed = hasPackages && totalPrice > 0
                            ? (totalPrice * percent) / 100
                            : 0;
                        downPaymentAmount = Math.round(computed * 100) / 100;
                        setNominalValue(downPaymentAmount);
                    } else {
                        let nominal = getNominalValue();
                        if (!Number.isFinite(nominal) || nominal < 0) {
                            nominal = 0;
                        }
                        if (hasPackages && totalPrice > 0 && nominal > totalPrice) {
                            nominal = totalPrice;
                        }
                        setNominalValue(nominal);
                        percentValue = totalPrice > 0
                            ? Math.round((nominal / totalPrice) * 100 * 100) / 100
                            : 0;
                        setPercentageValue(percentValue);
                        downPaymentAmount = nominal;
                    }

                    refreshDownPaymentInput();

                    const tenorValue = Number(ensureTenorSelection());
                    updateTenorCardsState(tenorValue);

                    const principalBalance = Math.max(totalPrice - downPaymentAmount, 0);
                    const marginPercentage = resolveMarginPercentage(tenorValue);
                    const marginAmount = Math.round(principalBalance * (marginPercentage / 100) * 100) / 100;
                    const administrationAmount = getAdministrationValue();
                    const totalFinanced = principalBalance + marginAmount + administrationAmount;
                    const installment = tenorValue > 0
                        ? Math.round((totalFinanced / tenorValue) * 100) / 100
                        : 0;

                    const weightDisplay = formatWeight(totalWeight);
                    const summaryLines = buildSummaryLines(selectedPackages);

                    const summaryMetaText = (() => {
                        if (!hasPackages) {
                            return '{{ __('Belum ada barang dipilih. Gunakan kolom pencarian untuk menemukan barang.') }}';
                        }
                        if (summaryLines.length === 1) {
                            return summaryLines[0];
                        }
                        return `${formatNumber(summaryLines.length)} {{ __('barang dipilih') }} • ${weightDisplay} gr • ${formatCurrency(totalPrice)}`;
                    })();

                    if (packageMeta) {
                        packageMeta.textContent = summaryMetaText;
                    }

                    if (packageSummaryCount) {
                        packageSummaryCount.textContent = hasPackages ? formatNumber(summaryLines.length) : '0';
                    }

                    if (packageSummaryWeight) {
                        packageSummaryWeight.textContent = hasPackages ? `${weightDisplay} gr` : '—';
                    }

                    if (packageSummaryPrice) {
                        packageSummaryPrice.textContent = hasPackages ? formatCurrency(totalPrice) : '—';
                    }

                    updatePackageSummaryList(summaryLines);
                    updateSummaryItemsList(summaryLines);

                    if (summaryPanel) {
                        summaryPanel.hidden = !hasPackages;
                    }

                    if (summaryPackage) {
                        summaryPackage.textContent = hasPackages
                            ? (summaryLines.length === 1
                                ? summaryLines[0]
                                : `${formatNumber(summaryLines.length)} {{ __('barang dipilih') }} • ${weightDisplay} gr`)
                            : '{{ __('Barang belum dipilih.') }}';
                    }

                    if (summaryPrice) {
                        summaryPrice.textContent = hasPackages
                            ? `{{ __('Total Harga Barang') }}: ${formatCurrency(totalPrice)}`
                            : '';
                    }

                    if (summaryOption) {
                        summaryOption.textContent = hasPackages && tenorValue
                            ? `{{ __('Tenor') }}: ${formatNumber(tenorValue)} {{ __('bulan') }} • {{ __('Angsuran') }} ${formatCurrency(installment)}`
                            : '';
                    }

                    if (summaryPrincipal) {
                        summaryPrincipal.textContent = hasPackages
                            ? `{{ __('Pokok Pembiayaan') }}: ${formatCurrency(principalBalance)}`
                            : '';
                    }

                    if (summaryMargin) {
                        summaryMargin.textContent = hasPackages
                            ? `{{ __('Margin Pembiayaan') }}: ${formatCurrency(marginAmount)} (${formatPercentage(marginPercentage)}%)`
                            : '';
                    }

                    if (summaryAdministration) {
                        summaryAdministration.textContent = administrationAmount > 0
                            ? `{{ __('Biaya Administrasi') }}: ${formatCurrency(administrationAmount)}`
                            : '';
                    }

                    if (summaryFinancing) {
                        summaryFinancing.textContent = hasPackages && tenorValue
                            ? `{{ __('Total Pembiayaan') }}: ${formatCurrency(totalFinanced)}`
                            : '';
                    }

                    if (installmentHidden) {
                        installmentHidden.value = installment.toFixed(2);
                    }

                    if (installmentOutput) {
                        installmentOutput.value = installment ? formatNumber(installment) : '';
                    }

                    if (downPaymentDisplay) {
                        downPaymentDisplay.textContent = hasPackages
                            ? `${formatCurrency(downPaymentAmount)} • ${formatPercentage(percentValue)}% {{ __('dari total harga barang') }}`
                            : '{{ __('Masukkan uang muka untuk menghitung besaran cicilan.') }}';
                    }

                    if (tenorMeta) {
                        tenorMeta.textContent = hasPackages
                            ? (tenorValue
                                ? `{{ __('Cicilan selama :bulan bulan.', ['bulan' => ':bulan']) }}`.replace(':bulan', formatNumber(tenorValue))
                                : '{{ __('Pilih tenor cicilan untuk melihat estimasi angsuran.') }}')
                            : '{{ __('Pilih barang terlebih dahulu.') }}';
                    }

                    if (installmentDisplay) {
                        installmentDisplay.textContent = hasPackages && tenorValue
                            ? `{{ __('Estimasi angsuran: :amount per bulan', ['amount' => ':amount']) }}`.replace(':amount', formatCurrency(installment))
                            : '{{ __('Besaran angsuran dihitung dari sisa harga emas dibagi tenor yang dipilih.') }}';
                    }

                    if (marginDisplay) {
                        marginDisplay.textContent = hasPackages
                            ? `${formatCurrency(marginAmount)} ({{ __('Margin') }} ${formatPercentage(marginPercentage)}%)`
                            : '{{ __('Margin akan dihitung setelah paket dan tenor dipilih.') }}';
                    }

                    if (financingDisplay) {
                        financingDisplay.textContent = hasPackages && tenorValue
                            ? `{{ __('Total pembiayaan: :amount', ['amount' => ':amount']) }}`.replace(':amount', formatCurrency(totalFinanced))
                            : '{{ __('Total pembiayaan akan tampil setelah simulasi lengkap.') }}';
                    }

                    if (administrationDisplay) {
                        administrationDisplay.textContent = administrationAmount > 0
                            ? `{{ __('Biaya Administrasi') }}: ${formatCurrency(administrationAmount)}`
                            : '{{ __('Jika diisi, biaya administrasi akan ditambahkan ke total pembiayaan cicilan.') }}';
                    }

                    updateTenorCaptions(totalPrice, downPaymentAmount);
                };

                packageSelect.addEventListener('change', () => {
                    updateSummary();
                    filterOptions();
                });

                packageSelect.addEventListener('input', () => {
                    updateSummary();
                });

                if (packageSearch) {
                    packageSearch.addEventListener('input', () => {
                        filterOptions();
                    });
                }

                downPaymentModeButtons.forEach((button) => {
                    button.addEventListener('click', () => {
                        applyDownPaymentMode(button.getAttribute('data-down-payment-mode-button'));
                    });
                });

                if (downPaymentInput) {
                    downPaymentInput.addEventListener('input', () => {
                        if (getDownPaymentMode() === 'percentage') {
                            setPercentageValue(parsePercentageInput(downPaymentInput.value));
                        } else {
                            setNominalValue(parseCurrencyInput(downPaymentInput.value));
                        }
                        updateSummary();
                    });

                    downPaymentInput.addEventListener('blur', () => {
                        refreshDownPaymentInput();
                    });
                }

                tenorOptions.forEach((option) => {
                    option.addEventListener('change', () => {
                        if (!option.checked) {
                            return;
                        }

                        if (tenorHidden) {
                            tenorHidden.value = option.value;
                        }

                        updateTenorCardsState(option.value);
                        updateSummary();
                    });
                });

                if (administrationInput) {
                    administrationInput.addEventListener('input', () => {
                        updateSummary();
                    });

                    administrationInput.addEventListener('blur', () => {
                        updateSummary();
                    });
                }

                ensureTenorSelection();
                refreshModeButtons(getDownPaymentMode());
                refreshModeLabel(getDownPaymentMode());
                refreshDownPaymentInput();
                filterOptions();
                updateSummary();
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initCicilEmas);
            } else {
                initCicilEmas();
            }

            document.addEventListener('livewire:navigated', initCicilEmas);
        })();
    </script>
</x-layouts.app>
