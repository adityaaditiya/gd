<x-layouts.app :title="__('Data Nasabah')">
    <div class="space-y-8" id="nasabah-page">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Data Nasabah') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Kelola dan telusuri informasi lengkap nasabah melalui tabel interaktif berikut.') }}
            </p>
        </div>

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
                            placeholder="{{ __('Ketik untuk mencari seluruh kolom...') }}"
                            class="w-full border-0 bg-transparent p-0 text-sm text-neutral-900 placeholder:text-neutral-400 focus:outline-none focus:ring-0 dark:text-white"
                        />
                    </div>
                </label>

                <div class="flex justify-end">
                    <button
                        id="addNasabahButton"
                        type="button"
                        class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500"
                    >
                        <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>{{ __('+ Tambah Data Member') }}</span>
                    </button>
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
                            <th scope="col" class="min-w-[140px] px-4 py-3">
                                <button type="button" class="flex items-center gap-1" data-sort-key="nasabah_lama">
                                    <span>{{ __('Nasabah Lama') }}</span>
                                    <span data-sort-icon class="hidden">
                                        <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </span>
                                </button>
                            </th>
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
        </div>
    </div>

    <div id="nasabahModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-neutral-950/60 p-4 backdrop-blur-sm">
        <div class="relative w-full max-w-2xl rounded-xl border border-neutral-200 bg-white shadow-xl dark:border-neutral-700 dark:bg-neutral-900">
            <div class="flex items-start justify-between border-b border-neutral-200 p-4 dark:border-neutral-700">
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Tambah Data Nasabah') }}</h2>
                    <p class="text-sm text-neutral-500 dark:text-neutral-300">{{ __('Lengkapi formulir berikut untuk menambahkan member baru.') }}</p>
                </div>
                <button type="button" id="closeNasabahModal" class="rounded-full p-1 text-neutral-400 transition hover:text-neutral-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:hover:text-neutral-200">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="nasabahForm" class="space-y-4 overflow-y-auto p-4">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="formNik" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('NIK') }} <span class="text-red-500">*</span></label>
                        <input id="formNik" name="nik" type="text" class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40" required />
                        <p data-error-for="nik" class="mt-1 text-xs text-red-500 hidden"></p>
                    </div>
                    <div>
                        <label for="formNama" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Nama') }} <span class="text-red-500">*</span></label>
                        <input id="formNama" name="nama" type="text" class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40" required />
                        <p data-error-for="nama" class="mt-1 text-xs text-red-500 hidden"></p>
                    </div>
                    <div>
                        <label for="formTempatLahir" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Tempat Lahir') }} <span class="text-red-500">*</span></label>
                        <input id="formTempatLahir" name="tempat_lahir" type="text" class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40" required />
                        <p data-error-for="tempat_lahir" class="mt-1 text-xs text-red-500 hidden"></p>
                    </div>
                    <div>
                        <label for="formTanggalLahir" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Tanggal Lahir') }} <span class="text-red-500">*</span></label>
                        <input id="formTanggalLahir" name="tanggal_lahir" type="date" class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40" required />
                        <p data-error-for="tanggal_lahir" class="mt-1 text-xs text-red-500 hidden"></p>
                    </div>
                    <div>
                        <label for="formTelepon" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Telepon') }} <span class="text-red-500">*</span></label>
                        <input id="formTelepon" name="telepon" type="tel" inputmode="tel" class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40" required />
                        <p data-error-for="telepon" class="mt-1 text-xs text-red-500 hidden"></p>
                    </div>
                    <div>
                        <label for="formKota" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Kota') }}</label>
                        <input id="formKota" name="kota" type="text" class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40" />
                        <p data-error-for="kota" class="mt-1 text-xs text-red-500 hidden"></p>
                    </div>
                    <div>
                        <label for="formKelurahan" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Kelurahan') }}</label>
                        <input id="formKelurahan" name="kelurahan" type="text" class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40" />
                        <p data-error-for="kelurahan" class="mt-1 text-xs text-red-500 hidden"></p>
                    </div>
                    <div>
                        <label for="formKecamatan" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Kecamatan') }}</label>
                        <input id="formKecamatan" name="kecamatan" type="text" class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40" />
                        <p data-error-for="kecamatan" class="mt-1 text-xs text-red-500 hidden"></p>
                    </div>
                    <div>
                        <label for="formNpwp" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('No NPWP') }}</label>
                        <input id="formNpwp" name="npwp" type="text" class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40" />
                        <p data-error-for="npwp" class="mt-1 text-xs text-red-500 hidden"></p>
                    </div>
                    <div>
                        <label for="formIdLain" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('No Identitas Lain') }}</label>
                        <input id="formIdLain" name="id_lain" type="text" class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40" />
                        <p data-error-for="id_lain" class="mt-1 text-xs text-red-500 hidden"></p>
                    </div>
                </div>

                <div>
                    <label for="formAlamat" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Alamat') }} <span class="text-red-500">*</span></label>
                    <textarea id="formAlamat" name="alamat" rows="3" class="mt-2 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40" required></textarea>
                    <p data-error-for="alamat" class="mt-1 text-xs text-red-500 hidden"></p>
                </div>

                <div class="flex items-center gap-2">
                    <input id="formNasabahLama" name="nasabah_lama" type="checkbox" class="size-4 rounded border-neutral-300 text-emerald-600 focus:ring-emerald-500 dark:border-neutral-600 dark:bg-neutral-800 dark:focus:ring-emerald-400" />
                    <label for="formNasabahLama" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Nasabah Lama') }}</label>
                </div>

                <div id="kodeMemberGroup" class="hidden rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700 dark:border-emerald-500/60 dark:bg-emerald-500/10 dark:text-emerald-300">
                    <p class="font-medium">{{ __('Data berhasil disimpan!') }}</p>
                    <p>{{ __('Kode Member Anda:') }}</p>
                    <input id="kodeMember" type="text" readonly class="mt-2 w-full rounded-lg border border-emerald-300 bg-white px-3 py-2 text-sm font-semibold tracking-wide text-emerald-700 shadow-sm dark:border-emerald-500/60 dark:bg-neutral-900 dark:text-emerald-300" />
                    <p class="mt-1 text-xs text-emerald-600 dark:text-emerald-400">{{ __('Kode ini dibuat otomatis oleh sistem dan tidak dapat diubah.') }}</p>
                </div>

                <div class="flex items-center justify-end gap-2 border-t border-neutral-200 pt-4 dark:border-neutral-700">
                    <button type="button" id="cancelNasabahForm" class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-600 transition hover:bg-neutral-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800">{{ __('Batal') }}</button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500">{{ __('Simpan') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dataset = [
                {
                    nik: '3173021509860001',
                    nama: 'Siti Maemunah',
                    tempat_lahir: 'Jakarta',
                    tanggal_lahir: '1986-09-15',
                    telepon: '081234567890',
                    kota: 'Jakarta Selatan',
                    kelurahan: 'Pela Mampang',
                    kecamatan: 'Mampang Prapatan',
                    alamat: 'Jl. Kemang Raya No. 5, Pela Mampang, Jakarta Selatan',
                    npwp: '12.345.678.9-012.000',
                    nasabah_lama: true,
                    id_lain: 'SIM 1234567890',
                },
                {
                    nik: '3275010401920003',
                    nama: 'Budi Hartanto',
                    tempat_lahir: 'Bandung',
                    tanggal_lahir: '1992-01-04',
                    telepon: '082298776655',
                    kota: 'Bandung',
                    kelurahan: 'Burangrang',
                    kecamatan: 'Lengkong',
                    alamat: 'Jl. Gatot Subroto No. 12, Burangrang, Bandung',
                    npwp: '45.678.910.1-234.000',
                    nasabah_lama: false,
                    id_lain: '-',
                },
                {
                    nik: '3578012205830004',
                    nama: 'Rina Kusuma',
                    tempat_lahir: 'Surabaya',
                    tanggal_lahir: '1983-05-22',
                    telepon: '085612345678',
                    kota: 'Surabaya',
                    kelurahan: 'Darmo',
                    kecamatan: 'Wonokromo',
                    alamat: 'Jl. Darmo Permai I No. 8, Darmo, Surabaya',
                    npwp: '67.890.123.4-567.000',
                    nasabah_lama: true,
                    id_lain: 'Paspor C1234567',
                },
                {
                    nik: '3374061011800007',
                    nama: 'Eko Santoso',
                    tempat_lahir: 'Semarang',
                    tanggal_lahir: '1980-11-10',
                    telepon: '087788990011',
                    kota: 'Semarang',
                    kelurahan: 'Jatingaleh',
                    kecamatan: 'Candisari',
                    alamat: 'Jl. Setiabudi No. 45, Jatingaleh, Semarang',
                    npwp: '89.012.345.6-789.000',
                    nasabah_lama: false,
                    id_lain: 'SIM 9876543210',
                },
                {
                    nik: '3674012809750008',
                    nama: 'Lestari Widya',
                    tempat_lahir: 'Tangerang',
                    tanggal_lahir: '1975-09-28',
                    telepon: '081355779900',
                    kota: 'Tangerang',
                    kelurahan: 'Karang Tengah',
                    kecamatan: 'Ciledug',
                    alamat: 'Jl. Raden Saleh No. 3, Karang Tengah, Tangerang',
                    npwp: '01.234.567.8-910.000',
                    nasabah_lama: true,
                    id_lain: '-',
                },
            ];

            const state = {
                sortKey: 'nama',
                sortDirection: 'asc',
                searchTerm: '',
            };

            const tableBody = document.getElementById('nasabahTableBody');
            const searchInput = document.getElementById('nasabahSearch');
            const sortButtons = Array.from(document.querySelectorAll('[data-sort-key]'));
            const addButton = document.getElementById('addNasabahButton');
            const modal = document.getElementById('nasabahModal');
            const closeModalButton = document.getElementById('closeNasabahModal');
            const cancelButton = document.getElementById('cancelNasabahForm');
            const form = document.getElementById('nasabahForm');
            const kodeMemberGroup = document.getElementById('kodeMemberGroup');
            const kodeMemberInput = document.getElementById('kodeMember');

            const formFields = {
                nik: document.getElementById('formNik'),
                nama: document.getElementById('formNama'),
                tempat_lahir: document.getElementById('formTempatLahir'),
                tanggal_lahir: document.getElementById('formTanggalLahir'),
                telepon: document.getElementById('formTelepon'),
                kota: document.getElementById('formKota'),
                kelurahan: document.getElementById('formKelurahan'),
                kecamatan: document.getElementById('formKecamatan'),
                npwp: document.getElementById('formNpwp'),
                id_lain: document.getElementById('formIdLain'),
                alamat: document.getElementById('formAlamat'),
                nasabah_lama: document.getElementById('formNasabahLama'),
            };

            const errorElements = Object.fromEntries(
                Object.keys(formFields).map((field) => [field, form.querySelector(`[data-error-for="${field}"]`)]),
            );

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

                    if (sortKey === 'tanggal_lahir') {
                        const aTime = a.tanggal_lahir ? new Date(a.tanggal_lahir).getTime() : 0;
                        const bTime = b.tanggal_lahir ? new Date(b.tanggal_lahir).getTime() : 0;
                        return sortDirection === 'asc' ? aTime - bTime : bTime - aTime;
                    }

                    const aValue = normalize(a[sortKey] ?? '');
                    const bValue = normalize(b[sortKey] ?? '');

                    if (aValue < bValue) return sortDirection === 'asc' ? -1 : 1;
                    if (aValue > bValue) return sortDirection === 'asc' ? 1 : -1;
                    return 0;
                });

                if (!filtered.length) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="10" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-300">
                                ${'{{ __('Data tidak ditemukan untuk kata kunci yang dimasukkan.') }}'}
                            </td>
                        </tr>
                    `;
                    return;
                }

                const rows = filtered
                    .map((item) => {
                        return `
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/40">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <button type="button" class="rounded-lg border border-emerald-200 px-2 py-1 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50 focus:outline-none dark:border-emerald-400/40 dark:text-emerald-300 dark:hover:bg-emerald-400/10">{{ __('Edit') }}</button>
                                        <button type="button" class="rounded-lg border border-red-200 px-2 py-1 text-xs font-semibold text-red-600 transition hover:bg-red-50 focus:outline-none dark:border-red-400/40 dark:text-red-300 dark:hover:bg-red-400/10">{{ __('Hapus') }}</button>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 font-mono text-sm">${item.nik}</td>
                                <td class="px-4 py-3 font-medium text-neutral-800 dark:text-neutral-100">${item.nama}</td>
                                <td class="px-4 py-3">${formatDate(item.tanggal_lahir)}</td>
                                <td class="px-4 py-3">${item.telepon}</td>
                                <td class="px-4 py-3">${item.kota ?? '-'}</td>
                                <td class="px-4 py-3">${item.kecamatan ?? '-'}</td>
                                <td class="px-4 py-3">${item.npwp ?? '-'}</td>
                                <td class="px-4 py-3">
                                    <input type="checkbox" ${item.nasabah_lama ? 'checked' : ''} disabled class="size-4 rounded border-neutral-300 text-emerald-600 focus:ring-emerald-500 dark:border-neutral-600 dark:bg-neutral-700" aria-label="{{ __('Nasabah lama') }}" />
                                </td>
                                <td class="px-4 py-3">${item.id_lain ?? '-'}</td>
                            </tr>
                        `;
                    })
                    .join('');

                tableBody.innerHTML = rows;
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

            function clearErrors() {
                Object.values(errorElements).forEach((element) => {
                    if (!element) return;
                    element.textContent = '';
                    element.classList.add('hidden');
                });
            }

            function showError(field, message) {
                const element = errorElements[field];
                if (!element) return;
                element.textContent = message;
                element.classList.remove('hidden');
            }

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
                form.reset();
                clearErrors();
                kodeMemberGroup.classList.add('hidden');
                kodeMemberInput.value = '';
                formFields.nasabah_lama.checked = false;
                formFields.nik.focus();
            }

            function closeModal() {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            function generateMemberCode() {
                const timestamp = Date.now().toString(36).toUpperCase();
                return `MBR-${timestamp.slice(-6)}`;
            }

            function handleFormSubmit(event) {
                event.preventDefault();
                clearErrors();

                const values = {
                    nik: formFields.nik.value.trim(),
                    nama: formFields.nama.value.trim(),
                    tempat_lahir: formFields.tempat_lahir.value.trim(),
                    tanggal_lahir: formFields.tanggal_lahir.value,
                    telepon: formFields.telepon.value.trim(),
                    kota: formFields.kota.value.trim(),
                    kelurahan: formFields.kelurahan.value.trim(),
                    kecamatan: formFields.kecamatan.value.trim(),
                    npwp: formFields.npwp.value.trim(),
                    id_lain: formFields.id_lain.value.trim(),
                    alamat: formFields.alamat.value.trim(),
                    nasabah_lama: formFields.nasabah_lama.checked,
                };

                let hasError = false;

                if (!values.nik) {
                    showError('nik', '{{ __('NIK wajib diisi.') }}');
                    hasError = true;
                }
                if (!values.nama) {
                    showError('nama', '{{ __('Nama wajib diisi.') }}');
                    hasError = true;
                }
                if (!values.tempat_lahir) {
                    showError('tempat_lahir', '{{ __('Tempat lahir wajib diisi.') }}');
                    hasError = true;
                }
                if (!values.tanggal_lahir) {
                    showError('tanggal_lahir', '{{ __('Tanggal lahir wajib diisi.') }}');
                    hasError = true;
                }
                if (!values.telepon) {
                    showError('telepon', '{{ __('Nomor telepon wajib diisi.') }}');
                    hasError = true;
                }
                if (!values.alamat) {
                    showError('alamat', '{{ __('Alamat wajib diisi.') }}');
                    hasError = true;
                }

                if (hasError) {
                    return;
                }

                const kodeMember = generateMemberCode();

                dataset.push({
                    nik: values.nik,
                    nama: values.nama,
                    tempat_lahir: values.tempat_lahir,
                    tanggal_lahir: values.tanggal_lahir,
                    telepon: values.telepon,
                    kota: values.kota || '-',
                    kelurahan: values.kelurahan || '-',
                    kecamatan: values.kecamatan || '-',
                    alamat: values.alamat,
                    npwp: values.npwp || '-',
                    nasabah_lama: values.nasabah_lama,
                    id_lain: values.id_lain || '-',
                });

                kodeMemberInput.value = kodeMember;
                kodeMemberGroup.classList.remove('hidden');
                renderTable();
            }

            searchInput.addEventListener('input', (event) => {
                state.searchTerm = event.target.value;
                renderTable();
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
                    updateSortIndicators();
                    renderTable();
                });
            });

            addButton.addEventListener('click', openModal);
            closeModalButton.addEventListener('click', closeModal);
            cancelButton.addEventListener('click', () => {
                closeModal();
            });

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            form.addEventListener('submit', handleFormSubmit);

            updateSortIndicators();
            renderTable();
        });
    </script>
</x-layouts.app>
