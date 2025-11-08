@php
    $pageTitle = $pageTitle ?? __('Data Nasabah');
    $searchEndpoint = $searchEndpoint ?? route('nasabah.data-nasabah');
@endphp

<x-layouts.app :title="$pageTitle">
    <div class="space-y-8" id="nasabah-page">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ $pageTitle }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Kelola dan telusuri informasi lengkap nasabah melalui tabel interaktif berikut.') }}
            </p>
        </div>

 @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-700 dark:border-emerald-500/60 dark:bg-emerald-500/10 dark:text-emerald-300">
                <p class="font-semibold text-black">{{ session('status') }}</p>
                @if (session('kode_member'))
                    <p class="mt-1 text-sm text-black">{{ __('Kode member otomatis:') }}</p>
                    <input
                        type="text"
                        readonly
                        value="{{ session('kode_member') }}"
                        class="mt-2 w-full rounded-lg border border-emerald-300 bg-white px-3 py-2 font-semibold tracking-wide text-emerald-700 shadow-sm dark:border-emerald-500/60 dark:bg-neutral-900 dark:text-emerald-300"
                    />
                    <p class="mt-1 text-x text-black" >{{ __('Salin kode ini untuk keperluan verifikasi dan layanan selanjutnya.') }}</p>
                @endif
            </div>
        @endif

        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex flex-col gap-4 border-b border-neutral-200 p-4 dark:border-neutral-700 lg:flex-row lg:items-center lg:justify-between">
                <label class="flex w-full items-center gap-3 rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-600 shadow-sm focus-within:border-emerald-500 focus-within:text-neutral-900 focus-within:ring-2 focus-within:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-300 dark:focus-within:border-emerald-400 dark:focus-within:text-white dark:focus-within:ring-emerald-900/40 lg:max-w-sm"
                    for="nasabahSearch">
                    <svg class="size-5 text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <div class="flex w-full flex-col">
                        <span class="text-xs font-medium uppercase tracking-wide text-neutral-400">{{ __('Cari Data (NIK, Nama, Telepon, dll.)') }}</span>
                        <input
                            id="nasabahSearch"
                            type="search"
                            placeholder="{{ __('Ketik untuk mencari seluruh data kolom...') }}"
                            class="w-full border-0 bg-transparent p-0 text-sm text-neutral-900 placeholder:text-neutral-400 focus:outline-none focus:ring-0 dark:text-white"
                        />
                    </div>
                </label>
