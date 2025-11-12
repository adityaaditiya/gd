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
        $defaultDownPaymentValue = old('estimasi_uang_muka');
        if (! is_numeric($defaultDownPaymentValue)) {
            $defaultDownPaymentValue = $defaultDownPayment ?? 1_000_000;
        }
        $defaultDownPaymentValue = (float) $defaultDownPaymentValue;
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
                            <dd>{{ isset($summary['dp']) ? number_format($summary['dp'], 2, ',', '.') : '—' }}</dd>
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

        <div class="grid gap-6 xl:grid-cols-3">
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
            </div>

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
                                <div class="flex overflow-hidden rounded-lg border border-neutral-300 bg-white shadow-sm dark:border-neutral-600 dark:bg-neutral-800">
                                    <span class="flex items-center justify-center bg-neutral-100 px-3 text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
                                        {{ __('Rupiah') }}
                                    </span>
                                    <input
                                        id="uang_muka_display"
                                        type="text"
                                        inputmode="numeric"
                                        data-down-payment-input
                                        value="{{ number_format((float) $defaultDownPaymentValue, 0, ',', '.') }}"
                                        class="w-full border-0 bg-transparent px-3 py-2 text-sm font-semibold text-neutral-900 focus:outline-none focus:ring-0 dark:text-white"
                                        autocomplete="off"
                                    />
                                </div>
                                <input type="hidden" name="estimasi_uang_muka" data-down-payment-hidden value="{{ $defaultDownPaymentValue }}">
                                @error('estimasi_uang_muka')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400" data-down-payment-display>
                                    {{ __('Masukkan uang muka untuk menghitung besaran cicilan.') }}
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

                            <div>
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
                            </div>
                        </div>

                        <div class="rounded-lg bg-neutral-100 px-4 py-3 text-sm text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200" data-summary-panel hidden>
                            <p class="font-semibold text-neutral-900 dark:text-white">{{ __('Ringkasan Barang') }}</p>
                            <ul class="mt-2 space-y-1">
                                <li data-summary-package>{{ __('Barang belum dipilih.') }}</li>
                                <li data-summary-price></li>
                                <li data-summary-option></li>
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
        document.addEventListener('DOMContentLoaded', () => {
            const packages = @json($packagesCollection);
            const packageSelect = document.querySelector('[data-package-select]');
            const packageMeta = document.querySelector('[data-package-meta]');
            const downPaymentInput = document.querySelector('[data-down-payment-input]');
            const downPaymentHidden = document.querySelector('[data-down-payment-hidden]');
            const downPaymentDisplay = document.querySelector('[data-down-payment-display]');
            const tenorHidden = document.querySelector('[data-tenor-input]');
            const tenorMeta = document.querySelector('[data-tenor-meta]');
            const tenorCards = Array.from(document.querySelectorAll('[data-tenor-card]'));
            const tenorOptions = Array.from(document.querySelectorAll('[data-tenor-option]'));
            const installmentHidden = document.querySelector('[data-installment]');
            const installmentOutput = document.querySelector('[data-installment-output]');
            const installmentDisplay = document.querySelector('[data-installment-display]');
            const summaryPanel = document.querySelector('[data-summary-panel]');
            const summaryPackage = document.querySelector('[data-summary-package]');
            const summaryPrice = document.querySelector('[data-summary-price]');
            const summaryOption = document.querySelector('[data-summary-option]');

            const findPackage = (id) => packages.find((pkg) => pkg.id === id);

            const formatCurrency = (value) =>
                new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(
                    Number.isFinite(value) ? value : 0,
                );

            const formatNumber = (value) =>
                new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(
                    Number.isFinite(value) ? Math.round(value) : 0,
                );

            const parseCurrencyInput = (value) => {
                const sanitized = String(value ?? '').replace(/[^0-9]/g, '');
                return sanitized ? parseInt(sanitized, 10) : 0;
            };

            const setDownPaymentValue = (value) => {
                const normalized = Number.isFinite(value) ? Math.max(value, 0) : 0;
                if (downPaymentHidden) {
                    downPaymentHidden.value = (Math.round(normalized * 100) / 100).toFixed(2);
                }
                if (downPaymentInput) {
                    downPaymentInput.value = formatNumber(normalized);
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

            const updateTenorCaptions = (totalPrice, downPayment) => {
                tenorOptions.forEach((option) => {
                    const card = option.closest('[data-tenor-card]');
                    const caption = card?.querySelector('[data-tenor-caption]');
                    if (!caption) {
                        return;
                    }
                    if (!totalPrice) {
                        caption.textContent = '{{ __('Pilih barang terlebih dahulu.') }}';
                        return;
                    }

                    const tenorValue = Number(option.value);
                    const remaining = Math.max(totalPrice - downPayment, 0);
                    const installment = tenorValue > 0 ? Math.round((remaining / tenorValue) * 100) / 100 : 0;
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
                if (currentValue && tenorOptions.some((option) => String(option.value) === String(currentValue))) {
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

                toggleTenorCardsDisabled(!hasPackage);

                let downPayment = Number.parseFloat(downPaymentHidden?.value ?? '0');
                if (!Number.isFinite(downPayment) || downPayment < 0) {
                    downPayment = 0;
                }

                if (hasPackage && totalPrice > 0 && downPayment > totalPrice) {
                    downPayment = totalPrice;
                    setDownPaymentValue(downPayment);
                }

                updateTenorCardsState(tenorHidden?.value);
                updateTenorCaptions(totalPrice, downPayment);

                if (!hasPackage) {
                    if (packageMeta) {
                        packageMeta.textContent = '{{ __('Silakan pilih barang emas untuk melihat detail harga.') }}';
                    }
                    if (downPaymentDisplay) {
                        downPaymentDisplay.textContent = '{{ __('Masukkan uang muka untuk menghitung besaran cicilan.') }}';
                    }
                    if (tenorMeta) {
                        tenorMeta.textContent = '{{ __('Pilih barang emas terlebih dahulu sebelum menentukan jangka waktu.') }}';
                    }
                    if (installmentHidden) {
                        installmentHidden.value = '';
                    }
                    if (installmentOutput) {
                        installmentOutput.value = '';
                    }
                    if (installmentDisplay) {
                        installmentDisplay.textContent = '{{ __('Besaran angsuran dihitung dari sisa harga emas dibagi tenor yang dipilih.') }}';
                    }
                    if (summaryPanel) {
                        summaryPanel.hidden = true;
                    }
                    if (summaryPackage) {
                        summaryPackage.textContent = '{{ __('Barang belum dipilih.') }}';
                    }
                    if (summaryPrice) {
                        summaryPrice.textContent = '';
                    }
                    if (summaryOption) {
                        summaryOption.textContent = '';
                    }
                    return;
                }

                const beratDisplay = Number(selectedPackage.berat ?? 0).toLocaleString('id-ID', {
                    minimumFractionDigits: 3,
                    maximumFractionDigits: 3,
                });
                const groupLabel = selectedPackage.kode_group || selectedPackage.kode_intern || '—';

                if (packageMeta) {
                    packageMeta.textContent = `${selectedPackage.nama_barang} • ${beratDisplay} gr • ${groupLabel}`;
                }
                if (summaryPanel) {
                    summaryPanel.hidden = false;
                }
                if (summaryPackage) {
                    summaryPackage.textContent = `${selectedPackage.nama_barang} • ${beratDisplay} gr • ${groupLabel}`;
                }
                if (summaryPrice) {
                    summaryPrice.textContent = `{{ __('Harga Barang') }}: ${formatCurrency(totalPrice)}`;
                }

                const tenorValue = Number(tenorHidden?.value ?? 0);
                const remaining = Math.max(totalPrice - downPayment, 0);
                const installment = tenorValue > 0 ? Math.round((remaining / tenorValue) * 100) / 100 : 0;

                if (installmentHidden) {
                    installmentHidden.value = installment.toFixed(2);
                }
                if (installmentOutput) {
                    installmentOutput.value = installment ? formatNumber(installment) : '';
                }

                const percent = totalPrice > 0 ? Math.round((downPayment / totalPrice) * 10000) / 100 : 0;

                if (downPaymentDisplay) {
                    downPaymentDisplay.textContent = `${formatCurrency(downPayment)} • {{ __('Sekitar :persen% dari harga', ['persen' => ':persen']) }}`.replace(
                        ':persen',
                        percent.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 }),
                    );
                }
                if (tenorMeta) {
                    tenorMeta.textContent = tenorValue
                        ? `{{ __('Cicilan selama :bulan bulan.', ['bulan' => ':bulan']) }}`.replace(':bulan', tenorValue)
                        : '{{ __('Pilih jangka waktu cicilan untuk melihat estimasi angsuran per bulan.') }}';
                }
                if (installmentDisplay) {
                    installmentDisplay.textContent = tenorValue
                        ? `${formatCurrency(installment)} • {{ __('dibayar setiap bulan selama :bulan bulan', ['bulan' => ':bulan']) }}`.replace(':bulan', tenorValue)
                        : '{{ __('Besaran angsuran dihitung dari sisa harga emas dibagi tenor yang dipilih.') }}';
                }
                if (summaryOption) {
                    summaryOption.textContent = tenorValue
                        ? `{{ __('Jangka waktu: :bulan bulan', ['bulan' => ':bulan']) }}`.replace(':bulan', tenorValue) + ` • {{ __('Angsuran') }} ${formatCurrency(installment)}`
                        : '';
                }
            };

            const initialTenor = ensureTenorSelection();
            updateTenorCardsState(initialTenor);

            const initialHiddenValue = Number.parseFloat(downPaymentHidden?.value ?? '0');
            if (!Number.isFinite(initialHiddenValue) || initialHiddenValue <= 0) {
                const parsed = downPaymentInput ? parseCurrencyInput(downPaymentInput.value) : 0;
                setDownPaymentValue(parsed);
            } else if (downPaymentInput) {
                downPaymentInput.value = formatNumber(initialHiddenValue);
            }

            updateOutputs();

            packageSelect?.addEventListener('change', () => {
                updateOutputs();
            });

            if (downPaymentInput) {
                downPaymentInput.addEventListener('input', () => {
                    const raw = parseCurrencyInput(downPaymentInput.value);
                    const selectedPackage = findPackage(packageSelect?.value);
                    const totalPrice = Number(selectedPackage?.harga ?? 0);
                    const adjusted = totalPrice > 0 ? Math.min(raw, totalPrice) : raw;
                    setDownPaymentValue(adjusted);
                    updateOutputs();
                });

                downPaymentInput.addEventListener('blur', () => {
                    const hiddenValue = Number.parseFloat(downPaymentHidden?.value ?? '0');
                    downPaymentInput.value = formatNumber(hiddenValue);
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
                    setCheckedTenor(option.value);
                    updateTenorCardsState(option.value);
                    updateOutputs();
                });
            });
        });
    </script>
</x-layouts.app>
