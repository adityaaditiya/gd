<x-layouts.app :title="__('Transaksi Emas')">
    @php
        $packagesCollection = collect($packages ?? []);
        $selectedPackageIds = collect(old('package_ids', []))
            ->map(fn ($value) => (string) $value)
            ->filter()
            ->values();
        $selectedPackages = $packagesCollection
            ->filter(fn ($package) => $selectedPackageIds->contains($package['id'] ?? null))
            ->values();
        $selectedSummaryLines = $selectedPackages->map(function ($package) {
            $group = $package['kode_group'] ?? $package['kode_intern'] ?? '—';
            $weight = number_format((float) ($package['berat'] ?? 0), 3, ',', '.');
            $price = number_format((float) ($package['harga'] ?? 0), 0, ',', '.');

            return ($package['nama_barang'] ?? __('Barang')).' • '.$weight.' gr • '.$group.' • Rp '.$price;
        });
        $selectedTotalWeight = (float) $selectedPackages->sum(fn ($pkg) => (float) ($pkg['berat'] ?? 0));
        $selectedTotalPrice = (float) $selectedPackages->sum(fn ($pkg) => (float) ($pkg['harga'] ?? 0));
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
                            <div class="md:col-span-2">
                                <label for="nasabah_id" class="mb-2 block text-sm font-semibold text-neutral-800 dark:text-neutral-200">
                                    {{ __('Nasabah') }}
                                </label>
                                <select
                                    id="nasabah_id"
                                    name="nasabah_id"
                                    class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white"
                                    required
                                >
                                    <option value="">{{ __('Pilih nasabah') }}</option>
                                    @foreach ($nasabahs as $nasabah)
                                        <option value="{{ $nasabah->id }}" @selected(old('nasabah_id') == $nasabah->id)>
                                            {{ $nasabah->nama }}
                                            @if ($nasabah->kode_member)
                                                ({{ $nasabah->kode_member }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('nasabah_id')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-neutral-800 dark:text-neutral-200">
                                    {{ __('Data Barang') }}
                                </label>
                                <div
                                    class="space-y-3"
                                    data-package-selector
                                    data-package-endpoint="{{ route('cicil-emas.transaksi-emas.packages') }}"
                                >
                                    <div class="space-y-2" data-package-selected-list>
                                        <p
                                            class="rounded-lg border border-dashed border-neutral-300 px-3 py-4 text-sm text-neutral-500 dark:border-neutral-600 dark:text-neutral-300"
                                            data-package-empty
                                            @class(['hidden' => $selectedPackages->isNotEmpty()])
                                        >
                                            {{ __('Belum ada barang dipilih. Gunakan tombol “Tambah Barang” untuk memulai.') }}
                                        </p>
                                        @foreach ($selectedPackages as $package)
                                            <div
                                                class="flex items-start gap-3 rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm shadow-sm dark:border-neutral-600 dark:bg-neutral-800"
                                                data-package-item="{{ $package['id'] }}"
                                            >
                                                <div class="min-w-0 flex-1">
                                                    <p class="truncate font-semibold text-neutral-900 dark:text-white" data-package-name>
                                                        {{ $package['nama_barang'] ?? __('Barang') }}
                                                    </p>
                                                    <p class="text-xs text-neutral-500 dark:text-neutral-400" data-package-detail>
                                                        {{ ($package['kode_group'] ?? $package['kode_intern'] ?? '—') }} • {{ number_format((float) ($package['berat'] ?? 0), 3, ',', '.') }} gr • Rp {{ number_format((float) ($package['harga'] ?? 0), 0, ',', '.') }}
                                                    </p>
                                                </div>
                                                <button
                                                    type="button"
                                                    class="text-xs font-semibold text-rose-600 transition hover:text-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500/20 dark:text-rose-400 dark:hover:text-rose-300"
                                                    data-package-remove="{{ $package['id'] }}"
                                                >
                                                    {{ __('Hapus') }}
                                                </button>
                                                <input type="hidden" name="package_ids[]" value="{{ $package['id'] }}" data-package-input="{{ $package['id'] }}">
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="flex flex-wrap items-center gap-3">
                                        <button
                                            type="button"
                                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-indigo-600 bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:ring-offset-1 dark:focus:ring-offset-neutral-900"
                                            data-package-open
                                        >
                                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path d="M10 3a1 1 0 0 1 1 1v5h5a1 1 0 1 1 0 2h-5v5a1 1 0 1 1-2 0v-5H4a1 1 0 0 1 0-2h5V4a1 1 0 0 1 1-1Z" />
                                            </svg>
                                            <span>{{ __('Tambah Barang') }}</span>
                                        </button>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                            {{ __('Klik “Tambah Barang” untuk mencari dan menambahkan barang emas.') }}
                                        </p>
                                    </div>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400" data-package-meta>
                                        {{ $selectedPackages->isNotEmpty() ? ($selectedPackages->count() === 1 ? $selectedSummaryLines->first() : $selectedPackages->count() . ' ' . __('barang dipilih') . ' • ' . number_format($selectedTotalWeight, 3, ',', '.') . ' gr • Rp ' . number_format($selectedTotalPrice, 0, ',', '.')) : __('Belum ada barang dipilih. Gunakan tombol “Tambah Barang” untuk memulai.') }}
                                    </p>
                                    <template data-package-item-template>
                                        <div class="flex items-start gap-3 rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm shadow-sm dark:border-neutral-600 dark:bg-neutral-800" data-package-item>
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate font-semibold text-neutral-900 dark:text-white" data-package-name></p>
                                                <p class="text-xs text-neutral-500 dark:text-neutral-400" data-package-detail></p>
                                            </div>
                                            <button
                                                type="button"
                                                class="text-xs font-semibold text-rose-600 transition hover:text-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500/20 dark:text-rose-400 dark:hover:text-rose-300"
                                                data-package-remove
                                            >
                                                {{ __('Hapus') }}
                                            </button>
                                            <input type="hidden" name="package_ids[]" data-package-input>
                                        </div>
                                    </template>
                                </div>
                                @error('package_ids')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                                @error('package_ids.*')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                                @if ($selectedPackages->isEmpty())
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

    <template data-package-result-template>
        <li class="flex items-start justify-between gap-3 rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm shadow-sm dark:border-neutral-700 dark:bg-neutral-800" data-package-result>
            <div class="min-w-0 flex-1">
                <p class="truncate font-semibold text-neutral-900 dark:text-white" data-result-name></p>
                <p class="text-xs text-neutral-500 dark:text-neutral-400" data-result-meta></p>
            </div>
            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-md border border-indigo-600 bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 dark:focus:ring-offset-neutral-900"
                data-result-add
            >
                {{ __('Tambah') }}
            </button>
        </li>
    </template>

    <div class="fixed inset-0 z-40 hidden" data-package-modal aria-hidden="true">
        <div class="absolute inset-0 bg-neutral-900/50 backdrop-blur-sm" data-package-modal-backdrop></div>
        <div class="relative mx-auto flex h-full w-full max-w-3xl flex-col px-4 py-8 sm:justify-center">
            <div class="relative z-10 flex max-h-full flex-col overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-2xl dark:border-neutral-700 dark:bg-neutral-900">
                <div class="flex items-center justify-between border-b border-neutral-200 px-6 py-4 dark:border-neutral-700">
                    <h2 class="text-base font-semibold text-neutral-900 dark:text-white">{{ __('Cari Barang Emas') }}</h2>
                    <button
                        type="button"
                        class="rounded-md p-2 text-neutral-500 transition hover:bg-neutral-100 hover:text-neutral-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-neutral-200"
                        data-package-modal-close
                    >
                        <span class="sr-only">{{ __('Tutup') }}</span>
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <div class="flex flex-1 flex-col gap-4 px-6 py-4">
                    <div class="relative">
                        <label class="sr-only" for="package-modal-search">{{ __('Cari barang emas') }}</label>
                        <input
                            id="package-modal-search"
                            type="search"
                            data-package-modal-search
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white"
                            placeholder="{{ __('Ketik minimal 2 huruf untuk mencari barang emas') }}"
                            autocomplete="off"
                        >
                    </div>
                    <div class="flex-1 overflow-y-auto">
                        <ul class="space-y-2" data-package-modal-results></ul>
                        <p class="rounded-lg border border-dashed border-neutral-300 px-3 py-4 text-sm text-neutral-500 dark:border-neutral-600 dark:text-neutral-300" data-package-modal-empty>
                            {{ __('Ketik minimal 2 huruf untuk mencari barang emas.') }}
                        </p>
                    </div>
                    <div class="flex flex-col gap-2 border-t border-neutral-200 pt-4 text-xs text-neutral-500 dark:border-neutral-700 dark:text-neutral-400 sm:flex-row sm:items-center sm:justify-between">
                        <p>{{ __('Hasil pencarian tidak menampilkan barang yang sudah dipilih.') }}</p>
                        <div class="flex justify-end">
                            <button
                                type="button"
                                class="inline-flex items-center gap-2 rounded-md border border-neutral-300 px-3 py-2 text-xs font-semibold text-neutral-700 transition hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800"
                                data-package-modal-more
                                hidden
                            >
                                {{ __('Muat lebih banyak') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <script>
        (() => {
            function initCicilEmas() {
                const packages = @json($packagesCollection);
                const marginConfig = @json($resolvedMarginConfig);
                const packageSelector = document.querySelector('[data-package-selector]');
                const packageMeta = packageSelector?.querySelector('[data-package-meta]') ?? null;
                const packageList = packageSelector?.querySelector('[data-package-selected-list]') ?? null;
                const packageEmpty = packageSelector?.querySelector('[data-package-empty]') ?? null;
                const packageTemplate = packageSelector?.querySelector('template[data-package-item-template]') ?? null;
                const packageEndpoint = packageSelector?.getAttribute('data-package-endpoint') ?? '';
                const packageOpenButton = packageSelector?.querySelector('[data-package-open]') ?? null;
                const resultTemplate = document.querySelector('template[data-package-result-template]') ?? null;
                const modal = document.querySelector('[data-package-modal]') ?? null;
                const modalBackdrop = modal?.querySelector('[data-package-modal-backdrop]') ?? null;
                const modalCloseButtons = Array.from(modal?.querySelectorAll('[data-package-modal-close]') ?? []);
                const modalSearch = modal?.querySelector('[data-package-modal-search]') ?? null;
                const modalResults = modal?.querySelector('[data-package-modal-results]') ?? null;
                const modalEmpty = modal?.querySelector('[data-package-modal-empty]') ?? null;
                const modalMoreButton = modal?.querySelector('[data-package-modal-more]') ?? null;
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
                const summaryFinancing = document.querySelector('[data-summary-financing]');
                const administrationInput = document.querySelector('[data-administration-input]');
                const administrationDisplay = document.querySelector('[data-administration-display]');
                const summaryAdministration = document.querySelector('[data-summary-administration]');

                if (!packageSelector) {
                    // DOM belum siap atau kita bukan di halaman ini
                    return;
                }

                if (!packageEndpoint && packageOpenButton) {
                    packageOpenButton.disabled = true;
                    packageOpenButton.classList.add('opacity-50', 'cursor-not-allowed');
                }

                const packageMap = new Map(
                    Array.isArray(packages)
                        ? packages.map((pkg) => [String(pkg.id), pkg])
                        : [],
                );

                const decodePackageKeyValue = (value) => {
                    if (value === null || value === undefined) {
                        return null;
                    }

                    let normalized = String(value);

                    if (normalized.startsWith('barang-')) {
                        normalized = normalized.slice(7);
                    }

                    return /^[0-9]+$/.test(normalized) ? Number.parseInt(normalized, 10) : null;
                }

                const ensurePackageMapEntry = (pkg) => {
                    if (pkg && pkg.id) {
                        packageMap.set(String(pkg.id), pkg);
                    }
                };

                const getSelectedInputs = () =>
                    Array.from(packageList?.querySelectorAll('[data-package-input]') ?? []);

                const getSelectedPackageIds = () =>
                    getSelectedInputs()
                        .map((input) => String(input.value ?? ''))
                        .filter((value) => value !== '');

                const getSelectedPackages = () =>
                    getSelectedPackageIds()
                        .map((id) => packageMap.get(String(id)))
                        .filter((pkg) => Boolean(pkg));

                const updateEmptyState = () => {
                    if (!packageEmpty) {
                        return;
                    }

                    packageEmpty.hidden = getSelectedPackageIds().length > 0;
                };

                const buildPackageMeta = (pkg) => {
                    const code = pkg?.kode_group ?? pkg?.kode_intern ?? pkg?.kode_barcode ?? '—';
                    const weight = Number(pkg?.berat ?? 0).toLocaleString('id-ID', {
                        minimumFractionDigits: 3,
                        maximumFractionDigits: 3,
                    });

                    return `${code} • ${weight} gr • ${formatCurrency(Number(pkg?.harga ?? 0))}`;
                };

                const createPackageElement = (pkg) => {
                    if (!packageTemplate) {
                        return null;
                    }

                    const fragment = packageTemplate.content.cloneNode(true);
                    const element = fragment.querySelector('[data-package-item]');

                    if (!element) {
                        return null;
                    }

                    element.setAttribute('data-package-item', String(pkg.id));

                    const nameEl = element.querySelector('[data-package-name]');
                    if (nameEl) {
                        nameEl.textContent = pkg?.nama_barang ?? '{{ __('Barang') }}';
                    }

                    const detailEl = element.querySelector('[data-package-detail]');
                    if (detailEl) {
                        detailEl.textContent = buildPackageMeta(pkg);
                    }

                    const inputEl = element.querySelector('[data-package-input]');
                    if (inputEl) {
                        inputEl.value = String(pkg.id);
                        inputEl.setAttribute('data-package-input', String(pkg.id));
                    }

                    const removeButton = element.querySelector('[data-package-remove]');
                    if (removeButton) {
                        removeButton.setAttribute('data-package-remove', String(pkg.id));
                        removeButton.addEventListener('click', (event) => {
                            event.preventDefault();
                            removePackage(String(pkg.id));
                        });
                    }

                    return element;
                };

                const removePackage = (id) => {
                    const normalizedId = String(id ?? '');

                    if (!packageList || !normalizedId) {
                        return;
                    }

                    const item = packageList.querySelector(`[data-package-item='${CSS.escape(normalizedId)}']`);

                    if (item) {
                        item.remove();
                        updateEmptyState();
                        updateOutputs();
                    }
                };

                const addPackage = (pkg) => {
                    if (!pkg || !pkg.id || !packageList) {
                        return;
                    }

                    const id = String(pkg.id);

                    if (getSelectedPackageIds().includes(id)) {
                        return;
                    }

                    ensurePackageMapEntry(pkg);
                    const element = createPackageElement(pkg);

                    if (!element) {
                        return;
                    }

                    packageList.appendChild(element);
                    updateEmptyState();
                    updateOutputs();
                };

                let lastFocusedTrigger = null;
                const minimumSearchLength = 2;
                let searchDebounce;
                let currentRequestId = 0;
                const searchState = {
                    query: '',
                    page: 1,
                    loading: false,
                    hasMore: false,
                };

                const setModalEmptyMessage = (message, hidden = false) => {
                    if (!modalEmpty) {
                        return;
                    }

                    modalEmpty.textContent = message;
                    modalEmpty.hidden = hidden;
                };

                const clearModalResults = () => {
                    if (modalResults) {
                        modalResults.innerHTML = '';
                    }
                };

                const renderResultItem = (pkg) => {
                    if (!resultTemplate) {
                        return null;
                    }

                    const fragment = resultTemplate.content.cloneNode(true);
                    const element = fragment.querySelector('[data-package-result]');

                    if (!element) {
                        return null;
                    }

                    element.setAttribute('data-package-result', String(pkg.id));

                    const nameEl = element.querySelector('[data-result-name]');
                    if (nameEl) {
                        nameEl.textContent = pkg?.nama_barang ?? '{{ __('Barang') }}';
                    }

                    const detailEl = element.querySelector('[data-result-meta]');
                    if (detailEl) {
                        const barcode = pkg?.kode_barcode ? ` • ${pkg.kode_barcode}` : '';
                        detailEl.textContent = `${buildPackageMeta(pkg)}${barcode}`;
                    }

                    const addButton = element.querySelector('[data-result-add]');
                    if (addButton) {
                        addButton.addEventListener('click', (event) => {
                            event.preventDefault();
                            addPackage(pkg);
                            element.remove();

                            if (modalResults && !modalResults.children.length) {
                                setModalEmptyMessage('{{ __('Semua barang hasil pencarian sudah dipilih.') }}', false);
                            }
                        });
                    }

                    return element;
                };

                const toggleModalLoading = (isLoading) => {
                    if (modalSearch) {
                        modalSearch.classList.toggle('cursor-wait', isLoading);
                    }
                };

                const loadPackages = async ({ reset = false } = {}) => {
                    if (!modal || !modalResults || !resultTemplate || !packageEndpoint) {
                        return;
                    }

                    if (reset) {
                        searchState.page = 1;
                        clearModalResults();
                    } else if (searchState.loading) {
                        return;
                    }

                    const requestId = ++currentRequestId;
                    const requestedQuery = searchState.query;
                    const requestedPage = searchState.page;

                    searchState.loading = true;

                    setModalEmptyMessage('{{ __('Memuat data barang...') }}', false);
                    toggleModalLoading(true);

                    const params = new URLSearchParams();

                    if (requestedQuery) {
                        params.set('q', requestedQuery);
                    }

                    params.set('page', String(searchState.page));
                    params.set('per_page', '20');

                    getSelectedPackageIds()
                        .map((id) => decodePackageKeyValue(id))
                        .filter((value) => value !== null)
                        .forEach((value) => params.append('exclude[]', String(value)));

                    try {
                        const response = await fetch(`${packageEndpoint}?${params.toString()}`, {
                            headers: { Accept: 'application/json' },
                        });

                        if (!response.ok) {
                            throw new Error('Request failed');
                        }

                        const payload = await response.json();

                        if (requestedQuery !== searchState.query) {
                            return;
                        }
                        const items = Array.isArray(payload?.data) ? payload.data : [];

                        if (reset) {
                            clearModalResults();
                        }

                        if (items.length === 0) {
                            const emptyMessage = searchState.query
                                ? '{{ __('Tidak ada barang yang cocok dengan pencarian.') }}'
                                : '{{ __('Belum ada data untuk ditampilkan.') }}';
                            setModalEmptyMessage(emptyMessage, false);
                        } else {
                            setModalEmptyMessage('', true);

                            items.forEach((pkg) => {
                                ensurePackageMapEntry(pkg);
                                const element = renderResultItem(pkg);
                                if (element) {
                                    modalResults.appendChild(element);
                                }
                            });
                        }

                        searchState.hasMore = Boolean(payload?.meta?.has_more);

                        if (modalMoreButton) {
                            modalMoreButton.hidden = !searchState.hasMore;
                        }
                    } catch (error) {
                        setModalEmptyMessage('{{ __('Terjadi kesalahan saat memuat data barang.') }}', false);
                        searchState.hasMore = false;

                        if (!reset && requestedPage > 1) {
                            searchState.page = requestedPage - 1;
                        }

                        if (modalMoreButton) {
                            modalMoreButton.hidden = true;
                        }
                    } finally {
                        if (requestId === currentRequestId) {
                            searchState.loading = false;
                            toggleModalLoading(false);
                        }
                    }
                };

                const closeModal = () => {
                    if (!modal) {
                        return;
                    }

                    modal.classList.add('hidden');
                    modal.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('overflow-hidden');

                    if (searchDebounce) {
                        clearTimeout(searchDebounce);
                        searchDebounce = null;
                    }

                    currentRequestId += 1;
                    searchState.loading = false;
                    searchState.hasMore = false;
                    toggleModalLoading(false);

                    if (modalMoreButton) {
                        modalMoreButton.hidden = true;
                    }

                    if (lastFocusedTrigger instanceof HTMLElement) {
                        lastFocusedTrigger.focus();
                    }
                };

                const openModal = () => {
                    if (!modal || !packageEndpoint) {
                        return;
                    }

                    lastFocusedTrigger = document.activeElement;
                    modal.classList.remove('hidden');
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('overflow-hidden');

                    searchState.query = '';
                    searchState.page = 1;
                    searchState.hasMore = false;
                    searchState.loading = false;
                    clearModalResults();
                    setModalEmptyMessage('{{ __('Ketik minimal 2 huruf untuk mencari barang emas.') }}', false);

                    if (modalMoreButton) {
                        modalMoreButton.hidden = true;
                    }

                    if (modalSearch) {
                        modalSearch.value = '';
                        modalSearch.focus();
                    }
                };

                const handleModalKeydown = (event) => {
                    if (event.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                        closeModal();
                    }
                };

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
                        maximumFractionDigits: 2,
                    }).format(Number.isFinite(value) ? value : 0);

                const parseCurrencyInput = (value) => {
                    const sanitized = String(value ?? '').replace(/[^0-9]/g, '');
                    return sanitized ? parseInt(sanitized, 10) : 0;
                };

                const parsePercentageInput = (value) => {
                    const sanitized = String(value ?? '')
                        .replace(/[^0-9.,-]/g, '')
                        .replace(',', '.');
                    const parsed = parseFloat(sanitized);
                    return Number.isFinite(parsed) ? parsed : 0;
                };

                const getDownPaymentMode = () =>
                    downPaymentModeHidden?.value === 'percentage' ? 'percentage' : 'nominal';

                const getAdministrationValue = () => {
                    if (!administrationInput) {
                        return 0;
                    }

                    const raw = administrationInput.value;

                    if (raw === '' || raw === null || raw === undefined) {
                        return 0;
                    }

                    const parsed = Number.parseFloat(raw);

                    if (!Number.isFinite(parsed) || parsed < 0) {
                        return 0;
                    }

                    return Math.round(parsed);
                };

                const resolveMarginPercentage = (tenor) => {
                    if (!Number.isFinite(Number(tenor))) {
                        return Number(marginConfig?.default_percentage ?? 0);
                    }

                    const overrides = marginConfig?.tenor_overrides ?? {};
                    const tenorKey = String(tenor);
                    if (Object.prototype.hasOwnProperty.call(overrides, tenorKey)) {
                        const overrideValue = Number(overrides[tenorKey]);
                        return Number.isFinite(overrideValue)
                            ? overrideValue
                            : Number(marginConfig?.default_percentage ?? 0);
                    }

                    return Number(marginConfig?.default_percentage ?? 0);
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
                    if (!downPaymentLabel) return;
                    downPaymentLabel.textContent =
                        mode === 'percentage'
                            ? '{{ __('Persen') }}'
                            : '{{ __('Rupiah') }}';
                };

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
                    let sanitized = Number.isFinite(value) ? value : 0;
                    sanitized = Math.min(Math.max(sanitized, 0), 100);
                    if (downPaymentPercentageHidden) {
                        downPaymentPercentageHidden.value = (Math.round(sanitized * 100) / 100).toFixed(2);
                    }
                    if (getDownPaymentMode() === 'percentage' && downPaymentInput) {
                        downPaymentInput.value = formatPercentage(sanitized);
                    }
                };

                const refreshDownPaymentInput = () => {
                    const mode = getDownPaymentMode();
                    if (mode === 'percentage') {
                        if (downPaymentInput) {
                            downPaymentInput.value = formatPercentage(getPercentageValue());
                        }
                    } else if (downPaymentInput) {
                        downPaymentInput.value = formatNumber(getNominalValue());
                    }
                };

                const toggleTenorCardsDisabled = (disabled) => {
                    tenorCards.forEach((card) => {
                        const option = card.querySelector('[data-tenor-option]');
                        if (option) {
                            option.disabled = disabled;
                        }
                        if (disabled) {
                            card.classList.add('pointer-events-none', 'opacity-60');
                        } else {
                            card.classList.remove('pointer-events-none', 'opacity-60');
                        }
                    });
                };

                const updateTenorCardsState = (selectedValue) => {
                    tenorCards.forEach((card) => {
                        const option = card.querySelector('[data-tenor-option]');
                        if (!option) return;
                        const isSelected = String(option.value) === String(selectedValue ?? '');
                        card.classList.toggle('border-indigo-500', isSelected);
                        card.classList.toggle('ring-2', isSelected);
                        card.classList.toggle('ring-indigo-500/40', isSelected);
                        card.classList.toggle('bg-indigo-50', isSelected);
                        card.classList.toggle('dark:bg-indigo-500/10', isSelected);
                    });
                };

                const updateTenorCaptions = (totalPrice, downPayment, administration) => {
                    tenorOptions.forEach((option) => {
                        const card = option.closest('[data-tenor-card]');
                        const caption = card?.querySelector('[data-tenor-caption]');
                        if (!caption) return;

                        if (!totalPrice) {
                            caption.textContent = '{{ __('Pilih barang terlebih dahulu.') }}';
                            return;
                        }

                        const tenorValue = Number(option.value);
                        const principalBalance = Math.max(totalPrice - downPayment, 0);
                        const administrationAmount = Math.max(Number(administration) || 0, 0);
                        const marginPercentage = resolveMarginPercentage(tenorValue);
                        const marginAmount =
                            Math.round(principalBalance * (marginPercentage / 100) * 100) / 100;
                        const totalFinanced = principalBalance + marginAmount + administrationAmount;
                        const installment =
                            tenorValue > 0 ? Math.round((totalFinanced / tenorValue) * 100) / 100 : 0;
                        caption.textContent = `${formatCurrency(installment)} {{ __('per bulan') }}`;
                    });
                };

                const setCheckedTenor = (value) => {
                    tenorOptions.forEach((option) => {
                        option.checked = String(option.value) === String(value ?? '');
                    });
                };

                const ensureTenorSelection = () => {
                    if (!tenorOptions.length) {
                        if (tenorHidden) {
                            tenorHidden.value = '';
                        }
                        return '';
                    }

                    const currentValue = tenorHidden?.value;
                    if (
                        currentValue &&
                        tenorOptions.some(
                            (option) => String(option.value) === String(currentValue),
                        )
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

                function updateOutputs() {
                    const selectedPackages = getSelectedPackages();
                    const totalPrice = selectedPackages.reduce(
                        (total, pkg) => total + Number(pkg?.harga ?? 0),
                        0,
                    );
                    const totalWeight = selectedPackages.reduce(
                        (total, pkg) => total + Number(pkg?.berat ?? 0),
                        0,
                    );
                    const hasSelection = selectedPackages.length > 0;
                    const mode = getDownPaymentMode();

                    updateEmptyState();
                    toggleTenorCardsDisabled(!hasSelection);

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
                        const computed =
                            hasSelection && totalPrice > 0
                                ? (totalPrice * percent) / 100
                                : 0;
                        downPaymentAmount = Math.round(computed * 100) / 100;
                        setNominalValue(downPaymentAmount);
                    } else {
                        let nominal = getNominalValue();
                        if (!Number.isFinite(nominal) || nominal < 0) {
                            nominal = 0;
                        }
                        if (hasSelection && totalPrice > 0 && nominal > totalPrice) {
                            nominal = totalPrice;
                        }
                        setNominalValue(nominal);
                        percentValue =
                            totalPrice > 0
                                ? Math.round((nominal / totalPrice) * 100 * 100) / 100
                                : 0;
                        setPercentageValue(percentValue);
                        downPaymentAmount = nominal;
                    }

                    refreshDownPaymentInput();
                    updateTenorCardsState(tenorHidden?.value);
                    const administrationAmount = Math.max(getAdministrationValue(), 0);
                    updateTenorCaptions(totalPrice, downPaymentAmount, administrationAmount);

                    if (!hasSelection) {
                        if (packageMeta) {
                            packageMeta.textContent =
                                '{{ __('Belum ada barang dipilih. Gunakan tombol “Tambah Barang” untuk memulai.') }}';
                        }
                        if (downPaymentDisplay) {
                            downPaymentDisplay.textContent =
                                mode === 'percentage'
                                    ? '{{ __('Masukkan persentase uang muka (0-100%) untuk melihat estimasi cicilan.') }}'
                                    : '{{ __('Masukkan uang muka untuk menghitung besaran cicilan.') }}';
                        }
                        if (tenorMeta) {
                            tenorMeta.textContent =
                                '{{ __('Pilih barang emas terlebih dahulu sebelum menentukan jangka waktu.') }}';
                        }
                        if (installmentHidden) installmentHidden.value = '';
                        if (installmentOutput) installmentOutput.value = '';
                        if (installmentDisplay) {
                            installmentDisplay.textContent =
                                '{{ __('Besaran angsuran dihitung dari sisa harga emas dibagi tenor yang dipilih.') }}';
                        }
                        if (marginDisplay) {
                            marginDisplay.textContent =
                                '{{ __('Margin akan dihitung setelah paket dan tenor dipilih.') }}';
                        }
                        if (financingDisplay) {
                            financingDisplay.textContent =
                                '{{ __('Total pembiayaan akan tampil setelah simulasi lengkap.') }}';
                        }
                        if (summaryPanel) summaryPanel.hidden = true;
                        if (summaryPackage) {
                            summaryPackage.textContent =
                                '{{ __('Barang belum dipilih.') }}';
                        }
                        if (summaryItemsList) summaryItemsList.innerHTML = '';
                        if (summaryPrice) summaryPrice.textContent = '';
                        if (summaryOption) summaryOption.textContent = '';
                        if (summaryPrincipal) summaryPrincipal.textContent = '';
                        if (summaryMargin) summaryMargin.textContent = '';
                        if (summaryAdministration) summaryAdministration.textContent = '';
                        if (summaryFinancing) summaryFinancing.textContent = '';
                        if (administrationDisplay) {
                            administrationDisplay.textContent =
                                '{{ __('Jika diisi, biaya administrasi akan ditambahkan ke total pembiayaan cicilan.') }}';
                        }
                        return;
                    }

                    const tenorValue = Number(tenorHidden?.value ?? 0);
                    const principalBalance = Math.max(totalPrice - downPaymentAmount, 0);
                    const marginPercentage = resolveMarginPercentage(tenorValue);
                    const marginAmount =
                        Math.round(principalBalance * (marginPercentage / 100) * 100) / 100;
                    const totalFinanced = principalBalance + marginAmount + administrationAmount;
                    const installment =
                        tenorValue > 0
                            ? Math.round((totalFinanced / tenorValue) * 100) / 100
                            : 0;

                    const weightDisplay = totalWeight.toLocaleString('id-ID', {
                        minimumFractionDigits: 3,
                        maximumFractionDigits: 3,
                    });

                    const summaryLines = selectedPackages.map((pkg) => {
                        const group = pkg?.kode_group || pkg?.kode_intern || '—';
                        const weight = Number(pkg?.berat ?? 0).toLocaleString('id-ID', {
                            minimumFractionDigits: 3,
                            maximumFractionDigits: 3,
                        });
                        return `${pkg?.nama_barang ?? '{{ __('Barang') }}'} • ${weight} gr • ${group} • ${formatCurrency(Number(pkg?.harga ?? 0))}`;
                    });

                    if (packageMeta) {
                        const metaLabel = selectedPackages.length === 1
                            ? summaryLines[0]
                            : `${selectedPackages.length} {{ __('barang dipilih') }} • ${weightDisplay} gr • ${formatCurrency(totalPrice)}`;
                        packageMeta.textContent = metaLabel;
                    }

                    if (summaryPanel) summaryPanel.hidden = false;
                    if (summaryPackage) {
                        summaryPackage.textContent = selectedPackages.length === 1
                            ? summaryLines[0]
                            : `${selectedPackages.length} {{ __('barang dipilih') }} • ${weightDisplay} gr`;
                    }
                    if (summaryItemsList) {
                        summaryItemsList.innerHTML = '';
                        summaryLines.forEach((line) => {
                            const li = document.createElement('li');
                            li.textContent = line;
                            summaryItemsList.appendChild(li);
                        });
                    }
                    if (summaryPrice) {
                        summaryPrice.textContent =
                            `{{ __('Total Harga Barang') }}: ${formatCurrency(totalPrice)}`;
                    }
                    if (summaryOption) {
                        summaryOption.textContent =
                            `{{ __('Total Berat') }}: ${weightDisplay} gr • {{ __('Tenor') }}: ${tenorValue} {{ __('bulan') }}`;
                    }
                    if (summaryPrincipal) {
                        summaryPrincipal.textContent = `{{ __('Pokok Pembiayaan') }}: ${formatCurrency(principalBalance)}`;
                    }
                    if (summaryMargin) {
                        summaryMargin.textContent = `{{ __('Margin Pembiayaan') }}: ${formatCurrency(marginAmount)} (${formatPercentage(marginPercentage)}%)`;
                    }
                    if (summaryAdministration) {
                        summaryAdministration.textContent = `{{ __('Biaya Administrasi') }}: ${formatCurrency(administrationAmount)}`;
                    }
                    if (summaryFinancing) {
                        summaryFinancing.textContent = `{{ __('Total Pembiayaan') }}: ${formatCurrency(totalFinanced)}`;
                    }

                    if (installmentHidden) {
                        installmentHidden.value = installment.toFixed(2);
                    }
                    if (installmentOutput) {
                        installmentOutput.value = installment
                            ? formatNumber(installment)
                            : '';
                    }

                    if (downPaymentDisplay) {
                        const percentText = percentValue.toLocaleString('id-ID', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 2,
                        });
                        downPaymentDisplay.textContent =
                            `${formatCurrency(downPaymentAmount)} • ${percentText}% {{ __('dari total harga barang') }}`;
                    }
                    if (tenorMeta) {
                        tenorMeta.textContent = tenorValue
                            ? `{{ __('Cicilan selama :bulan bulan.', ['bulan' => ':bulan']) }}`.replace(
                                  ':bulan',
                                  tenorValue,
                              )
                            : '{{ __('Pilih tenor cicilan untuk melihat estimasi angsuran.') }}';
                    }
                    if (installmentDisplay) {
                        installmentDisplay.textContent =
                            installment
                                ? `{{ __('Estimasi angsuran: :amount per bulan', ['amount' => ':amount']) }}`.replace(
                                      ':amount',
                                      formatCurrency(installment),
                                  )
                                : '{{ __('Besaran angsuran dihitung dari sisa harga emas dibagi tenor yang dipilih.') }}';
                    }
                    if (marginDisplay) {
                        marginDisplay.textContent = `${formatCurrency(marginAmount)} ({{ __('Margin') }} ${formatPercentage(marginPercentage)}%)`;
                    }
                    if (financingDisplay) {
                        financingDisplay.textContent = `{{ __('Total pembiayaan: :amount', ['amount' => ':amount']) }}`.replace(
                            ':amount',
                            formatCurrency(totalFinanced),
                        );
                    }
                    if (administrationDisplay) {
                        administrationDisplay.textContent = administrationAmount > 0
                            ? `{{ __('Biaya Administrasi') }}: ${formatCurrency(administrationAmount)}`
                            : '{{ __('Jika diisi, biaya administrasi akan ditambahkan ke total pembiayaan cicilan.') }}';
                    }
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
                        if (totalPrice > 0) {
                            const percentFromNominal = (nominal / totalPrice) * 100;
                            setPercentageValue(percentFromNominal);
                        }
                        setPercentageValue(getPercentageValue());
                    } else {
                        const percent = getPercentageValue();
                        if (totalPrice > 0) {
                            const nominalFromPercent = (totalPrice * percent) / 100;
                            setNominalValue(nominalFromPercent);
                        }
                        setNominalValue(getNominalValue());
                    }

                    refreshModeButtons(sanitizedMode);
                    refreshModeLabel(sanitizedMode);
                    refreshDownPaymentInput();
                    updateOutputs();
                };
                const initialTenor = ensureTenorSelection();
                updateTenorCardsState(initialTenor);
                setNominalValue(getNominalValue());
                setPercentageValue(getPercentageValue());
                refreshModeButtons(getDownPaymentMode());
                refreshModeLabel(getDownPaymentMode());
                refreshDownPaymentInput();
                updateOutputs();

                if (packageList) {
                    Array.from(packageList.querySelectorAll('[data-package-remove]')).forEach((button) => {
                        button.addEventListener('click', (event) => {
                            event.preventDefault();
                            const id = button.getAttribute('data-package-remove');
                            if (id) {
                                removePackage(id);
                            }
                        });
                    });
                }

                packageOpenButton?.addEventListener('click', (event) => {
                    event.preventDefault();
                    openModal();
                });

                modalBackdrop?.addEventListener('click', () => {
                    closeModal();
                });

                modalCloseButtons.forEach((button) => {
                    button.addEventListener('click', () => {
                        closeModal();
                    });
                });

                modalSearch?.addEventListener('input', () => {
                    const term = (modalSearch.value ?? '').toString().trim();
                    searchState.query = term;

                    if (searchDebounce) {
                        clearTimeout(searchDebounce);
                    }

                    if (!term || term.length < minimumSearchLength) {
                        clearModalResults();
                        setModalEmptyMessage('{{ __('Ketik minimal 2 huruf untuk mencari barang emas.') }}', false);
                        searchState.hasMore = false;
                        if (modalMoreButton) {
                            modalMoreButton.hidden = true;
                        }
                        return;
                    }

                    searchDebounce = setTimeout(() => {
                        loadPackages({ reset: true });
                    }, 250);
                });

                modalMoreButton?.addEventListener('click', () => {
                    if (searchState.loading || !searchState.hasMore) {
                        return;
                    }

                    searchState.page += 1;
                    loadPackages();
                });

                if (modal && !modal.dataset.keydownBound) {
                    document.addEventListener('keydown', handleModalKeydown);
                    modal.dataset.keydownBound = 'true';
                }

                if (downPaymentInput) {
                    downPaymentInput.addEventListener('input', () => {
                        const mode = getDownPaymentMode();
                        if (mode === 'percentage') {
                            const rawPercent = parsePercentageInput(downPaymentInput.value);
                            setPercentageValue(rawPercent);
                        } else {
                            const rawNominal = parseCurrencyInput(downPaymentInput.value);
                            setNominalValue(rawNominal);
                        }
                        updateOutputs();
                    });

                    downPaymentInput.addEventListener('blur', () => {
                        refreshDownPaymentInput();
                    });
                }

                downPaymentModeButtons.forEach((button) => {
                    button.addEventListener('click', () => {
                        const mode = button.getAttribute('data-down-payment-mode-button');
                        applyDownPaymentMode(mode);
                    });
                });

                tenorOptions.forEach((option) => {
                    option.addEventListener('change', () => {
                        if (!option.checked) return;
                        if (tenorHidden) {
                            tenorHidden.value = option.value;
                        }
                        setCheckedTenor(option.value);
                        updateTenorCardsState(option.value);
                        updateOutputs();
                    });
                });

                administrationInput?.addEventListener('input', () => {
                    if (administrationInput.value !== '' && Number.parseFloat(administrationInput.value ?? '0') < 0) {
                        administrationInput.value = '0';
                    }

                    updateOutputs();
                });

                administrationInput?.addEventListener('blur', () => {
                    let value = getAdministrationValue();

                    if (!Number.isFinite(value) || value < 0) {
                        value = 0;
                    }

                    administrationInput.value = value ? String(Math.round(value)) : '0';
                    updateOutputs();
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initCicilEmas, { once: true });
            } else {
                initCicilEmas();
            }

            document.addEventListener('livewire:navigated', () => {
                initCicilEmas();
            });
        })();
    </script>
</x-layouts.app>
