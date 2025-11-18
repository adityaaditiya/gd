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

        @php
            $createFormHasErrors = $errors->getBag('default')->isNotEmpty();
        @endphp

        <div
            x-data="{ showCreateForm: {{ $createFormHasErrors ? 'true' : 'false' }} }"
            class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900"
        >
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Tambah Rumus Baru') }}</h2>
                    <p class="mt-1 text-sm text-neutral-500">{{ __('Konfigurasikan parameter perhitungan pemberian kredit di bawah ini.') }}</p>
                </div>
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-900"
                    x-on:click="showCreateForm = !showCreateForm"
                >
                    <span x-show="!showCreateForm">{{ __('Tambah Data') }}</span>
                    <span x-show="showCreateForm">{{ __('Tutup Form') }}</span>
                </button>
            </div>

            <div x-show="showCreateForm" x-cloak class="mt-6 border-t border-neutral-200 pt-6 dark:border-neutral-700">
                <form method="POST" action="{{ route('admin.master-perhitungan-gadai.store') }}" class="space-y-5">
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
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <div class="flex items-center justify-between border-b border-neutral-200 px-6 py-4 dark:border-neutral-700">
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Data Master Perhitungan Gadai') }}</h2>
                    <p class="text-sm text-neutral-500">{{ __('Kelola seluruh range dan tarif dari satu tempat.') }}</p>
                </div>
                <span class="text-sm font-medium text-neutral-500">{{ $perhitunganList->count() }} {{ \Illuminate\Support\Str::plural(__('Data'), $perhitunganList->count()) }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                    <thead class="bg-neutral-50 dark:bg-neutral-800/50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Type') }}</th>
                            <th scope="col" class="px-4 py-3 text-left font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Range Awal') }}</th>
                            <th scope="col" class="px-4 py-3 text-left font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Range Akhir') }}</th>
                            <th scope="col" class="px-4 py-3 text-left font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Tarif Bunga Harian') }}</th>
                            <th scope="col" class="px-4 py-3 text-left font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Tenor (Hari)') }}</th>
                            <th scope="col" class="px-4 py-3 text-left font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Jatuh Tempo Awal (Hari)') }}</th>
                            <th scope="col" class="px-4 py-3 text-left font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Biaya Admin') }}</th>
                            <th scope="col" class="px-4 py-3 text-left font-semibold text-neutral-700 dark:text-neutral-200">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">
                        @forelse ($perhitunganList as $perhitungan)
                            @php
                                $isEditing = (string) old('perhitungan_id') === (string) $perhitungan->id;
                                $value = fn($field) => $isEditing ? old($field) : $perhitungan->{$field};
                            @endphp
                            <tr class="bg-white text-neutral-900 dark:bg-neutral-900 dark:text-white">
                                <td class="px-4 py-4 font-semibold">{{ $perhitungan->type }}</td>
                                <td class="px-4 py-4">Rp {{ number_format($perhitungan->range_awal, 0, ',', '.') }}</td>
                                <td class="px-4 py-4">Rp {{ number_format($perhitungan->range_akhir, 0, ',', '.') }}</td>
                                <td class="px-4 py-4">{{ rtrim(rtrim(number_format($perhitungan->tarif_bunga_harian * 100, 4, '.', ''), '0'), '.') }}%</td>
                                <td class="px-4 py-4">{{ $perhitungan->tenor_hari }} {{ __('hari') }}</td>
                                <td class="px-4 py-4">{{ $perhitungan->jatuh_tempo_awal }} {{ __('hari') }}</td>
                                <td class="px-4 py-4">Rp {{ number_format($perhitungan->biaya_admin, 0, ',', '.') }}</td>
                                <td class="px-4 py-4">
                                    <div x-data="{ isEditing: {{ $isEditing ? 'true' : 'false' }} }" class="space-y-4">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-lg border border-indigo-200 px-3 py-1.5 text-xs font-semibold text-indigo-700 transition hover:bg-indigo-50 dark:border-indigo-500/40 dark:text-indigo-200"
                                                x-on:click="isEditing = !isEditing"
                                            >
                                                {{ __('Ubah') }}
                                            </button>
                                            <form
                                                method="POST"
                                                action="{{ route('admin.master-perhitungan-gadai.destroy', $perhitungan) }}"
                                                onsubmit="return confirm('{{ __('Hapus rumus ini?') }}');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50 dark:border-rose-500/40 dark:text-rose-200"
                                                >
                                                    {{ __('Hapus') }}
                                                </button>
                                            </form>
                                        </div>

                                        <div x-show="isEditing" x-cloak class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 text-left dark:border-neutral-700 dark:bg-neutral-800">
                                            <form method="POST" action="{{ route('admin.master-perhitungan-gadai.update', $perhitungan) }}" class="space-y-4">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="perhitungan_id" value="{{ $perhitungan->id }}">

                                                <div class="grid gap-4 sm:grid-cols-2">
                                                    <div class="space-y-1">
                                                        <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200">{{ __('Type') }}</label>
                                                        <input
                                                            name="type"
                                                            type="text"
                                                            value="{{ $value('type') }}"
                                                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-900"
                                                            required
                                                        >
                                                        @error('type', 'updateMasterPerhitungan_' . $perhitungan->id)
                                                            <p class="text-xs text-rose-500">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="space-y-1">
                                                        <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200">{{ __('Tarif Bunga Harian') }}</label>
                                                        <input
                                                            name="tarif_bunga_harian"
                                                            type="number"
                                                            step="0.0001"
                                                            min="0"
                                                            max="1"
                                                            value="{{ $value('tarif_bunga_harian') }}"
                                                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-900"
                                                            required
                                                        >
                                                        @error('tarif_bunga_harian', 'updateMasterPerhitungan_' . $perhitungan->id)
                                                            <p class="text-xs text-rose-500">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="grid gap-4 sm:grid-cols-2">
                                                    <div class="space-y-1">
                                                        <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200">{{ __('Range Awal') }}</label>
                                                        <input
                                                            name="range_awal"
                                                            type="number"
                                                            step="0.01"
                                                            min="0"
                                                            value="{{ $value('range_awal') }}"
                                                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-900"
                                                            required
                                                        >
                                                        @error('range_awal', 'updateMasterPerhitungan_' . $perhitungan->id)
                                                            <p class="text-xs text-rose-500">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="space-y-1">
                                                        <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200">{{ __('Range Akhir') }}</label>
                                                        <input
                                                            name="range_akhir"
                                                            type="number"
                                                            step="0.01"
                                                            min="0"
                                                            value="{{ $value('range_akhir') }}"
                                                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-900"
                                                            required
                                                        >
                                                        @error('range_akhir', 'updateMasterPerhitungan_' . $perhitungan->id)
                                                            <p class="text-xs text-rose-500">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="grid gap-4 sm:grid-cols-2">
                                                    <div class="space-y-1">
                                                        <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200">{{ __('Tenor (Hari)') }}</label>
                                                        <input
                                                            name="tenor_hari"
                                                            type="number"
                                                            min="1"
                                                            value="{{ $value('tenor_hari') }}"
                                                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-900"
                                                            required
                                                        >
                                                        @error('tenor_hari', 'updateMasterPerhitungan_' . $perhitungan->id)
                                                            <p class="text-xs text-rose-500">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="space-y-1">
                                                        <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200">{{ __('Jatuh Tempo Awal (Hari)') }}</label>
                                                        <input
                                                            name="jatuh_tempo_awal"
                                                            type="number"
                                                            min="1"
                                                            value="{{ $value('jatuh_tempo_awal') }}"
                                                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-900"
                                                            required
                                                        >
                                                        @error('jatuh_tempo_awal', 'updateMasterPerhitungan_' . $perhitungan->id)
                                                            <p class="text-xs text-rose-500">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="space-y-1">
                                                    <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200">{{ __('Biaya Admin') }}</label>
                                                    <input
                                                        name="biaya_admin"
                                                        type="number"
                                                        step="0.01"
                                                        min="0"
                                                        value="{{ $value('biaya_admin') }}"
                                                        class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-900"
                                                        required
                                                    >
                                                    @error('biaya_admin', 'updateMasterPerhitungan_' . $perhitungan->id)
                                                        <p class="text-xs text-rose-500">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="flex flex-col gap-2 border-t border-neutral-200 pt-4 text-xs text-neutral-500 dark:border-neutral-700 dark:text-neutral-300 sm:flex-row sm:items-center sm:justify-between">
                                                    <span>{{ __('Terakhir diperbarui:') }} {{ $perhitungan->updated_at?->translatedFormat('d F Y H:i') }}</span>
                                                    <div class="flex flex-wrap gap-2">
                                                        <button
                                                            type="button"
                                                            class="rounded-lg border border-neutral-300 px-3 py-1.5 font-semibold text-neutral-700 transition hover:bg-white dark:border-neutral-500 dark:text-neutral-100"
                                                            x-on:click="isEditing = false"
                                                        >
                                                            {{ __('Batal') }}
                                                        </button>
                                                        <button
                                                            type="submit"
                                                            class="rounded-lg bg-indigo-600 px-3 py-1.5 font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                                                        >
                                                            {{ __('Simpan Perubahan') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-300">
                                    {{ __('Belum ada data perhitungan gadai. Tambahkan rumus baru untuk memulai.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
