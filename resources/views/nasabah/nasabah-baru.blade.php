@php
    $pageTitle = $pageTitle ?? __('Nasabah Baru');
    $initialFilters = $filters ?? [
        'date_from' => null,
        'date_to' => null,
        'search' => '',
    ];
@endphp

<x-layouts.app :title="$pageTitle">
    <div class="space-y-8" id="nasabah-baru-page" data-initialized="false">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ $pageTitle }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Daftar nasabah baru yang terdaftar pada sistem. Data pada tabel ini bersifat hanya-baca.') }}
            </p>
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex flex-col gap-4 border-b border-neutral-200 p-4 dark:border-neutral-700 lg:flex-row lg:items-center lg:justify-between">
                <label class="flex w-full items-center gap-3 rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-600 shadow-sm focus-within:border-emerald-500 focus-within:text-neutral-900 focus-within:ring-2 focus-within:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-300 dark:focus-within:border-emerald-400 dark:focus-within:text-white dark:focus-within:ring-emerald-900/40 lg:max-w-lg" for="nasabahBaruSearch">
                    <svg class="size-5 text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <div class="flex w-full flex-col">
                        <span class="text-xs font-medium uppercase tracking-wide text-neutral-400">{{ __('Pencarian Global') }}</span>
                        <input
                            id="nasabahBaruSearch"
                            type="search"
                            placeholder="{{ __('Cari berdasarkan nama, NIK, alamat, dll...') }}"
                            class="w-full border-0 bg-transparent p-0 text-sm text-neutral-900 placeholder:text-neutral-400 focus:outline-none focus:ring-0 dark:text-white"
                            value="{{ $initialFilters['search'] }}"
                        />
                    </div>
                </label>

                <div class="flex w-full flex-col gap-3 lg:w-auto lg:flex-row lg:items-end lg:gap-4">
                    <label class="flex flex-col gap-2 text-sm text-neutral-600 dark:text-neutral-200">
                        <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Tanggal Dari') }}</span>
                        <input
                            id="nasabahBaruDateFrom"
                            type="date"
                            value="{{ $initialFilters['date_from'] }}"
                            class="rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-200 dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                        />
                    </label>
                    <label class="flex flex-col gap-2 text-sm text-neutral-600 dark:text-neutral-200">
                        <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Tanggal Sampai') }}</span>
                        <input
                            id="nasabahBaruDateTo"
                            type="date"
                            value="{{ $initialFilters['date_to'] }}"
                            class="rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-200 dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                        />
                    </label>
                    <button
                        type="button"
                        id="nasabahBaruResetFilters"
                        class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-700/60"
                    >
                        {{ __('Atur Ulang Filter') }}
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 text-left text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200">
                    <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">{{ __('Kode Member') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('NIK') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Nama') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Telepon') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Kota') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Tanggal Pendaftaran') }}</th>
                        </tr>
                    </thead>
                    <tbody id="nasabahBaruTableBody" class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800"></tbody>
                </table>
            </div>

            <div id="nasabahBaruEmptyState" class="hidden border-t border-neutral-200 bg-neutral-50 px-4 py-6 text-center text-sm text-neutral-600 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">
                {{ __('Tidak ada data nasabah baru yang sesuai dengan filter saat ini.') }}
            </div>
        </div>
    </div>

    <script>
        const nasabahBaruInitialDataset = @js($nasabahs);
        const nasabahBaruSearchEndpoint = @js(route('nasabah.nasabah-baru'));
        const nasabahBaruInitialFilters = @js($initialFilters);

        (() => {
            function initializeNasabahBaruPage() {
                const container = document.getElementById('nasabah-baru-page');

                if (!container || container.dataset.initialized === 'true') {
                    return;
                }

                container.dataset.initialized = 'true';

                const toRecord = (item) => ({
                    id: item?.id ?? null,
                    nik: item?.nik ?? '',
                    nama: item?.nama ?? '',
                    telepon: item?.telepon ?? '',
                    kota: item?.kota ?? '',
                    kode_member: item?.kode_member ?? '',
                    tanggal_pendaftaran: item?.tanggal_pendaftaran ?? '',
                });

                let dataset = Array.isArray(window.__nasabahBaruDataset)
                    ? window.__nasabahBaruDataset
                    : Array.isArray(nasabahBaruInitialDataset)
                        ? nasabahBaruInitialDataset
                        : [];

                dataset = dataset.map(toRecord);
                window.__nasabahBaruDataset = dataset;

                const state = {
                    search: nasabahBaruInitialFilters?.search ?? '',
                    dateFrom: nasabahBaruInitialFilters?.date_from ?? '',
                    dateTo: nasabahBaruInitialFilters?.date_to ?? '',
                    abortController: null,
                    debounceId: null,
                };

                const tableBody = document.getElementById('nasabahBaruTableBody');
                const emptyState = document.getElementById('nasabahBaruEmptyState');
                const searchInput = document.getElementById('nasabahBaruSearch');
                const dateFromInput = document.getElementById('nasabahBaruDateFrom');
                const dateToInput = document.getElementById('nasabahBaruDateTo');
                const resetFiltersButton = document.getElementById('nasabahBaruResetFilters');

                const sanitizeDateValue = (value) => {
                    const candidate = String(value ?? '').trim();
                    return /^\d{4}-\d{2}-\d{2}$/.test(candidate) ? candidate : '';
                };

                const escapeHtml = (value) =>
                    String(value ?? '')
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#39;');

                const formatDate = (value) => {
                    if (!value) {
                        return '';
                    }

                    const [year, month, day] = value.split('-');
                    return `${day}/${month}/${year}`;
                };

                const renderTable = () => {
                    if (!tableBody) {
                        return;
                    }

                    if (!Array.isArray(dataset) || dataset.length === 0) {
                        tableBody.innerHTML = '';

                        if (emptyState) {
                            emptyState.classList.remove('hidden');
                        }

                        return;
                    }

                    const rows = dataset.map((item) => `
                        <tr>
                            <td class="whitespace-nowrap px-4 py-3 font-semibold text-neutral-900 dark:text-white">${escapeHtml(item.kode_member)}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-neutral-700 dark:text-neutral-200">${escapeHtml(item.nik)}</td>
                            <td class="px-4 py-3 text-neutral-700 dark:text-neutral-200">${escapeHtml(item.nama)}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-neutral-700 dark:text-neutral-200">${escapeHtml(item.telepon)}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-neutral-700 dark:text-neutral-200">${escapeHtml(item.kota)}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-neutral-700 dark:text-neutral-200">${escapeHtml(formatDate(item.tanggal_pendaftaran))}</td>
                        </tr>
                    `);

                    tableBody.innerHTML = rows.join('');

                    if (emptyState) {
                        emptyState.classList.add('hidden');
                    }
                };

                const applyFilters = () => {
                    if (state.abortController) {
                        state.abortController.abort();
                    }

                    const params = new URLSearchParams();

                    const searchTerm = String(state.search ?? '').trim();
                    const dateFrom = sanitizeDateValue(state.dateFrom);
                    const dateTo = sanitizeDateValue(state.dateTo);

                    if (searchTerm !== '') {
                        params.set('search', searchTerm);
                    }

                    if (dateFrom) {
                        params.set('date_from', dateFrom);
                    }

                    if (dateTo) {
                        params.set('date_to', dateTo);
                    }

                    state.abortController = new AbortController();

                    const queryString = params.toString();
                    const requestUrl = queryString ? `${nasabahBaruSearchEndpoint}?${queryString}` : nasabahBaruSearchEndpoint;

                    fetch(requestUrl, {
                        method: 'GET',
                        signal: state.abortController.signal,
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    })
                        .then((response) => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }

                            return response.json();
                        })
                        .then((payload) => {
                            const items = Array.isArray(payload?.data) ? payload.data.map(toRecord) : [];
                            dataset = items;
                            window.__nasabahBaruDataset = dataset;
                            renderTable();
                        })
                        .catch((error) => {
                            if (error.name === 'AbortError') {
                                return;
                            }

                            console.error('Gagal memuat data nasabah baru:', error);
                        });
                };

                const debounceFetch = () => {
                    if (state.debounceId) {
                        window.clearTimeout(state.debounceId);
                    }

                    state.debounceId = window.setTimeout(() => {
                        applyFilters();
                    }, 400);
                };

                if (searchInput) {
                    searchInput.addEventListener('input', (event) => {
                        state.search = event.target.value;
                        debounceFetch();
                    });
                }

                if (dateFromInput) {
                    dateFromInput.addEventListener('change', (event) => {
                        state.dateFrom = event.target.value;
                        applyFilters();
                    });
                }

                if (dateToInput) {
                    dateToInput.addEventListener('change', (event) => {
                        state.dateTo = event.target.value;
                        applyFilters();
                    });
                }

                if (resetFiltersButton) {
                    resetFiltersButton.addEventListener('click', () => {
                        state.search = '';
                        state.dateFrom = '';
                        state.dateTo = '';

                        if (searchInput) {
                            searchInput.value = '';
                        }

                        if (dateFromInput) {
                            dateFromInput.value = '';
                        }

                        if (dateToInput) {
                            dateToInput.value = '';
                        }

                        applyFilters();
                    });
                }

                if (searchInput && typeof state.search === 'string') {
                    searchInput.value = state.search;
                }

                if (dateFromInput && state.dateFrom) {
                    dateFromInput.value = state.dateFrom;
                }

                if (dateToInput && state.dateTo) {
                    dateToInput.value = state.dateTo;
                }

                renderTable();

                if (state.search || state.dateFrom || state.dateTo) {
                    applyFilters();
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initializeNasabahBaruPage, { once: true });
            } else {
                initializeNasabahBaruPage();
            }
        })();
    </script>
</x-layouts.app>
