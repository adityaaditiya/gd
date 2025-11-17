<x-layouts.app :title="__('Master Perhitungan Gadai')">
    <div class="mx-auto w-full max-w-6xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-neutral-500">{{ __('Master') }}</p>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ __('Master Perhitungan Gadai') }}</h1>
            </div>
        </div>

        @if (session('status'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900 dark:border-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-100">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[minmax(0,360px)_1fr]">
            <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Tambah Rumus Baru') }}</h2>
                <p class="mt-1 text-sm text-neutral-500">{{ __('Konfigurasikan parameter perhitungan pemberian kredit di bawah ini.') }}</p>

                <form method="POST" action="{{ route('admin.master-perhitungan-gadai.store') }}" class="mt-6 space-y-5">
                    @csrf

                    <div class="space-y-1">
                        <label for="type" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Type') }}</label>
                        <input
                            id="type"
                            name="type"
                            type="text"
                            value="{{ old('type') }}"
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                            required
                        >
                        @error('type')
                            <p class="text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="space-y-1">
                            <label for="range_awal" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Range Awal') }}</label>
                            <input
                                id="range_awal"
                                name="range_awal"
                                type="number"
                                step="0.01"
                                min="0"
                                value="{{ old('range_awal') }}"
                                class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                required
                            >
                            @error('range_awal')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1">
                            <label for="range_akhir" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Range Akhir') }}</label>
                            <input
                                id="range_akhir"
                                name="range_akhir"
                                type="number"
                                step="0.01"
                                min="0"
                                value="{{ old('range_akhir') }}"
                                class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                required
                            >
                            @error('range_akhir')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="space-y-1">
                            <label for="tarif_bunga_harian" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Tarif Bunga Harian (%)') }}</label>
                            <input
                                id="tarif_bunga_harian"
                                name="tarif_bunga_harian"
                                type="number"
                                step="0.0001"
                                min="0"
                                max="1"
                                value="{{ old('tarif_bunga_harian') }}"
                                class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                required
                            >
                            @error('tarif_bunga_harian')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-neutral-500">{{ __('Gunakan format desimal, contoh: 0.015 untuk 1.5% harian.') }}</p>
                        </div>
                        <div class="space-y-1">
                            <label for="tenor_hari" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Tenor (Hari)') }}</label>
                            <input
                                id="tenor_hari"
                                name="tenor_hari"
                                type="number"
                                min="1"
                                value="{{ old('tenor_hari') }}"
                                class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                required
                            >
                            @error('tenor_hari')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="space-y-1">
                            <label for="jatuh_tempo_awal" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Jatuh Tempo Awal (Hari)') }}</label>
                            <input
                                id="jatuh_tempo_awal"
                                name="jatuh_tempo_awal"
                                type="number"
                                min="1"
                                value="{{ old('jatuh_tempo_awal') }}"
                                class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                required
                            >
                            @error('jatuh_tempo_awal')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1">
                            <label for="biaya_admin" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Biaya Admin') }}</label>
                            <input
                                id="biaya_admin"
                                name="biaya_admin"
                                type="number"
                                step="0.01"
                                min="0"
                                value="{{ old('biaya_admin') }}"
                                class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                required
                            >
                            @error('biaya_admin')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-900">
                            {{ __('Simpan Rumus') }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Daftar Rumus') }}</h2>
                    <span class="text-sm text-neutral-500">{{ $perhitunganList->count() }} {{ \Illuminate\Support\Str::plural(__('Rumus'), $perhitunganList->count()) }}</span>
                </div>

                @forelse ($perhitunganList as $perhitungan)
                    @php
                        $isEditing = (string) old('perhitungan_id') === (string) $perhitungan->id;
                        $value = fn($field) => $isEditing ? old($field) : $perhitungan->{$field};
                    @endphp
                    <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                        <div class="border-b border-neutral-200 px-4 py-3 dark:border-neutral-700">
                            <h3 class="text-base font-semibold text-neutral-900 dark:text-white">{{ $perhitungan->type }}</h3>
                            <p class="text-sm text-neutral-500">{{ __('Range') }}: Rp {{ number_format($perhitungan->range_awal, 0, ',', '.') }} - Rp {{ number_format($perhitungan->range_akhir, 0, ',', '.') }}</p>
                        </div>

                        <form method="POST" action="{{ route('admin.master-perhitungan-gadai.update', $perhitungan) }}" class="space-y-4 px-4 py-4">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="perhitungan_id" value="{{ $perhitungan->id }}">

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Type') }}</label>
                                    <input
                                        name="type"
                                        type="text"
                                        value="{{ $value('type') }}"
                                        class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                        required
                                    >
                                    @error('type', 'updateMasterPerhitungan_' . $perhitungan->id)
                                        <p class="text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Tarif Bunga Harian') }}</label>
                                    <input
                                        name="tarif_bunga_harian"
                                        type="number"
                                        step="0.0001"
                                        min="0"
                                        max="1"
                                        value="{{ $value('tarif_bunga_harian') }}"
                                        class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                        required
                                    >
                                    @error('tarif_bunga_harian', 'updateMasterPerhitungan_' . $perhitungan->id)
                                        <p class="text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Range Awal') }}</label>
                                    <input
                                        name="range_awal"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        value="{{ $value('range_awal') }}"
                                        class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                        required
                                    >
                                    @error('range_awal', 'updateMasterPerhitungan_' . $perhitungan->id)
                                        <p class="text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Range Akhir') }}</label>
                                    <input
                                        name="range_akhir"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        value="{{ $value('range_akhir') }}"
                                        class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                        required
                                    >
                                    @error('range_akhir', 'updateMasterPerhitungan_' . $perhitungan->id)
                                        <p class="text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Tenor (Hari)') }}</label>
                                    <input
                                        name="tenor_hari"
                                        type="number"
                                        min="1"
                                        value="{{ $value('tenor_hari') }}"
                                        class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                        required
                                    >
                                    @error('tenor_hari', 'updateMasterPerhitungan_' . $perhitungan->id)
                                        <p class="text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Jatuh Tempo Awal (Hari)') }}</label>
                                    <input
                                        name="jatuh_tempo_awal"
                                        type="number"
                                        min="1"
                                        value="{{ $value('jatuh_tempo_awal') }}"
                                        class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                        required
                                    >
                                    @error('jatuh_tempo_awal', 'updateMasterPerhitungan_' . $perhitungan->id)
                                        <p class="text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Biaya Admin') }}</label>
                                <input
                                    name="biaya_admin"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    value="{{ $value('biaya_admin') }}"
                                    class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                    required
                                >
                                @error('biaya_admin', 'updateMasterPerhitungan_' . $perhitungan->id)
                                    <p class="text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex flex-col gap-3 border-t border-neutral-200 pt-4 dark:border-neutral-700 sm:flex-row sm:items-center sm:justify-between">
                                <div class="text-sm text-neutral-500">
                                    {{ __('Terakhir diperbarui:') }} {{ $perhitungan->updated_at?->translatedFormat('d F Y H:i') }}
                                </div>
                                <div class="flex flex-wrap items-center gap-3">
                                    <button type="submit" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-900">
                                        {{ __('Simpan Perubahan') }}
                                    </button>
                                    <button
                                        type="submit"
                                        form="delete-master-perhitungan-{{ $perhitungan->id }}"
                                        onclick="return confirm('{{ __('Hapus rumus ini?') }}');"
                                        class="inline-flex items-center rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-900"
                                    >
                                        {{ __('Hapus') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                        <form
                            id="delete-master-perhitungan-{{ $perhitungan->id }}"
                            method="POST"
                            action="{{ route('admin.master-perhitungan-gadai.destroy', $perhitungan) }}"
                            class="hidden"
                        >
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-neutral-300 p-6 text-center text-sm text-neutral-500 dark:border-neutral-700 dark:text-neutral-300">
                        {{ __('Belum ada data perhitungan gadai. Tambahkan rumus baru untuk memulai.') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.app>
