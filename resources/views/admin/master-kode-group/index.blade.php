<x-layouts.app :title="__('Master Kode Group')">
    <div class="space-y-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Master Kode Group') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Kelola daftar kode group dan harga dasar yang digunakan saat menambah atau mengubah data barang.') }}
            </p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-700 dark:border-emerald-500/60 dark:bg-emerald-500/10 dark:text-emerald-300">
                <p class="font-semibold">{{ session('status') }}</p>
            </div>
        @endif

        <div class="space-y-6 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <div>
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Tambah Kode Group Baru') }}</h2>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Masukkan kode group dan harga agar tersedia pada formulir data barang.') }}
                </p>
            </div>

            <form method="POST" action="{{ route('admin.master-kode-group.store') }}" class="grid gap-4 md:grid-cols-3 md:items-end">
                @csrf
                @php
                    $isCreateContext = old('form_mode') === 'create';
                @endphp
                <input type="hidden" name="form_mode" value="create">

                <div class="space-y-2">
                    <label for="kode_group" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Kode Group') }}</label>
                    <input
                        type="text"
                        id="kode_group"
                        name="kode_group"
                        value="{{ $isCreateContext ? old('kode_group') : '' }}"
                        maxlength="191"
                        required
                        class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
                        placeholder="{{ __('Masukkan kode group') }}"
                    >
                    @if ($isCreateContext && $errors->has('kode_group'))
                        <p class="text-xs text-rose-600 dark:text-rose-400">{{ $errors->first('kode_group') }}</p>
                    @endif
                </div>

                <div class="space-y-2">
                    <label for="harga" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Harga (Rp)') }}</label>
                    <input
                        type="number"
                        id="harga"
                        name="harga"
                        value="{{ $isCreateContext ? old('harga') : '' }}"
                        step="0.01"
                        min="0"
                        required
                        class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
                        placeholder="{{ __('Masukkan harga') }}"
                    >
                    @if ($isCreateContext && $errors->has('harga'))
                        <p class="text-xs text-rose-600 dark:text-rose-400">{{ $errors->first('harga') }}</p>
                    @endif
                </div>

                <div class="flex items-center gap-3 md:justify-end">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
                    >
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>{{ __('Tambah Kode Group') }}</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="space-y-4 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Daftar Kode Group Tersimpan') }}</h2>
                    <span class="text-sm text-neutral-500 dark:text-neutral-400">
                        {{ trans_choice('{0}Tidak ada kode group|{1}1 kode group|[2,*]:count kode group', $masterKodeGroups->count(), ['count' => $masterKodeGroups->count()]) }}
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 text-left text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200">
                    <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-950 dark:text-neutral-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">#</th>
                            <th scope="col" class="px-4 py-3">{{ __('Kode Group') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Harga (Rp)') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Terakhir Diubah') }}</th>
                            <th scope="col" class="px-4 py-3 text-right">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-800 dark:bg-neutral-900">
                        @forelse ($masterKodeGroups as $masterKodeGroup)
                            @php
                                $isActiveRow = (int) old('master_kode_group_id') === $masterKodeGroup->id;
                            @endphp
                            <form method="POST" action="{{ route('admin.master-kode-group.update', $masterKodeGroup) }}" class="contents">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="master_kode_group_id" value="{{ $masterKodeGroup->id }}">
                                <tr class="transition hover:bg-neutral-50 dark:hover:bg-neutral-800/60">
                                    <td class="px-4 py-3 align-top text-neutral-500 dark:text-neutral-400">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 align-top">
                                        <div class="space-y-2">
                                            <label class="sr-only" for="kode_group-{{ $masterKodeGroup->id }}">{{ __('Kode Group') }}</label>
                                            <input
                                                type="text"
                                                id="kode_group-{{ $masterKodeGroup->id }}"
                                                name="kode_group"
                                                value="{{ $isActiveRow ? old('kode_group') : $masterKodeGroup->kode_group }}"
                                                maxlength="191"
                                                required
                                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
                                            >
                                            @if ($isActiveRow && $errors->has('kode_group'))
                                                <p class="text-xs text-rose-600 dark:text-rose-400">{{ $errors->first('kode_group') }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <div class="space-y-2">
                                            <label class="sr-only" for="harga-{{ $masterKodeGroup->id }}">{{ __('Harga') }}</label>
                                            <input
                                                type="number"
                                                id="harga-{{ $masterKodeGroup->id }}"
                                                name="harga"
                                                value="{{ $isActiveRow ? old('harga') : $masterKodeGroup->harga }}"
                                                step="0.01"
                                                min="0"
                                                required
                                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
                                            >
                                            @if ($isActiveRow && $errors->has('harga'))
                                                <p class="text-xs text-rose-600 dark:text-rose-400">{{ $errors->first('harga') }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top text-neutral-500 dark:text-neutral-400">{{ $masterKodeGroup->updated_at?->translatedFormat('d F Y H:i') ?? '—' }}</td>
                                    <td class="px-4 py-3 align-top">
                                        <div class="flex justify-end gap-2">
                                            <button
                                                type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg border border-emerald-600 bg-emerald-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
                                            >
                                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                </svg>
                                                <span>{{ __('Simpan') }}</span>
                                            </button>

                                            <button
                                                type="submit"
                                                form="delete-master-kode-group-{{ $masterKodeGroup->id }}"
                                                class="inline-flex items-center gap-1 rounded-lg border border-rose-600 bg-rose-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:border-rose-700 hover:bg-rose-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-500 dark:border-rose-500 dark:bg-rose-500 dark:hover:border-rose-400 dark:hover:bg-rose-400"
                                                onclick="return confirm('{{ __('Hapus kode group ini?') }}')"
                                            >
                                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 4.5 15 15m0-15-15 15" />
                                                </svg>
                                                <span>{{ __('Hapus') }}</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </form>
                            <form method="POST" action="{{ route('admin.master-kode-group.destroy', $masterKodeGroup) }}" id="delete-master-kode-group-{{ $masterKodeGroup->id }}" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                    {{ __('Belum ada data kode group yang tersimpan.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