<a
                        href="{{ route('nasabah.tambah-nasabah') }}"
                        wire:navigate
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-4 text-sm font-semibold text-blue-600 shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
                    >
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>{{ __('Tambah Nasabah') }}</span>
                    </a>
                </div>
            </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 text-left text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200">
                    <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                        <tr>
                            <th scope="col" class="min-w-[120px] px-4 py-3">
                                <button type="button" class="flex items-center gap-1" data-sort-key="actions" disabled>
                                    <span>{{ __('Aksi') }}</span>
                                </button>
                            </th>
                            <th scope="col" class="min-w-[160px] px-4 py-3">
                                <button type="button" class="flex items-center gap-1" data-sort-key="nik">
                                    <span>{{ __('Kode Member') }}</span>
                                    <span data-sort-icon class="hidden">
                                        <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </span>
                                </button>
                            </th>
                            <th scope="col" class="min-w-[160px] px-4 py-3">
                                <button type="button" class="flex items-center gap-1" data-sort-key="nik">
                                    <span>{{ __('NIK') }}</span>
                                    <span data-sort-icon class="hidden">
                                        <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </span>
                                </button>
                            </th>
                            <th scope="col" class="min-w-[160px] px-4 py-3">
                                <button type="button" class="flex items-center gap-1" data-sort-key="nama">
                                    <span>{{ __('Nama') }}</span>
                                    <span data-sort-icon class="hidden">
                                        <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </span>
                                </button>
                            </th>
                            <th scope="col" class="min-w-[150px] px-4 py-3">
                                <button type="button" class="flex items-center gap-1" data-sort-key="tanggal_lahir">
                                    <span>{{ __('Tanggal Lahir') }}</span>
                                    <span data-sort-icon class="hidden">
                                        <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </span>
                                </button>
                            </th>
                            <th scope="col" class="min-w-[140px] px-4 py-3">
                                <button type="button" class="flex items-center gap-1" data-sort-key="telepon">
                                    <span>{{ __('Telepon') }}</span>
                                    <span data-sort-icon class="hidden">
                                        <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </span>
                                </button>
                            </th>
                            <th scope="col" class="min-w-[140px] px-4 py-3">
                                <button type="button" class="flex items-center gap-1" data-sort-key="kota">
                                    <span>{{ __('Kota') }}</span>
                                    <span data-sort-icon class="hidden">
                                        <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </span>
                                </button>
                            </th>
                            <th scope="col" class="min-w-[160px] px-4 py-3">
                                <button type="button" class="flex items-center gap-1" data-sort-key="kecamatan">
                                    <span>{{ __('Kecamatan') }}</span>
                                    <span data-sort-icon class="hidden">
                                        <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </span>
                                </button>
                            </th>
                            <th scope="col" class="min-w-[160px] px-4 py-3">
                                <button type="button" class="flex items-center gap-1" data-sort-key="kecamatan">
                                    <span>{{ __('Alamat') }}</span>
                                    <span data-sort-icon class="hidden">
                                        <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </span>
                                </button>
                            </th>
                            <th scope="col" class="min-w-[140px] px-4 py-3">
                                <button type="button" class="flex items-center gap-1" data-sort-key="npwp">
                                    <span>{{ __('NPWP') }}</span>
                                    <span data-sort-icon class="hidden">
                                        <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </span>
                                </button>
                            </th>
                            <!-- <th scope="col" class="min-w-[140px] px-4 py-3">
                                <button type="button" class="flex items-center gap-1" data-sort-key="nasabah_lama">
                                    <span>{{ __('Nasabah Lama') }}</span>
                                    <span data-sort-icon class="hidden">
                                        <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </span>
                                </button>
                            </th> -->
                            <th scope="col" class="min-w-[150px] px-4 py-3">
                                <button type="button" class="flex items-center gap-1" data-sort-key="id_lain">
                                    <span>{{ __('ID Lain') }}</span>
                                    <span data-sort-icon class="hidden">
                                        <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </span>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="nasabahTableBody" class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800"></tbody>
                </table>
            </div>

            <div class="flex flex-col gap-4 border-t border-neutral-200 bg-neutral-50 px-4 py-3 text-sm text-neutral-600 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-200 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <label for="nasabahRowsPerPage" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Rows per page</label>
                    <span id="nasabahRowsPerPageValue" class="text-sm font-semibold text-neutral-900 dark:text-white">10</span>
                    <select
                        id="nasabahRowsPerPage"
                        class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm font-medium text-neutral-700 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                    >
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end sm:gap-4">
                    <nav id="nasabahPagination" class="flex flex-wrap items-center gap-2" aria-label="{{ __('Navigasi halaman nasabah') }}"></nav>
                </div>
            </div>
        </div>
    </div>

    <script>
        const nasabahInitialDataset = @js($nasabahs);
        const nasabahSearchEndpoint = @js($searchEndpoint);
        (() => {
            function initializeNasabahPage() {
                const container = document.getElementById('nasabah-page');

                if (!container || container.dataset.initialized === 'true') {
                    return;
                }

                container.dataset.initialized = 'true';

                const toRecord = (item) => ({
                    id: item?.id ?? null,
                    nik: item?.nik ?? '',
                    nama: item?.nama ?? '',
                    tempat_lahir: item?.tempat_lahir ?? '',
                    tanggal_lahir: item?.tanggal_lahir ?? '',
                    telepon: item?.telepon ?? '',
                    kota: item?.kota ?? '',
                    kelurahan: item?.kelurahan ?? '',
                    kecamatan: item?.kecamatan ?? '',
                    alamat: item?.alamat ?? '',
                    npwp: item?.npwp ?? '',
                    id_lain: item?.id_lain ?? '',
                    nasabah_lama: Boolean(item?.nasabah_lama),
                    kode_member: item?.kode_member ?? '',
                    created_at: item?.created_at ?? '',
                    edit_url: item?.edit_url ?? '',
                    delete_url: item?.delete_url ?? '',
                });

                let dataset = Array.isArray(window.__nasabahDataset)
                    ? window.__nasabahDataset
                    : Array.isArray(nasabahInitialDataset)
                        ? [...nasabahInitialDataset]
                        : [];

                dataset = dataset.map(toRecord);
                window.__nasabahDataset = dataset;
                const initialDataset = dataset.map((item) => ({ ...item }));

                const searchState = {
                    abortController: null,
                    debounceId: null,
                };

                const tableBody = document.getElementById('nasabahTableBody');
                const searchInput = document.getElementById('nasabahSearch');
                const sortButtons = Array.from(document.querySelectorAll('[data-sort-key]'));
                const rowsPerPageSelect = document.getElementById('nasabahRowsPerPage');
                const rowsPerPageValue = document.getElementById('nasabahRowsPerPageValue');
                const paginationContainer = document.getElementById('nasabahPagination');
                const csrfToken = document.head.querySelector('meta[name="csrf-token"]')?.content ?? '';
                const paginationLabels = {
                    first: 'First',
                    back: 'Back',
                    next: 'Next',
                    last: 'Last',
                };

                const defaultPageSize = Number(rowsPerPageSelect?.value ?? 10) || 10;

                const state = {
                    sortKey: 'created_at',
                    sortDirection: 'desc',
                    searchTerm: '',
                    pageSize: defaultPageSize,
                    currentPage: 1,
                    totalPages: 1,
                };

                if (!tableBody) {
                    return;
                }

                function escapeAttribute(value) {
                    return String(value ?? '')
                        .replace(/&/g, '&amp;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#39;')
                        .replace(/`/g, '&#96;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;');
                }

                function escapeHtml(value) {
                    return String(value ?? '')
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#39;');
                }

                function formatDate(dateString) {
                    if (!dateString) return '';
                    const [year, month, day] = dateString.split('-');
                    return `${day}/${month}/${year}`;
                }

                function normalize(value) {
                    return String(value ?? '')
                        .toLowerCase()
                        .replace(/\s+/g, ' ')
                        .trim();
                }

                function updateRowsPerPageDisplay() {
                    if (rowsPerPageValue) {
                        rowsPerPageValue.textContent = String(state.pageSize);
                    }

                    if (rowsPerPageSelect && Number(rowsPerPageSelect.value) !== Number(state.pageSize)) {
                        rowsPerPageSelect.value = String(state.pageSize);
                    }
                }

                function renderPaginationControls({ totalRecords, totalPages, startIndex, pageItemsCount }) {
                    if (!paginationContainer) {
                        return;
                    }

                    const baseButtonClass = 'inline-flex items-center justify-center rounded-lg border border-neutral-300 px-3 py-1.5 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-700/60 dark:focus:ring-emerald-900/40';
                    const disabledClass = 'cursor-not-allowed opacity-50';
                    const activeClass = 'border-neutral-900 bg-neutral-900 text-white hover:bg-neutral-900 focus:ring-neutral-200 dark:border-neutral-100 dark:bg-neutral-100 dark:text-neutral-900 dark:hover:bg-neutral-100';

                    const isFirstPage = state.currentPage === 1;
                    const isLastPage = state.currentPage === totalPages;

                    const parts = [];

                    parts.push(`<button type="button" class="${baseButtonClass} ${isFirstPage ? disabledClass : ''}" data-page-control="first" ${isFirstPage ? 'disabled' : ''}>&laquo;&laquo; ${paginationLabels.first}</button>`);
                    parts.push(`<button type="button" class="${baseButtonClass} ${isFirstPage ? disabledClass : ''}" data-page-control="prev" ${isFirstPage ? 'disabled' : ''}>&laquo; ${paginationLabels.back}</button>`);

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

                    parts.push(`<button type="button" class="${baseButtonClass} ${isLastPage ? disabledClass : ''}" data-page-control="next" ${isLastPage ? 'disabled' : ''}>${paginationLabels.next} &raquo;</button>`);
                    parts.push(`<button type="button" class="${baseButtonClass} ${isLastPage ? disabledClass : ''}" data-page-control="last" ${isLastPage ? 'disabled' : ''}>${paginationLabels.last} &raquo;&raquo;</button>`);

                    paginationContainer.innerHTML = parts.join('');
                }

                function renderTable() {
                    const term = normalize(state.searchTerm);

                    const filtered = dataset.filter((item) => {
                        if (!term) {
                            return true;
                        }

                        const haystack = [
                            item.nik,
                            item.nama,
                            item.tempat_lahir,
                            formatDate(item.tanggal_lahir),
                            item.telepon,
                            item.kota,
                            item.kelurahan,
                            item.kecamatan,
                            item.alamat,
                            item.npwp,
                            item.nasabah_lama ? 'ya' : 'tidak',
                            item.id_lain,
                            item.kode_member,
                        ]
                            .map(normalize)
                            .join(' ');

                        return haystack.includes(term);
                    });

                    filtered.sort((a, b) => {
                        const { sortKey, sortDirection } = state;

                        if (sortKey === 'nasabah_lama') {
                            return sortDirection === 'asc'
                                ? Number(a.nasabah_lama) - Number(b.nasabah_lama)
                                : Number(b.nasabah_lama) - Number(a.nasabah_lama);
                        }

                        if (sortKey === 'created_at') {
                            const aTime = new Date(a?.created_at ?? '').getTime() || 0;
                            const bTime = new Date(b?.created_at ?? '').getTime() || 0;
                            return state.sortDirection === 'asc' ? aTime - bTime : bTime - aTime;
                        }

                        if (sortKey === 'tanggal_lahir') {
                            const aTime = a.tanggal_lahir ? new Date(a.tanggal_lahir).getTime() : 0;
                            const bTime = b.tanggal_lahir ? new Date(b.tanggal_lahir).getTime() : 0;
                            return sortDirection === 'asc' ? aTime - bTime : bTime - aTime;
                        }

                        const aValue = normalize(a?.[sortKey]);
                        const bValue = normalize(b?.[sortKey]);

                        if (aValue < bValue) return sortDirection === 'asc' ? -1 : 1;
                        if (aValue > bValue) return sortDirection === 'asc' ? 1 : -1;
                        return 0;
                    });

                    const totalRecords = filtered.length;
                    const totalPages = totalRecords ? Math.ceil(totalRecords / state.pageSize) : 1;

                    state.totalPages = totalPages;

                    if (state.currentPage > totalPages) {
                        state.currentPage = totalPages;
                    }

                    if (state.currentPage < 1) {
                        state.currentPage = 1;
                    }

                    const startIndex = totalRecords ? (state.currentPage - 1) * state.pageSize : 0;
                    const pageItems = totalRecords
                        ? filtered.slice(startIndex, Math.min(startIndex + state.pageSize, totalRecords))
                        : [];

                    if (!pageItems.length) {
                        tableBody.innerHTML = `
                            <tr>
                                <td colspan="10" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-300">
                                    {{ __('Data tidak ditemukan untuk kata kunci yang dimasukkan.') }}
                                </td>
                            </tr>
                        `;

                        updateRowsPerPageDisplay();
                        renderPaginationControls({
                            totalRecords,
                            totalPages,
                            startIndex,
                            pageItemsCount: pageItems.length,
                        });

                        return;
                    }

                    const rows = pageItems
                        .map((item) => {
                            const editUrl = escapeAttribute(item.edit_url ?? '#');
                            const deleteUrl = escapeAttribute(item.delete_url ?? '#');
                            const recordId = escapeAttribute(item.id ?? '');

                            return `
                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/40">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <a href="${editUrl}" wire:navigate class="rounded-lg border border-emerald-200 px-2 py-1 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50 focus:outline-none dark:border-emerald-400/40 dark:text-emerald-300 dark:hover:bg-emerald-400/10">{{ __('Edit') }}</a>
                                            <form method="POST" action="${deleteUrl}" data-nasabah-delete-form data-nasabah-id="${recordId}" class="inline-flex">
                                                <input type="hidden" name="_token" value="${escapeAttribute(csrfToken)}">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" data-nasabah-delete-button class="rounded-lg border border-red-200 px-2 py-1 text-xs font-semibold text-red-600 transition hover:bg-red-50 focus:outline-none dark:border-red-400/40 dark:text-red-300 dark:hover:bg-red-400/10">{{ __('Hapus') }}</button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 font-mono text-sm">${escapeHtml(item.kode_member)}</td>
                                    <td class="whitespace-nowrap px-4 py-3 font-mono text-sm">${escapeHtml(item.nik)}</td>
                                    <td class="px-4 py-3 font-medium text-neutral-800 dark:text-neutral-100">${escapeHtml(item.nama)}</td>
                                    <td class="px-4 py-3">${formatDate(item.tanggal_lahir)}</td>
                                    <td class="px-4 py-3">${escapeHtml(item.telepon)}</td>
                                    <td class="px-4 py-3">${escapeHtml(item.kota) || '-'}</td>
                                    <td class="px-4 py-3">${escapeHtml(item.kecamatan) || '-'}</td>
                                    <td class="px-4 py-3 font-medium text-neutral-800 dark:text-neutral-100">${escapeHtml(item.alamat)}</td>
                                    <td class="px-4 py-3">${escapeHtml(item.npwp) || '-'}</td>
                                    <td class="px-4 py-3">${escapeHtml(item.id_lain) || '-'}</td>
                                </tr>
                            `;
                        })
                        .join('');

                    tableBody.innerHTML = rows;
                    updateRowsPerPageDisplay();
                    renderPaginationControls({
                        totalRecords,
                        totalPages,
                        startIndex,
                        pageItemsCount: pageItems.length,
                    });
                }

                function updateSortIndicators() {
                    sortButtons.forEach((button) => {
                        const key = button.dataset.sortKey;
                        const icon = button.querySelector('[data-sort-icon]');

                        if (!icon) return;

                        if (state.sortKey === key) {
                            icon.classList.remove('hidden');
                            icon.classList.toggle('rotate-180', state.sortDirection === 'desc');
                        } else {
                            icon.classList.add('hidden');
                            icon.classList.remove('rotate-180');
                        }
                    });
                }

                function resetDataset() {
                    dataset = initialDataset.map((item) => ({ ...item }));
                    window.__nasabahDataset = dataset;
                    renderTable();
                }

                async function performSearch(term) {
                    if (searchState.abortController) {
                        searchState.abortController.abort();
                    }

                    const controller = new AbortController();
                    searchState.abortController = controller;

                    try {
                        const params = new URLSearchParams({ search: term });
                        const response = await fetch(`${nasabahSearchEndpoint}?${params.toString()}`, {
                            headers: {
                                Accept: 'application/json',
                            },
                            signal: controller.signal,
                        });

                        if (!response.ok) {
                            throw new Error(`Request failed with status ${response.status}`);
                        }

                        const payload = await response.json();

                        const activeTerm = (searchInput?.value ?? '').trim();
                        if (activeTerm !== term) {
                            return;
                        }

                        const records = Array.isArray(payload?.data) ? payload.data.map(toRecord) : [];
                        dataset = records;
                        window.__nasabahDataset = dataset;
                        renderTable();
                    } catch (error) {
                        if (error.name === 'AbortError') {
                            return;
                        }

                        console.error('Failed to fetch nasabah data', error);
                    } finally {
                        if (searchState.abortController === controller) {
                            searchState.abortController = null;
                        }
                    }
                }

                function handleSearchInput(value) {
                    const trimmed = String(value ?? '').trim();
                    state.searchTerm = trimmed;
                    state.currentPage = 1;

                    if (searchState.debounceId) {
                        window.clearTimeout(searchState.debounceId);
                    }

                    if (!trimmed) {
                        if (searchState.abortController) {
                            searchState.abortController.abort();
                            searchState.abortController = null;
                        }
                        resetDataset();
                        return;
                    }

                    searchState.debounceId = window.setTimeout(() => {
                        performSearch(trimmed);
                    }, 300);

                    renderTable();
                }

                searchInput?.addEventListener('input', (event) => {
                    handleSearchInput(event.target.value);
                });

                sortButtons.forEach((button) => {
                    const key = button.dataset.sortKey;
                    if (!key || key === 'actions') {
                        return;
                    }

                    button.addEventListener('click', () => {
                        if (state.sortKey === key) {
                            state.sortDirection = state.sortDirection === 'asc' ? 'desc' : 'asc';
                        } else {
                            state.sortKey = key;
                            state.sortDirection = 'asc';
                        }
                        state.currentPage = 1;
                        updateSortIndicators();
                        renderTable();
                    });
                });

                rowsPerPageSelect?.addEventListener('change', (event) => {
                    const nextSize = Number(event.target.value);
                    if (!Number.isNaN(nextSize)) {
                        state.pageSize = nextSize;
                        state.currentPage = 1;
                        renderTable();
                    }
                });

                if (paginationContainer && paginationContainer.dataset.listenerAttached !== 'true') {
                    paginationContainer.addEventListener('click', (event) => {
                        const control = event.target.closest('[data-page-control], [data-page-number]');

                        if (!control) {
                            return;
                        }

                        event.preventDefault();

                        if (control.dataset.pageNumber) {
                            const targetPage = Number(control.dataset.pageNumber);
                            if (!Number.isNaN(targetPage) && targetPage >= 1 && targetPage <= state.totalPages) {
                                state.currentPage = targetPage;
                                renderTable();
                            }
                            return;
                        }

                        const action = control.dataset.pageControl;

                        switch (action) {
                            case 'first':
                                if (state.currentPage !== 1) {
                                    state.currentPage = 1;
                                    renderTable();
                                }
                                break;
                            case 'prev':
                                if (state.currentPage > 1) {
                                    state.currentPage -= 1;
                                    renderTable();
                                }
                                break;
                            case 'next':
                                if (state.currentPage < state.totalPages) {
                                    state.currentPage += 1;
                                    renderTable();
                                }
                                break;
                            case 'last':
                                if (state.currentPage !== state.totalPages) {
                                    state.currentPage = state.totalPages || 1;
                                    renderTable();
                                }
                                break;
                            default:
                                break;
                        }
                    });

                    paginationContainer.dataset.listenerAttached = 'true';
                }

                updateSortIndicators();
                renderTable();
            }

            if (document.readyState !== 'loading') {
                initializeNasabahPage();
            } else {
                document.addEventListener('DOMContentLoaded', initializeNasabahPage, { once: true });
            }

            document.addEventListener('livewire:navigated', initializeNasabahPage);

            if (!window.__nasabahDeleteHandlerRegistered) {
                document.addEventListener('submit', (event) => {
                    const form = event.target.closest('[data-nasabah-delete-form]');
                    if (!form) {
                        return;
                    }

                    const recordId = form.getAttribute('data-nasabah-id');
                    const target = window.__nasabahDataset?.find?.((entry) => String(entry.id ?? '') === String(recordId ?? ''));
                    const name = target?.nama ?? '';
                    const sanitizedName = String(name ?? '').replace(/"/g, '\\"');
                    const prefix = `{{ __('Apakah Anda yakin ingin menghapus data nasabah') }}`;
                    const fallback = `{{ __('Apakah Anda yakin ingin menghapus data nasabah ini?') }}`;
                    const message = sanitizedName ? `${prefix} "${sanitizedName}"?` : fallback;

                    if (!window.confirm(message)) {
                        event.preventDefault();
                        return;
                    }

                    const submitButton = form.querySelector('[data-nasabah-delete-button]');
                    if (submitButton) {
                        submitButton.setAttribute('disabled', 'disabled');
                        submitButton.classList.add('opacity-60', 'cursor-not-allowed');
                    }
                });

                window.__nasabahDeleteHandlerRegistered = true;
            }
        })();
    </script>
</x-layouts.app>
