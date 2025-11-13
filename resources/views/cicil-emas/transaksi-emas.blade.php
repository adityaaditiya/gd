<x-layouts.app :title="__('Transaksi Emas')">
    @php
        $packagesCollection = collect($packages ?? []);
        $selectedPackageId = old('package_id');
        $selectedPackage = $packagesCollection->firstWhere('id', $selectedPackageId);
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
                            <dd>{{ $summary['paket'] ?? '—' }}</dd>
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
                                <dd>{{ number_format($summary['administrasi'], 2, ',', '.') }}</dd>
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
                                <label for="package_id" class="mb-2 block text-sm font-semibold text-neutral-800 dark:text-neutral-200">
                                    {{ __('Data Barang') }}
                                </label>
                                <select
                                    id="package_id"
                                    name="package_id"
                                    data-package-select
                                    class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white"
                                    required
                                    @disabled($packagesCollection->isEmpty())
                                >
                                    <option value="">{{ $packagesCollection->isEmpty() ? __('Belum ada data barang tersedia') : __('Pilih barang emas') }}</option>
                                    @foreach ($packagesCollection as $package)
                                        <option value="{{ $package['id'] }}" @selected($selectedPackageId === $package['id'])>
                                            {{ $package['nama_barang'] }} — {{ $package['kode_group'] ?? $package['kode_intern'] }} • {{ number_format((float) $package['berat'], 3, ',', '.') }} gr
                                        </option>
                                    @endforeach
                                </select>
                                @error('package_id')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400" data-package-meta>
                                    {{ __('Silakan pilih barang emas untuk melihat detail harga.') }}
                                </p>
                                @if ($packagesCollection->isEmpty())
                                    <p class="mt-3 text-xs text-amber-600 dark:text-amber-400">
                                        {{ __('Belum ada data barang. Tambahkan entri melalui halaman Data Barang terlebih dahulu.') }}
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
                                        step="0.01"
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
                            <ul class="mt-2 space-y-1">
                                <li data-summary-package>{{ __('Barang belum dipilih.') }}</li>
                                <li data-summary-price></li>
                                <li data-summary-option></li>
                                <li data-summary-principal></li>
                                <li data-summary-margin></li>
                                <li data-summary-administration></li>
                                <li data-summary-financing></li>
                            </ul>
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
                const packageSelect = document.querySelector('[data-package-select]');
                const packageMeta = document.querySelector('[data-package-meta]');
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
                const summaryPrice = document.querySelector('[data-summary-price]');
                const summaryOption = document.querySelector('[data-summary-option]');
                const summaryPrincipal = document.querySelector('[data-summary-principal]');
                const summaryMargin = document.querySelector('[data-summary-margin]');
                const summaryFinancing = document.querySelector('[data-summary-financing]');
                const administrationInput = document.querySelector('[data-administration-input]');
                const administrationDisplay = document.querySelector('[data-administration-display]');
                const summaryAdministration = document.querySelector('[data-summary-administration]');

                if (!packageSelect) {
                    // DOM belum siap atau kita bukan di halaman ini
                    return;
                }

                const findPackage = (id) => packages.find((pkg) => String(pkg.id) === String(id));

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

                    return parsed;
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

                const updateOutputs = () => {
                    const selectedPackage = findPackage(packageSelect?.value);
                    const totalPrice = Number(selectedPackage?.harga ?? 0);
                    const hasPackage = Boolean(selectedPackage);
                    const mode = getDownPaymentMode();

                    toggleTenorCardsDisabled(!hasPackage);

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
                            hasPackage && totalPrice > 0
                                ? (totalPrice * percent) / 100
                                : 0;
                        downPaymentAmount = Math.round(computed * 100) / 100;
                        setNominalValue(downPaymentAmount);
                    } else {
                        let nominal = getNominalValue();
                        if (!Number.isFinite(nominal) || nominal < 0) {
                            nominal = 0;
                        }
                        if (hasPackage && totalPrice > 0 && nominal > totalPrice) {
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

                    if (!hasPackage) {
                        if (packageMeta) {
                            packageMeta.textContent =
                                '{{ __('Silakan pilih barang emas untuk melihat detail harga.') }}';
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

                    const beratDisplay = Number(selectedPackage.berat ?? 0).toLocaleString('id-ID', {
                        minimumFractionDigits: 3,
                        maximumFractionDigits: 3,
                    });
                    const groupLabel =
                        selectedPackage.kode_group ||
                        selectedPackage.kode_intern ||
                        '—';

                    if (packageMeta) {
                        packageMeta.textContent = `${selectedPackage.nama_barang} • ${beratDisplay} gr • ${groupLabel}`;
                    }
                    if (summaryPanel) summaryPanel.hidden = false;
                    if (summaryPackage) {
                        summaryPackage.textContent = `${selectedPackage.nama_barang} • ${beratDisplay} gr • ${groupLabel}`;
                    }
                    if (summaryPrice) {
                        summaryPrice.textContent =
                            `{{ __('Harga Barang') }}: ${formatCurrency(totalPrice)}`;
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
                            `${formatCurrency(downPaymentAmount)} • ${percentText}% {{ __('dari harga barang') }}`;
                    }
                    if (tenorMeta) {
                        tenorMeta.textContent = tenorValue
                            ? `{{ __('Cicilan selama :bulan bulan.', ['bulan' => ':bulan']) }}`.replace(
                                  ':bulan',
                                  tenorValue,
                              )
                            : '{{ __('Pilih jangka waktu cicilan untuk melihat estimasi angsuran per bulan.') }}';
                    }
                    if (installmentDisplay) {
                        installmentDisplay.textContent = tenorValue
                            ? `${formatCurrency(installment)} • {{ __('dibayar setiap bulan selama :bulan bulan', ['bulan' => ':bulan']) }}`.replace(
                                  ':bulan',
                                  tenorValue,
                              )
                            : '{{ __('Besaran angsuran dihitung dari sisa harga emas dibagi tenor yang dipilih.') }}';
                    }
                    if (marginDisplay) {
                        marginDisplay.textContent = tenorValue
                            ? `${formatCurrency(marginAmount)} • ${formatPercentage(marginPercentage)}% {{ __('tarif margin') }}`
                            : '{{ __('Margin akan dihitung setelah paket dan tenor dipilih.') }}';
                    }
                    if (administrationDisplay) {
                        administrationDisplay.textContent = administrationAmount > 0
                            ? `{{ __('Biaya Administrasi') }}: ${formatCurrency(administrationAmount)} {{ __('akan ditambahkan ke total pembiayaan.') }}`
                            : '{{ __('Jika diisi, biaya administrasi akan ditambahkan ke total pembiayaan cicilan.') }}';
                    }
                    if (financingDisplay) {
                        financingDisplay.textContent = tenorValue
                            ? `{{ __('Total Pembiayaan') }}: ${formatCurrency(totalFinanced)} • {{ __('Pokok Pembiayaan') }} ${formatCurrency(principalBalance)} • {{ __('Margin') }} ${formatCurrency(marginAmount)} • {{ __('Administrasi') }} ${formatCurrency(administrationAmount)}`
                            : '{{ __('Total pembiayaan akan tampil setelah simulasi lengkap.') }}';
                    }
                    if (summaryOption) {
                        summaryOption.textContent = tenorValue
                            ? `{{ __('Jangka waktu: :bulan bulan', ['bulan' => ':bulan']) }}`.replace(
                                  ':bulan',
                                  tenorValue,
                              ) +
                              ` • {{ __('Angsuran') }} ${formatCurrency(installment)}`
                            : '';
                    }
                    if (summaryPrincipal) {
                        summaryPrincipal.textContent = `{{ __('Pokok Pembiayaan') }}: ${formatCurrency(principalBalance)}`;
                    }
                    if (summaryMargin) {
                        summaryMargin.textContent = `{{ __('Margin') }}: ${formatCurrency(marginAmount)} • ${formatPercentage(marginPercentage)}%`;
                    }
                    if (summaryAdministration) {
                        summaryAdministration.textContent = `{{ __('Biaya Administrasi') }}: ${formatCurrency(administrationAmount)}`;
                    }
                    if (summaryFinancing) {
                        summaryFinancing.textContent = `{{ __('Total Pembiayaan') }}: ${formatCurrency(totalFinanced)}`;
                    }
                };

                const applyDownPaymentMode = (mode) => {
                    const sanitizedMode = mode === 'percentage' ? 'percentage' : 'nominal';
                    if (downPaymentModeHidden) {
                        downPaymentModeHidden.value = sanitizedMode;
                    }

                    const selectedPackage = findPackage(packageSelect?.value);
                    const totalPrice = Number(selectedPackage?.harga ?? 0);

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

                packageSelect?.addEventListener('change', () => {
                    updateOutputs();
                });

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

                    administrationInput.value = value ? value.toFixed(2) : '0';
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
