<x-layouts.app :title="__('Penerbitan Kontrak Gadai')">
    <div class="space-y-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Penerbitan Kontrak Gadai') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Ikuti tiga langkah berikut untuk menerbitkan kontrak gadai baru.') }}
            </p>
        </div>

        @include('transaksi-gadai.partials.three-step-form', [
            'barangSiapGadai' => $barangSiapGadai,
            'nasabahList' => $nasabahList,
        ])
    </div>
</x-layouts.app>
