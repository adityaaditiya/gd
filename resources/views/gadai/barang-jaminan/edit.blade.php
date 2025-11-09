<x-layouts.app :title="__('Edit Barang Jaminan')">
    <div class="space-y-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Edit Barang Jaminan') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Perbarui informasi barang jaminan tanpa mengubah relasi kontrak gadai yang sudah terdaftar.') }}
            </p>
        </div>

        @if (session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-500/60 dark:bg-red-500/10 dark:text-red-300">
                {{ session('error') }}
            </div>
        @endif

        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <form method="POST" action="{{ route('gadai.barang-jaminan.update', $barangJaminan) }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                @include('gadai.barang-jaminan._form', [
                    'barangJaminan' => $barangJaminan,
                    'penaksirList' => $penaksirList,
                    'submitLabel' => __('Perbarui Barang'),
                ])
            </form>
        </div>
    </div>
</x-layouts.app>
