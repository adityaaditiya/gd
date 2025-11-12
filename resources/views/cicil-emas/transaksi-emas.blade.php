<x-layouts.app :title="__('Transaksi Emas')">
    @php
        $packagesCollection = collect($packages ?? []);
        $selectedPackageId = old('package_id');
        $selectedPackage = $packagesCollection->firstWhere('id', $selectedPackageId);
        $selectedOptionId = old('option_id');
    @endphp
    <div class="space-y-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Transaksi Cicil Emas') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Lakukan simulasi cicilan dengan memilih nasabah, paket emas, dan kombinasi DP–tenor untuk menghasilkan estimasi pembayaran yang otomatis tersimpan.') }}
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
                            @if (!empty($summary['kombinasi']))
                                <dd class="text-xs text-neutral-500 dark:text-neutral-400">{{ $summary['kombinasi'] }}</dd>
                            @endif
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
                        <li>{{ __('Tentukan paket emas berdasarkan pabrikan, berat, dan kadar yang tersedia.') }}</li>
                        <li>{{ __('Sesuaikan kombinasi DP–tenor untuk melihat estimasi angsuran yang dihitung otomatis.') }}</li>
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
                                    {{ __('Paket Emas') }}
                                </label>
                                <select
                                    id="package_id"
                                    name="package_id"
                                    data-package-select
                                    class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white"
                                    required
                                >
                                    <option value="">{{ __('Pilih paket emas') }}</option>
                                    @foreach ($packagesCollection as $package)
                                        <option value="{{ $package['id'] }}" @selected($selectedPackageId === $package['id'])>
                                            {{ $package['pabrikan'] }} — {{ number_format($package['berat_gram'], 2, ',', '.') }} gr • {{ $package['kadar'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('package_id')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400" data-package-meta>
                                    {{ __('Silakan pilih paket emas untuk melihat detail harga.') }}
                                </p>
                            </div>

                            <div>
                                <label for="option_id" class="mb-2 block text-sm font-semibold text-neutral-800 dark:text-neutral-200">
                                    {{ __('Kombinasi DP & Tenor') }}
                                </label>
                                <select
                                    id="option_id"
                                    name="option_id"
                                    data-option-select
                                    class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white"
                                    @if (! $selectedPackage) disabled @endif
                                    required
                                >
                                    <option value="">{{ __('Pilih kombinasi DP & tenor') }}</option>
                                    @if ($selectedPackage)
                                        @foreach ($selectedPackage['options'] as $option)
                                            <option value="{{ $option['id'] }}" @selected($selectedOptionId === $option['id'])>
                                                {{ $option['label'] }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('option_id')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400" data-option-meta>
                                    {{ __('Pilih paket terlebih dahulu untuk melihat kombinasi DP & tenor yang tersedia.') }}
                                </p>
                            </div>

                            <div>
                                <label for="estimasi_uang_muka" class="mb-2 block text-sm font-semibold text-neutral-800 dark:text-neutral-200">
                                    {{ __('Estimasi Uang Muka (DP)') }}
                                </label>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    id="estimasi_uang_muka"
                                    name="estimasi_uang_muka"
                                    data-down-payment
                                    value="{{ old('estimasi_uang_muka') }}"
                                    readonly
                                    class="w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:outline-none dark:border-neutral-600 dark:bg-neutral-800 dark:text-white"
                                />
                                @error('estimasi_uang_muka')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400" data-down-payment-display>
                                    {{ __('Nilai DP akan dihitung otomatis setelah memilih paket dan kombinasi tenor.') }}
                                </p>
                            </div>

                            <div>
                                <label for="tenor_bulan" class="mb-2 block text-sm font-semibold text-neutral-800 dark:text-neutral-200">
                                    {{ __('Tenor (bulan)') }}
                                </label>
                                <input
                                    type="number"
                                    min="1"
                                    id="tenor_bulan"
                                    name="tenor_bulan"
                                    data-tenor-input
                                    value="{{ old('tenor_bulan') }}"
                                    readonly
                                    class="w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:outline-none dark:border-neutral-600 dark:bg-neutral-800 dark:text-white"
                                />
                                @error('tenor_bulan')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="besaran_angsuran" class="mb-2 block text-sm font-semibold text-neutral-800 dark:text-neutral-200">
                                    {{ __('Estimasi Angsuran Bulanan') }}
                                </label>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    id="besaran_angsuran"
                                    name="besaran_angsuran"
                                    data-installment
                                    value="{{ old('besaran_angsuran') }}"
                                    readonly
                                    class="w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:outline-none dark:border-neutral-600 dark:bg-neutral-800 dark:text-white"
                                />
                                @error('besaran_angsuran')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400" data-installment-display>
                                    {{ __('Besaran angsuran dihitung dari sisa harga emas dibagi tenor yang dipilih.') }}
                                </p>
                            </div>
                        </div>

                        <div class="rounded-lg bg-neutral-100 px-4 py-3 text-sm text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200" data-summary-panel hidden>
                            <p class="font-semibold text-neutral-900 dark:text-white">{{ __('Ringkasan Paket') }}</p>
                            <ul class="mt-2 space-y-1">
                                <li data-summary-package>{{ __('Paket belum dipilih.') }}</li>
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
            const optionSelect = document.querySelector('[data-option-select]');
            const packageMeta = document.querySelector('[data-package-meta]');
            const optionMeta = document.querySelector('[data-option-meta]');
            const downPaymentInput = document.querySelector('[data-down-payment]');
            const tenorInput = document.querySelector('[data-tenor-input]');
            const installmentInput = document.querySelector('[data-installment]');
            const downPaymentDisplay = document.querySelector('[data-down-payment-display]');
            const installmentDisplay = document.querySelector('[data-installment-display]');
            const summaryPanel = document.querySelector('[data-summary-panel]');
            const summaryPackage = document.querySelector('[data-summary-package]');
            const summaryPrice = document.querySelector('[data-summary-price]');
            const summaryOption = document.querySelector('[data-summary-option]');

            const formatCurrency = (value) =>
                new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(
                    Number.isFinite(value) ? value : 0,
                );

            const findPackage = (id) => packages.find((pkg) => pkg.id === id);

            const populateOptions = (pkg) => {
                optionSelect.innerHTML = '';
                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = '{{ __('Pilih kombinasi DP & tenor') }}';
                optionSelect.appendChild(placeholder);

                if (!pkg) {
                    optionSelect.disabled = true;
                    optionMeta.textContent = '{{ __('Pilih paket terlebih dahulu untuk melihat kombinasi DP & tenor yang tersedia.') }}';
                    summaryOption.textContent = '';
                    return;
                }

                (pkg.options || []).forEach((option) => {
                    const optionElement = document.createElement('option');
                    optionElement.value = option.id;
                    optionElement.textContent = option.label;
                    if ('{{ $selectedOptionId }}' === option.id) {
                        optionElement.selected = true;
                    }
                    optionSelect.appendChild(optionElement);
                });

                optionSelect.disabled = false;
                optionMeta.textContent = '{{ __('Pilih kombinasi DP & tenor untuk menghitung estimasi angsuran.') }}';
            };

            const updateOutputs = () => {
                const selectedPackage = findPackage(packageSelect.value);
                const selectedOption = selectedPackage
                    ? (selectedPackage.options || []).find((option) => optionSelect.value && option.id === optionSelect.value)
                    : undefined;

                if (!selectedPackage) {
                    downPaymentInput.value = '';
                    tenorInput.value = '';
                    installmentInput.value = '';
                    downPaymentDisplay.textContent = '{{ __('Nilai DP akan dihitung otomatis setelah memilih paket dan kombinasi tenor.') }}';
                    installmentDisplay.textContent = '{{ __('Besaran angsuran dihitung dari sisa harga emas dibagi tenor yang dipilih.') }}';
                    packageMeta.textContent = '{{ __('Silakan pilih paket emas untuk melihat detail harga.') }}';
                    summaryPanel.hidden = true;
                    summaryPackage.textContent = '{{ __('Paket belum dipilih.') }}';
                    summaryPrice.textContent = '';
                    summaryOption.textContent = '';
                    return;
                }

                const totalPrice = Number(selectedPackage.berat_gram) * Number(selectedPackage.price_per_gram);
                packageMeta.textContent = `${selectedPackage.pabrikan} • ${Number(selectedPackage.berat_gram).toLocaleString('id-ID')} gr • ${selectedPackage.kadar}`;
                summaryPanel.hidden = false;
                summaryPackage.textContent = `${selectedPackage.pabrikan} • ${Number(selectedPackage.berat_gram).toLocaleString('id-ID')} gr • ${selectedPackage.kadar}`;
                summaryPrice.textContent = `{{ __('Total Harga') }}: ${formatCurrency(totalPrice)}`;

                if (!selectedOption) {
                    optionMeta.textContent = '{{ __('Pilih kombinasi DP & tenor untuk menghitung estimasi angsuran.') }}';
                    downPaymentInput.value = '';
                    tenorInput.value = '';
                    installmentInput.value = '';
                    downPaymentDisplay.textContent = '{{ __('Nilai DP akan dihitung otomatis setelah memilih paket dan kombinasi tenor.') }}';
                    summaryOption.textContent = '{{ __('Belum ada kombinasi dipilih.') }}';
                    return;
                }

                const downPayment = Math.round(totalPrice * Number(selectedOption.dp_percentage) * 100) / 100;
                const tenor = Number(selectedOption.tenor || 0);
                const remaining = Math.max(totalPrice - downPayment, 0);
                const installment = tenor > 0 ? Math.round((remaining / tenor) * 100) / 100 : 0;
                const percent = Math.round(Number(selectedOption.dp_percentage || 0) * 100);

                downPaymentInput.value = downPayment.toFixed(2);
                tenorInput.value = tenor;
                installmentInput.value = installment.toFixed(2);

                downPaymentDisplay.textContent = `${formatCurrency(downPayment)} ({{ __('DP :persen%', ['persen' => ':persen']) }})`.replace(':persen', percent);
                installmentDisplay.textContent = `${formatCurrency(installment)} • {{ __('Sisa cicilan dibagi :bulan bulan', ['bulan' => ':bulan']) }}`.replace(':bulan', tenor);
                summaryOption.textContent = `${selectedOption.label} • {{ __('Angsuran') }} ${formatCurrency(installment)}`;
            };

            packageSelect?.addEventListener('change', () => {
                populateOptions(findPackage(packageSelect.value));
                optionSelect.value = '';
                updateOutputs();
            });

            optionSelect?.addEventListener('change', () => {
                updateOutputs();
            });

            if (packageSelect?.value) {
                populateOptions(findPackage(packageSelect.value));
                updateOutputs();
            }
        });
    </script>
</x-layouts.app>
