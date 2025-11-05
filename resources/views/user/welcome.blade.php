<x-layouts.app :title="__('Selamat Datang')">
    <div class="flex h-full w-full flex-1 flex-col items-center justify-center gap-4 rounded-xl border border-neutral-200 p-8 text-center dark:border-neutral-700">
        <h1 class="text-3xl font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Selamat datang') }} {{ auth()->user()?->username }}</h1>
        <p class="text-lg text-zinc-600 dark:text-zinc-400">
            {{ __('Anda berhasil masuk sebagai pengguna.') }}
        </p>
    </div>
</x-layouts.app>
