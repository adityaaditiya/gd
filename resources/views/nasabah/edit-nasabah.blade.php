<x-layouts.app :title="__('Edit Nasabah')">
    <div class="space-y-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Edit Nasabah') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Perbarui informasi nasabah berikut dengan memastikan seluruh data valid dan terbaru.') }}
            </p>
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <form
                method="POST"
                action="{{ route('nasabah.update', $nasabah) }}"
                class="space-y-6 p-6"
            >
                @csrf
                @method('PUT')

                <x-nasabah.form-fields :nasabah="$nasabah" :submit-label="__('Perbarui')" />
            </form>
        </div>
    </div>
</x-layouts.app>
