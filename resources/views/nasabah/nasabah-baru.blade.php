@php
    $pageTitle = $pageTitle ?? __('Nasabah Baru');
@endphp

<x-layouts.app :title="$pageTitle">
    <div class="space-y-6">
        <div class="space-y-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ $pageTitle }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Halaman ini belum memiliki konten. Silakan kembali lagi nanti untuk informasi Nasabah Baru.') }}
            </p>
        </div>
    </div>
</x-layouts.app>
