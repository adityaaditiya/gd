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
                        {{ __('Reset Filter') }}
                    </button>
                </div>
                <label class="flex w-full items-center gap-3 rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-600 shadow-sm focus-within:border-emerald-500 focus-within:text-neutral-900 focus-within:ring-2 focus-within:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-300 dark:focus-within:border-emerald-400 dark:focus-within:text-white dark:focus-within:ring-emerald-900/40 lg:max-w-lg" for="nasabahBaruSearch">
                    <svg class="size-5 text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <div class="flex w-full flex-col">
                        <!-- <span class="text-xs font-medium uppercase tracking-wide text-neutral-400">{{ __('Pencarian Global') }}</span> -->
                        <input
                            id="nasabahBaruSearch"
                            type="search"
                            placeholder="{{ __('Cari berdasarkan nama, NIK, alamat, dll...') }}"
                            class="w-full border-0 bg-transparent p-0 text-sm text-neutral-900 placeholder:text-neutral-400 focus:outline-none focus:ring-0 dark:text-white"
                            value="{{ $initialFilters['search'] }}"
                        />
                    </div>
                </label>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 text-left text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200">
                    <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">{{ __('Kode Member') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Nama') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Usia') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Telepon') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Kota') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Kecamatan') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Alamat') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('NIK') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Identitas Lain') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Tanggal Pendaftaran') }}</th>
                        </tr>
                    </thead>
                    <tbody id="nasabahBaruTableBody" class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800"></tbody>
                </table>
            </div>

            <div class="flex flex-col gap-4 border-t border-neutral-200 bg-neutral-50 px-4 py-3 text-sm text-neutral-600 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-200 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <label for="nasabahBaruRowsPerPage" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Rows per page</label>
                    <span id="nasabahBaruRowsPerPageValue" class="text-sm font-semibold text-neutral-900 dark:text-white">10</span>
                    <select
                        id="nasabahBaruRowsPerPage"
                        class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm font-medium text-neutral-700 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                    >
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end sm:gap-4">
                    <nav id="nasabahBaruPagination" class="flex flex-wrap items-center gap-2" aria-label="{{ __('Navigasi halaman nasabah baru') }}"></nav>
                </div>
            </div>

            <div id="nasabahBaruEmptyState" class="hidden border-t border-neutral-200 bg-neutral-50 px-4 py-6 text-center text-sm text-neutral-600 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">
                {{ __('Tidak ada data nasabah baru yang sesuai dengan filter saat ini.') }}
            </div>
        </div>
    </div>

    {{-- SCRIPT: navigate-once + namespace agar tidak redeclare saat Livewire swap --}}
    <script data-navigate-once>
        const nasabahBaruInitialDataset = @js($nasabahs);
        const nasabahBaruSearchEndpoint = @js(route('nasabah.nasabah-baru'));
        const nasabahBaruInitialFilters = @js($initialFilters);

        // Namespace global aman
        window.KRESNO = window.KRESNO || {};
        (function () {
            if (window.KRESNO.nasabahBaru?.initializedScript) {
                // Skrip sudah didefinisikan sebelumnya (karena navigate), jangan define ulang
                return;
            }

            function initializeNasabahBaruPage() {
                const container = document.getElementById('nasabah-baru-page');
                if (!container || container.dataset.initialized === 'true') return;
                container.dataset.initialized = 'true';

                const toRecord = (item) => {
                    const tanggalLahir = item?.tanggal_lahir ?? '';
                    const age = (() => {
                        const numericAge = Number(item?.usia);
                        if (Number.isFinite(numericAge) && numericAge >= 0) {
                            return numericAge;
                        }

                        return calculateAge(tanggalLahir);
                    })();

                    return {
                        id: item?.id ?? null,
                        nik: item?.nik ?? '',
                        identitas_lain: item?.identitas_lain ?? item?.id_lain ?? '',
                        nama: item?.nama ?? '',
                        telepon: item?.telepon ?? '',
                        kota: item?.kota ?? '',
                        kecamatan: item?.kecamatan ?? '',
                        alamat: item?.alamat ?? '',
                        kode_member: item?.kode_member ?? '',
                        tanggal_pendaftaran: item?.tanggal_pendaftaran ?? '',
                        tanggal_lahir: tanggalLahir,
                        usia: age,
                    };
                };

                let dataset = Array.isArray(window.__nasabahBaruDataset)
                    ? window.__nasabahBaruDataset
                    : Array.isArray(nasabahBaruInitialDataset)
                        ? nasabahBaruInitialDataset
                        : [];

                dataset = dataset.map(toRecord);
                window.__nasabahBaruDataset = dataset;

                const tableBody = document.getElementById('nasabahBaruTableBody');
                const emptyState = document.getElementById('nasabahBaruEmptyState');
                const searchInput = document.getElementById('nasabahBaruSearch');
                const dateFromInput = document.getElementById('nasabahBaruDateFrom');
                const dateToInput = document.getElementById('nasabahBaruDateTo');
                const resetFiltersButton = document.getElementById('nasabahBaruResetFilters');
                const rowsPerPageSelect = document.getElementById('nasabahBaruRowsPerPage');
                const rowsPerPageValue = document.getElementById('nasabahBaruRowsPerPageValue');
                const paginationContainer = document.getElementById('nasabahBaruPagination');

                const initialPageSize = Number(rowsPerPageSelect?.value ?? 10) || 10;

                const state = {
                    search: nasabahBaruInitialFilters?.search ?? '',
                    dateFrom: nasabahBaruInitialFilters?.date_from ?? '',
                    dateTo: nasabahBaruInitialFilters?.date_to ?? '',
                    abortController: null,
                    debounceId: null,
                    pageSize: initialPageSize,
                    currentPage: 1,
                    totalPages: 1,
                };

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
                    if (!value) return '';
                    const [year, month, day] = value.split('-');
                    return `${day}/${month}/${year}`;
                };

                function calculateAge(value) {
                    const dateString = String(value ?? '').trim();
                    if (!dateString) return null;

                    const parts = dateString.split('-');
                    if (parts.length !== 3) return null;

                    const year = Number(parts[0]);
                    const month = Number(parts[1]);
                    const day = Number(parts[2]);

                    if (!Number.isFinite(year) || !Number.isFinite(month) || !Number.isFinite(day)) {
                        return null;
                    }

                    const birthDate = new Date(Date.UTC(year, month - 1, day));
                    if (Number.isNaN(birthDate.getTime())) return null;

                    const today = new Date();
                    let age = today.getUTCFullYear() - birthDate.getUTCFullYear();
                    const monthDiff = today.getUTCMonth() - birthDate.getUTCMonth();
                    const dayDiff = today.getUTCDate() - birthDate.getUTCDate();

                    if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
                        age -= 1;
                    }

                    return age >= 0 ? age : null;
                }

                const formatAge = (value) => {
                    const numeric = Number(value);
                    return Number.isFinite(numeric) && numeric >= 0 ? String(numeric) : '-';
                };

                const updateRowsPerPageDisplay = () => {
                    if (rowsPerPageValue) {
                        rowsPerPageValue.textContent = String(state.pageSize);
                    }

                    if (rowsPerPageSelect && Number(rowsPerPageSelect.value) !== Number(state.pageSize)) {
                        rowsPerPageSelect.value = String(state.pageSize);
                    }
                };

                const getPaginationSnapshot = (records) => {
                    const items = Array.isArray(records) ? records : [];
                    const pageSize = Number(state.pageSize) > 0 ? Number(state.pageSize) : 10;
                    const totalRecords = items.length;
                    const totalPages = totalRecords > 0 ? Math.ceil(totalRecords / pageSize) : 1;

                    let currentPage = Number.isInteger(state.currentPage) ? state.currentPage : 1;
                    if (currentPage < 1) currentPage = 1;
                    if (currentPage > totalPages) currentPage = totalPages;

                    state.pageSize = pageSize;
                    state.currentPage = currentPage;
                    state.totalPages = totalPages;

                    const startIndex = totalRecords === 0 ? 0 : (currentPage - 1) * pageSize;
                    const endIndex = totalRecords === 0 ? 0 : Math.min(startIndex + pageSize, totalRecords);

                    return {
                        totalRecords,
                        totalPages,
                        startIndex,
                        endIndex,
                        pageItems: totalRecords === 0 ? [] : items.slice(startIndex, endIndex),
                    };
                };

                const renderPaginationControls = ({ totalRecords, totalPages }) => {
                    if (!paginationContainer) return;

                    if (!totalRecords) {
                        paginationContainer.innerHTML = '';
                        return;
                    }

                    const baseButtonClass = 'inline-flex items-center justify-center rounded-lg border border-neutral-300 px-3 py-1.5 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-700/60 dark:focus:ring-emerald-900/40';
                    const disabledClass = 'cursor-not-allowed opacity-50';
                    const activeClass = 'border-neutral-900 bg-neutral-900 text-white hover:bg-neutral-900 focus:ring-neutral-200 dark:border-neutral-100 dark:bg-neutral-100 dark:text-neutral-900 dark:hover:bg-neutral-100';

                    const isFirstPage = state.currentPage === 1;
                    const isLastPage = state.currentPage === totalPages;

                    const parts = [];

                    parts.push(`<button type="button" class="${baseButtonClass} ${isFirstPage ? disabledClass : ''}" data-page-control="first" ${isFirstPage ? 'disabled' : ''}>&lt;&lt; First</button>`);
                    parts.push(`<button type="button" class="${baseButtonClass} ${isFirstPage ? disabledClass : ''}" data-page-control="prev" ${isFirstPage ? 'disabled' : ''}>&lt; Back</button>`);

                    const visiblePages = 5;
                    let startPage = Math.max(1, state.currentPage - Math.floor(visiblePages / 2));
                    let endPage = startPage + visiblePages - 1;

                    if (endPage > totalPages) {
                        endPage = totalPages;
                        startPage = Math.max(1, endPage - visiblePages + 1);
                    }

                    for (let page = startPage; page <= endPage; page += 1) {
                        const isActive = state.currentPage === page;
                        parts.push(`<button type="button" class="${baseButtonClass} ${isActive ? activeClass : ''}" data-page-number="${page}" ${isActive ? 'aria-current="page"' : ''}>${page}</button>`);
                    }

                    parts.push(`<button type="button" class="${baseButtonClass} ${isLastPage ? disabledClass : ''}" data-page-control="next" ${isLastPage ? 'disabled' : ''}>Next &gt;</button>`);
                    parts.push(`<button type="button" class="${baseButtonClass} ${isLastPage ? disabledClass : ''}" data-page-control="last" ${isLastPage ? 'disabled' : ''}>Last &gt;&gt;</button>`);

                    paginationContainer.innerHTML = parts.join('');
                };

                const goToPage = (page) => {
                    const totalRecords = Array.isArray(dataset) ? dataset.length : 0;
                    const pageSize = Number(state.pageSize) > 0 ? Number(state.pageSize) : 10;
                    const totalPages = totalRecords > 0 ? Math.ceil(totalRecords / pageSize) : 1;
                    const targetPage = Math.min(Math.max(1, Number(page) || 1), totalPages);

                    if (targetPage === state.currentPage) {
                        renderTable();
                        return;
                    }

                    state.currentPage = targetPage;
                    renderTable();
                };

                const renderTable = () => {
                    if (!tableBody) return;

                    if (!Array.isArray(dataset) || dataset.length === 0) {
                        tableBody.innerHTML = '';
                        emptyState?.classList.remove('hidden');
                        state.totalPages = 1;
                        if (paginationContainer) {
                            paginationContainer.innerHTML = '';
                        }
                        updateRowsPerPageDisplay();
                        return;
                    }

                    const snapshot = getPaginationSnapshot(dataset);
                    const rows = snapshot.pageItems.map((item) => `
                        <tr>
                            <td class="whitespace-nowrap px-4 py-3 font-semibold text-neutral-900 dark:text-white">${escapeHtml(item.kode_member)}</td>
                            
                            <td class="px-4 py-3 text-neutral-700 dark:text-neutral-200">${escapeHtml(item.nama)}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-neutral-700 dark:text-neutral-200">${escapeHtml(formatAge(item.usia))} Tahun</td>
                            <td class="whitespace-nowrap px-4 py-3 text-neutral-700 dark:text-neutral-200">${escapeHtml(item.telepon)}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-neutral-700 dark:text-neutral-200">${escapeHtml(item.kota)}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-neutral-700 dark:text-neutral-200">${escapeHtml(item.kecamatan)}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-neutral-700 dark:text-neutral-200">${escapeHtml(item.alamat)}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-neutral-700 dark:text-neutral-200">${escapeHtml(item.nik)}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-neutral-700 dark:text-neutral-200">${escapeHtml(item.identitas_lain || '-')}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-neutral-700 dark:text-neutral-200">${escapeHtml(formatDate(item.tanggal_pendaftaran))}</td>
                        </tr>
                    `);

                    tableBody.innerHTML = rows.join('');
                    emptyState?.classList.add('hidden');
                    renderPaginationControls(snapshot);
                    updateRowsPerPageDisplay();
                };

                const applyFilters = () => {
                    // Jika semua filter kosong → kembali ke dataset awal tanpa fetch
                    const searchTerm = String(state.search ?? '').trim();
                    const dateFrom = sanitizeDateValue(state.dateFrom);
                    const dateTo = sanitizeDateValue(state.dateTo);

                    state.currentPage = 1;

                    if (!searchTerm && !dateFrom && !dateTo) {
                        dataset = Array.isArray(nasabahBaruInitialDataset)
                            ? nasabahBaruInitialDataset.map(toRecord)
                            : [];
                        window.__nasabahBaruDataset = dataset;
                        state.currentPage = 1;
                        renderTable();
                        return;
                    }

                    if (state.abortController) state.abortController.abort();
                    state.abortController = new AbortController();

                    const params = new URLSearchParams();
                    if (searchTerm) params.set('search', searchTerm);
                    if (dateFrom) params.set('date_from', dateFrom);
                    if (dateTo) params.set('date_to', dateTo);

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
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        })
                        .then((payload) => {
                            const items = Array.isArray(payload?.data) ? payload.data.map(toRecord) : [];
                            dataset = items;
                            window.__nasabahBaruDataset = dataset;
                            state.currentPage = 1;
                            renderTable();
                        })
                        .catch((error) => {
                            if (error.name === 'AbortError') return;
                            console.error('Gagal memuat data nasabah baru:', error);
                        });
                };

                const debounceFetch = () => {
                    if (state.debounceId) window.clearTimeout(state.debounceId);
                    state.debounceId = window.setTimeout(() => applyFilters(), 400);
                };

                // --- Event handlers ---
                if (searchInput) {
                    // input ketika mengetik
                    searchInput.addEventListener('input', (event) => {
                        state.search = event.target.value;
                        debounceFetch();
                    });
                    // event 'search' saat klik ikon ❌ (Chrome/Safari)
                    searchInput.addEventListener('search', (event) => {
                        if (event.target.value === '') {
                            state.search = '';
                            // batalkan request berjalan
                            if (state.abortController) {
                                state.abortController.abort();
                                state.abortController = null;
                            }
                            // jika semua filter kosong → reset ke dataset awal
                            applyFilters();
                        }
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

                        if (searchInput) searchInput.value = '';
                        if (dateFromInput) dateFromInput.value = '';
                        if (dateToInput) dateToInput.value = '';

                        applyFilters();
                    });
                }

                if (rowsPerPageSelect) {
                    rowsPerPageSelect.addEventListener('change', (event) => {
                        const value = Number(event.target.value);
                        state.pageSize = !Number.isNaN(value) && value > 0 ? value : initialPageSize;
                        state.currentPage = 1;
                        renderTable();
                    });
                }

                if (paginationContainer) {
                    paginationContainer.addEventListener('click', (event) => {
                        const button = event.target.closest('button[data-page-control], button[data-page-number]');
                        if (!button || button.disabled) return;

                        if (button.dataset.pageNumber) {
                            goToPage(Number(button.dataset.pageNumber));
                            return;
                        }

                        switch (button.dataset.pageControl) {
                            case 'first':
                                goToPage(1);
                                break;
                            case 'prev':
                                goToPage(state.currentPage - 1);
                                break;
                            case 'next':
                                goToPage(state.currentPage + 1);
                                break;
                            case 'last':
                                goToPage(state.totalPages);
                                break;
                            default:
                                break;
                        }
                    });
                }

                // Set nilai awal input dari state
                if (searchInput && typeof state.search === 'string') searchInput.value = state.search;
                if (dateFromInput && state.dateFrom) dateFromInput.value = state.dateFrom;
                if (dateToInput && state.dateTo) dateToInput.value = state.dateTo;

                // Render pertama
                renderTable();

                // Jika ada filter awal → apply langsung
                if (state.search || state.dateFrom || state.dateTo) {
                    applyFilters();
                }
            }

            // Expose ke namespace + guard supaya tak didefinisikan 2x
            window.KRESNO.nasabahBaru = {
                init: initializeNasabahBaruPage,
                initializedScript: true,
            };

            // Boot pertama + saat Livewire navigasi
            function bootNasabahBaru() {
                window.KRESNO.nasabahBaru.init();
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', bootNasabahBaru, { once: true });
            } else {
                bootNasabahBaru();
            }

            // Livewire v3: panggil init setiap halaman di-swap
            document.addEventListener('livewire:navigated', bootNasabahBaru);
        })();
    </script>
</x-layouts.app>
