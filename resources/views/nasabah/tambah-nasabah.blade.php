<x-layouts.app :title="__('Tambah Nasabah')">
    <div class="space-y-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Tambah Nasabah') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Isi formulir berikut untuk mendaftarkan member baru dan mendapatkan kode member otomatis setelah data tersimpan.') }}
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
            <form
                method="POST"
                action="{{ route('nasabah.data-nasabah.store') }}"
                class="space-y-6 p-6"
                data-nasabah-form
            >
                @csrf

                <x-nasabah.form-fields :redirect-route="'nasabah.tambah-nasabah'" />
            </form>
        </div>
    </div>
</x-layouts.app>
