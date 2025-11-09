<x-layouts.app :title="__('Tambah Barang Gadai')">
    <div class="space-y-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Tambah Barang Gadai') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Lengkapi formulir berikut untuk menambahkan data barang jaminan baru beserta informasi penaksiran.') }}
            </p>
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <form
                method="POST"
                action="{{ route('gadai.barang-jaminan.store') }}"
                class="space-y-6 p-6"
                enctype="multipart/form-data"
            >
                @csrf

                @include('gadai.barang-jaminan.form-fields', [
                    'barangJaminan' => null,
                    'penaksirList' => $penaksirList,
                ])

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <a
                        href="{{ route('gadai.lihat-barang-gadai') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-400 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-700/60"
                    >
                        {{ __('Batal') }}
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
                    >
                        {{ __('Simpan Data') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
