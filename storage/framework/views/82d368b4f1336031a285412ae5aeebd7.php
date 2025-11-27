<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Master Perhitungan Gadai')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Master Perhitungan Gadai'))]); ?>
    <div class="mx-auto w-full max-w-6xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <!-- <p class="text-sm font-semibold uppercase tracking-wide text-neutral-500"><?php echo e(__('Master')); ?></p> -->
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white"><?php echo e(__('Master Perhitungan Gadai')); ?></h1>
            </div>
        </div>

        <?php if(session('status')): ?>
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900 dark:border-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-100">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

        <?php
            $createFormHasErrors = $errors->getBag('default')->isNotEmpty();
        ?>

        <div
            x-data="{ showCreateForm: <?php echo e($createFormHasErrors ? 'true' : 'false'); ?> }"
            class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900"
        >
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Parameter Perhitungan Gadai')); ?></h2>
                    <p class="mt-1 text-sm text-neutral-500"><?php echo e(__('Konfigurasikan parameter perhitungan pemberian kredit di bawah ini.')); ?></p>
                </div>
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-900"
                    x-on:click="showCreateForm = !showCreateForm"
                >
                    <span x-show="!showCreateForm"><?php echo e(__('Tambah Data')); ?></span>
                    <span x-show="showCreateForm"><?php echo e(__('Tutup Form')); ?></span>
                </button>
            </div>

            <div x-show="showCreateForm" x-cloak class="mt-6 border-t border-neutral-200 pt-6 dark:border-neutral-700">
                <form
                    method="POST"
                    action="<?php echo e(route('admin.master-perhitungan-gadai.store')); ?>"
                    class="space-y-5"
                    x-data="{ skemaBunga: '<?php echo e(old('skema_bunga', 'harian')); ?>' }"
                >
                    <?php echo csrf_field(); ?>

                    <div class="space-y-1">
                        <label for="type" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Type')); ?></label>
                        <input
                            id="type"
                            name="type"
                            type="text"
                            value="<?php echo e(old('type')); ?>"
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                            required
                        >
                        <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-sm text-rose-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="space-y-1">
                        <label for="skema_bunga" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Skema Bunga')); ?></label>
                        <select
                            id="skema_bunga"
                            name="skema_bunga"
                            x-model="skemaBunga"
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                            required
                        >
                            <option value="harian"><?php echo e(__('Bunga Harian')); ?></option>
                            <option value="periodik"><?php echo e(__('Bunga Periodik')); ?></option>
                        </select>
                        <?php $__errorArgs = ['skema_bunga'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-sm text-rose-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="space-y-1">
                            <label for="range_awal" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Range Awal')); ?></label>
                            <input
                                id="range_awal"
                                name="range_awal"
                                type="number"
                                step="0.01"
                                min="0"
                                value="<?php echo e(old('range_awal')); ?>"
                                class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                required
                            >
                            <?php $__errorArgs = ['range_awal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-sm text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="space-y-1">
                            <label for="range_akhir" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Range Akhir')); ?></label>
                            <input
                                id="range_akhir"
                                name="range_akhir"
                                type="number"
                                step="0.01"
                                min="0"
                                value="<?php echo e(old('range_akhir')); ?>"
                                class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                required
                            >
                            <?php $__errorArgs = ['range_akhir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-sm text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="space-y-1">
                            <label for="tarif_bunga_harian" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Tarif Bunga Harian (%)')); ?></label>
                            <input
                                id="tarif_bunga_harian"
                                name="tarif_bunga_harian"
                                type="number"
                                step="0.00001"
                                min="0"
                                max="1"
                                value="<?php echo e(old('tarif_bunga_harian')); ?>"
                                x-bind:required="skemaBunga === 'harian'"
                                x-bind:disabled="skemaBunga === 'periodik'"
                                class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                            >
                            <?php $__errorArgs = ['tarif_bunga_harian'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-sm text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <!-- <p class="text-xs text-neutral-500"><?php echo e(__('Kolom input hanya aktif jika pilih skema Bunga Harian.')); ?></p> -->
                            <p class="text-xs text-neutral-500"><?php echo e(__('Panduan: Gunakan format desimal, contoh: 0.015 untuk 1.5% harian. untuk bunga total 2,5% dalam 30 hari gunakan ±0.00083 per hari.')); ?></p>
                        </div>
                        <div class="space-y-1" x-show="skemaBunga === 'periodik'" x-cloak>
                            <label for="tarif_bunga_per_periode" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Tarif Bunga per Periode (%)')); ?></label>
                            <input
                                id="tarif_bunga_per_periode"
                                name="tarif_bunga_per_periode"
                                type="number"
                                step="0.00001"
                                min="0"
                                max="1"
                                value="<?php echo e(old('tarif_bunga_per_periode')); ?>"
                                x-bind:required="skemaBunga === 'periodik'"
                                class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                            >
                            <?php $__errorArgs = ['tarif_bunga_per_periode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-sm text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="text-xs text-neutral-500"><?php echo e(__('Gunakan format desimal, contoh: 0.045 untuk 4.5% per periode (misal 15 hari).')); ?></p>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        
                        <div class="space-y-1" x-show="skemaBunga === 'periodik'" x-cloak>
                            <label for="periode_hari" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Periode (Hari)')); ?></label>
                            <input
                                id="periode_hari"
                                name="periode_hari"
                                type="number"
                                min="1"
                                value="<?php echo e(old('periode_hari')); ?>"
                                x-bind:required="skemaBunga === 'periodik'"
                                class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                            >
                            <?php $__errorArgs = ['periode_hari'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-sm text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="text-xs text-neutral-500"><?php echo e(__('Durasi satu periode bunga, contoh 15 hari untuk skema KCA 15 hari.')); ?></p>
                        </div>

                        <div class="space-y-1">
                            <label for="tenor_hari" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Tenor (Hari)')); ?></label>
                            <input
                                id="tenor_hari"
                                name="tenor_hari"
                                type="number"
                                min="1"
                                value="<?php echo e(old('tenor_hari')); ?>"
                                class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                required
                            >
                            <?php $__errorArgs = ['tenor_hari'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-sm text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="space-y-1">
                            <label for="jatuh_tempo_awal" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Jatuh Tempo Awal (Hari)')); ?></label>
                            <input
                                id="jatuh_tempo_awal"
                                name="jatuh_tempo_awal"
                                type="number"
                                min="1"
                                value="<?php echo e(old('jatuh_tempo_awal')); ?>"
                                class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                required
                            >
                            <?php $__errorArgs = ['jatuh_tempo_awal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-sm text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="space-y-1">
                            <label for="biaya_admin" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Biaya Admin')); ?></label>
                            <input
                                id="biaya_admin"
                                name="biaya_admin"
                                type="number"
                                step="0.01"
                                min="0"
                                value="<?php echo e(old('biaya_admin')); ?>"
                                class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                required
                            >
                            <?php $__errorArgs = ['biaya_admin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-sm text-rose-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-900">
                            <?php echo e(__('Simpan Rumus')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <!-- <div class="flex items-center justify-between border-b border-neutral-200 px-6 py-4 dark:border-neutral-700">
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Data Master Perhitungan Gadai')); ?></h2>
                    <p class="text-sm text-neutral-500"><?php echo e(__('Kelola seluruh range dan tarif dari satu tempat.')); ?></p>
                </div>
                <span class="text-sm font-medium text-neutral-500"><?php echo e($perhitunganList->count()); ?> <?php echo e(\Illuminate\Support\Str::plural(__('Data'), $perhitunganList->count())); ?></span>
            </div> -->

            <div class="overflow-x-auto">
                <table
                    class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700"
                    data-master-table
                >
                    <thead class="bg-neutral-50 dark:bg-neutral-700">
                        <tr>
                            <?php
                                $sortableColumns = [
                                    ['key' => 'type', 'label' => __('Type')],
                                    ['key' => 'range_awal', 'label' => __('Range Awal')],
                                    ['key' => 'range_akhir', 'label' => __('Range Akhir')],
                                    ['key' => 'skema_bunga', 'label' => __('Skema Bunga')],
                                    ['key' => 'tarif_bunga_harian', 'label' => __('Tarif Bunga Harian')],
                                    ['key' => 'tarif_bunga_per_periode', 'label' => __('Tarif Bunga per Periode')],
                                    ['key' => 'periode_hari', 'label' => __('Periode (Hari)')],
                                    ['key' => 'tenor_hari', 'label' => __('Tenor (Hari)')],
                                    ['key' => 'jatuh_tempo_awal', 'label' => __('Jatuh Tempo Awal (Hari)')],
                                    ['key' => 'biaya_admin', 'label' => __('Biaya Admin')],
                                ];
                            ?>

                            <?php $__currentLoopData = $sortableColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th scope="col" class="px-4 py-3 text-left font-semibold text-neutral-700 dark:text-neutral-200">
                                    <button
                                        type="button"
                                        class="flex items-center gap-1"
                                        data-sort-key="<?php echo e($column['key']); ?>"
                                    >
                                        <span><?php echo e($column['label']); ?></span>
                                        <span data-sort-icon class="hidden text-xs text-neutral-400 dark:text-neutral-500">
                                            <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                            </svg>
                                        </span>
                                    </button>
                                </th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <th scope="col" class="px-4 py-3 text-justify font-semibold text-neutral-700 dark:text-neutral-200">
                                <span><?php echo e(__('Aksi')); ?></span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800" data-master-rows>
                        <?php $__empty_1 = true; $__currentLoopData = $perhitunganList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perhitungan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $isEditing = (string) old('perhitungan_id') === (string) $perhitungan->id;
                                $value = fn($field) => $isEditing ? old($field) : $perhitungan->{$field};
                            ?>
                            <tr
                                class="bg-white text-neutral-900 dark:bg-neutral-900 dark:text-white"
                                data-master-row
                                data-type="<?php echo e($perhitungan->type); ?>"
                                data-range-awal="<?php echo e($perhitungan->range_awal); ?>"
                                data-range-akhir="<?php echo e($perhitungan->range_akhir); ?>"
                                data-tarif-bunga-harian="<?php echo e($perhitungan->tarif_bunga_harian); ?>"
                                data-skema-bunga="<?php echo e($perhitungan->skema_bunga); ?>"
                                data-tarif-bunga-per-periode="<?php echo e($perhitungan->tarif_bunga_per_periode); ?>"
                                data-periode-hari="<?php echo e($perhitungan->periode_hari); ?>"
                                data-tenor-hari="<?php echo e($perhitungan->tenor_hari); ?>"
                                data-jatuh-tempo-awal="<?php echo e($perhitungan->jatuh_tempo_awal); ?>"
                                data-biaya-admin="<?php echo e($perhitungan->biaya_admin); ?>"
                            >
                                <td class="px-4 py-4 font-semibold"><?php echo e($perhitungan->type); ?></td>
                                <td class="px-4 py-4">Rp <?php echo e(number_format($perhitungan->range_awal, 0, ',', '.')); ?></td>
                                <td class="px-4 py-4">Rp <?php echo e(number_format($perhitungan->range_akhir, 0, ',', '.')); ?></td>
                                <td class="px-4 py-4 capitalize"><?php echo e($perhitungan->skema_bunga ?? 'harian'); ?></td>
                                <td class="px-4 py-4">
                                    <?php echo e($perhitungan->tarif_bunga_harian !== null ? rtrim(rtrim(number_format($perhitungan->tarif_bunga_harian * 100, 4, '.', ''), '0'), '.') . '%' : '—'); ?>

                                </td>
                                <td class="px-4 py-4">
                                    <?php echo e($perhitungan->tarif_bunga_per_periode !== null ? rtrim(rtrim(number_format($perhitungan->tarif_bunga_per_periode * 100, 4, '.', ''), '0'), '.') . '%' : '—'); ?>

                                </td>
                                <td class="px-4 py-4"><?php echo e($perhitungan->periode_hari ?? '—'); ?></td>
                                <td class="px-4 py-4"><?php echo e($perhitungan->tenor_hari); ?> <?php echo e(__('hari')); ?></td>
                                <td class="px-4 py-4"><?php echo e($perhitungan->jatuh_tempo_awal); ?> <?php echo e(__('hari')); ?></td>
                                <td class="px-4 py-4">Rp <?php echo e(number_format($perhitungan->biaya_admin, 0, ',', '.')); ?></td>
                                <td class="px-4 py-4">
                                    <div x-data="{ isEditing: <?php echo e($isEditing ? 'true' : 'false'); ?>, skemaBunga: '<?php echo e($value('skema_bunga') ?? 'harian'); ?>' }" class="space-y-4">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-lg border border-indigo-200 px-3 py-1.5 text-xs font-semibold text-indigo-700 transition hover:bg-indigo-50 dark:border-indigo-500/40 dark:text-indigo-200"
                                                x-on:click="isEditing = !isEditing"
                                            >
                                                <?php echo e(__('Ubah')); ?>

                                            </button>
                                            <form
                                                method="POST"
                                                action="<?php echo e(route('admin.master-perhitungan-gadai.destroy', $perhitungan)); ?>"
                                                onsubmit="return confirm('<?php echo e(__('Hapus rumus ini?')); ?>');"
                                            >
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50 dark:border-rose-500/40 dark:text-rose-200"
                                                >
                                                    <?php echo e(__('Hapus')); ?>

                                                </button>
                                            </form>
                                        </div>

                                        <div x-show="isEditing" x-cloak class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 text-left dark:border-neutral-700 dark:bg-neutral-800">
                                            <form method="POST" action="<?php echo e(route('admin.master-perhitungan-gadai.update', $perhitungan)); ?>" class="space-y-4">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <input type="hidden" name="perhitungan_id" value="<?php echo e($perhitungan->id); ?>">

                                                <div class="grid gap-4 sm:grid-cols-2">
                                                    <div class="space-y-1">
                                                        <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200"><?php echo e(__('Type')); ?></label>
                                                        <input
                                                            name="type"
                                                            type="text"
                                                            value="<?php echo e($value('type')); ?>"
                                                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-900"
                                                            required
                                                        >
                                                        <?php $__errorArgs = ['type', 'updateMasterPerhitungan_' . $perhitungan->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <p class="text-xs text-rose-500"><?php echo e($message); ?></p>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                    <div class="space-y-1">
                                                        <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200"><?php echo e(__('Skema Bunga')); ?></label>
                                                        <select
                                                            name="skema_bunga"
                                                            x-model="skemaBunga"
                                                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-900"
                                                            required
                                                        >
                                                            <option value="harian"><?php echo e(__('Bunga Harian')); ?></option>
                                                            <option value="periodik"><?php echo e(__('Bunga Periodik')); ?></option>
                                                        </select>
                                                        <?php $__errorArgs = ['skema_bunga', 'updateMasterPerhitungan_' . $perhitungan->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <p class="text-xs text-rose-500"><?php echo e($message); ?></p>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                </div>

                                                <div class="grid gap-4 sm:grid-cols-2">
                                                    <div class="space-y-1">
                                                        <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200"><?php echo e(__('Range Awal')); ?></label>
                                                        <input
                                                            name="range_awal"
                                                            type="number"
                                                            step="0.01"
                                                            min="0"
                                                            value="<?php echo e($value('range_awal')); ?>"
                                                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-900"
                                                            required
                                                        >
                                                        <?php $__errorArgs = ['range_awal', 'updateMasterPerhitungan_' . $perhitungan->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <p class="text-xs text-rose-500"><?php echo e($message); ?></p>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                    <div class="space-y-1">
                                                        <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200"><?php echo e(__('Range Akhir')); ?></label>
                                                        <input
                                                            name="range_akhir"
                                                            type="number"
                                                            step="0.01"
                                                            min="0"
                                                            value="<?php echo e($value('range_akhir')); ?>"
                                                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-900"
                                                            required
                                                        >
                                                        <?php $__errorArgs = ['range_akhir', 'updateMasterPerhitungan_' . $perhitungan->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <p class="text-xs text-rose-500"><?php echo e($message); ?></p>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                </div>

                                                <div class="grid gap-4 sm:grid-cols-2">
                                                    <div class="space-y-1">
                                                        <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200"><?php echo e(__('Tarif Bunga Harian')); ?></label>
                                                        <input
                                                            name="tarif_bunga_harian"
                                                            type="number"
                                                            step="0.0001"
                                                            min="0"
                                                            max="1"
                                                            value="<?php echo e($value('tarif_bunga_harian')); ?>"
                                                            x-bind:required="skemaBunga === 'harian'"
                                                            x-bind:disabled="skemaBunga === 'periodik'"
                                                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-900"
                                                        >
                                                        <?php $__errorArgs = ['tarif_bunga_harian', 'updateMasterPerhitungan_' . $perhitungan->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <p class="text-xs text-rose-500"><?php echo e($message); ?></p>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                    <div class="space-y-1" x-show="skemaBunga === 'periodik'" x-cloak>
                                                        <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200"><?php echo e(__('Tarif Bunga per Periode')); ?></label>
                                                        <input
                                                            name="tarif_bunga_per_periode"
                                                            type="number"
                                                            step="0.0001"
                                                            min="0"
                                                            max="1"
                                                            value="<?php echo e($value('tarif_bunga_per_periode')); ?>"
                                                            x-bind:required="skemaBunga === 'periodik'"
                                                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-900"
                                                        >
                                                        <?php $__errorArgs = ['tarif_bunga_per_periode', 'updateMasterPerhitungan_' . $perhitungan->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <p class="text-xs text-rose-500"><?php echo e($message); ?></p>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                </div>

                                                <div class="grid gap-4 sm:grid-cols-2">

                                                    <div class="space-y-1" x-show="skemaBunga === 'periodik'" x-cloak>
                                                        <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200"><?php echo e(__('Periode (Hari)')); ?></label>
                                                        <input
                                                            name="periode_hari"
                                                            type="number"
                                                            min="1"
                                                            value="<?php echo e($value('periode_hari')); ?>"
                                                            x-bind:required="skemaBunga === 'periodik'"
                                                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-900"
                                                        >
                                                        <?php $__errorArgs = ['periode_hari', 'updateMasterPerhitungan_' . $perhitungan->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <p class="text-xs text-rose-500"><?php echo e($message); ?></p>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>

                                                    <div class="space-y-1">
                                                        <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200"><?php echo e(__('Tenor (Hari)')); ?></label>
                                                        <input
                                                            name="tenor_hari"
                                                            type="number"
                                                            min="1"
                                                            value="<?php echo e($value('tenor_hari')); ?>"
                                                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-900"
                                                            required
                                                        >
                                                        <?php $__errorArgs = ['tenor_hari', 'updateMasterPerhitungan_' . $perhitungan->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <p class="text-xs text-rose-500"><?php echo e($message); ?></p>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                    
                                                </div>

                                                <div class="grid gap-4 sm:grid-cols-2">
                                                    <div class="space-y-1">
                                                        <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200"><?php echo e(__('Jatuh Tempo Awal (Hari)')); ?></label>
                                                        <input
                                                            name="jatuh_tempo_awal"
                                                            type="number"
                                                            min="1"
                                                            value="<?php echo e($value('jatuh_tempo_awal')); ?>"
                                                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-900"
                                                            required
                                                        >
                                                        <?php $__errorArgs = ['jatuh_tempo_awal', 'updateMasterPerhitungan_' . $perhitungan->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <p class="text-xs text-rose-500"><?php echo e($message); ?></p>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                    <div class="space-y-1">
                                                        <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200"><?php echo e(__('Biaya Admin')); ?></label>
                                                        <input
                                                            name="biaya_admin"
                                                            type="number"
                                                            step="0.01"
                                                            min="0"
                                                            value="<?php echo e($value('biaya_admin')); ?>"
                                                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-900"
                                                            required
                                                        >
                                                        <?php $__errorArgs = ['biaya_admin', 'updateMasterPerhitungan_' . $perhitungan->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <p class="text-xs text-rose-500"><?php echo e($message); ?></p>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                </div>

                                                <div class="flex flex-col gap-2 border-t border-neutral-200 pt-4 text-xs text-neutral-500 dark:border-neutral-700 dark:text-neutral-300 sm:flex-row sm:items-center sm:justify-between">
                                                    <span><?php echo e(__('Terakhir diperbarui:')); ?> <?php echo e($perhitungan->updated_at?->translatedFormat('d F Y H:i')); ?></span>
                                                    <div class="flex flex-wrap gap-2">
                                                        <button
                                                            type="button"
                                                            class="rounded-lg border border-neutral-300 px-3 py-1.5 font-semibold text-neutral-700 transition hover:bg-white dark:border-neutral-500 dark:text-neutral-100"
                                                            x-on:click="isEditing = false"
                                                        >
                                                            <?php echo e(__('Batal')); ?>

                                                        </button>
                                                        <button
                                                            type="submit"
                                                            class="rounded-lg bg-indigo-600 px-3 py-1.5 font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                                                        >
                                                            <?php echo e(__('Simpan Perubahan')); ?>

                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="11" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-300">
                                    <?php echo e(__('Belum ada data perhitungan gadai. Tambahkan rumus baru untuk memulai.')); ?>

                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="border-t border-neutral-200 px-6 py-4 text-sm text-neutral-600 dark:border-neutral-800 dark:text-neutral-300">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
                        <label for="rowsPerPageSelect" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">
                            Rows per page
                            <span class="font-semibold text-neutral-900 dark:text-white" data-rows-per-page-display>10</span>
                        </label>
                        <select
                            id="rowsPerPageSelect"
                            class="w-28 rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm font-semibold text-neutral-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white"
                            data-rows-per-page
                        >
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>

                    <nav class="flex flex-wrap items-center gap-1" aria-label="Pagination" data-pagination></nav>
                </div>
            </div>
        </div>
    </div>

    <script>
        (() => {
            window.appMasterPerhitunganGadai ??= {};
            const namespace = window.appMasterPerhitunganGadai;

            namespace.initializeEnhancements ??= () => {
                const table = document.querySelector('[data-master-table]');

                if (!table || table.dataset.masterEnhanced === 'true') {
                    return;
                }

                table.dataset.masterEnhanced = 'true';

                const rowsContainer = table.querySelector('[data-master-rows]');

                if (!rowsContainer) {
                    return;
                }

                const rows = Array.from(rowsContainer.querySelectorAll('[data-master-row]') ?? []);
                const sortButtons = table.querySelectorAll('[data-sort-key]');
                const rowsPerPageSelect = document.querySelector('[data-rows-per-page]');
                const rowsPerPageDisplay = document.querySelector('[data-rows-per-page-display]');
                const paginationContainer = document.querySelector('[data-pagination]');
                const numericKeys = new Set([
                    'range_awal',
                    'range_akhir',
                    'tarif_bunga_harian',
                    'tarif_bunga_per_periode',
                    'periode_hari',
                    'tenor_hari',
                    'jatuh_tempo_awal',
                    'biaya_admin',
                ]);

                const state = {
                    rows,
                    sortedRows: [...rows],
                    sortKey: rows.length > 0 ? 'type' : null,
                    sortDirection: 'asc',
                    page: 1,
                    rowsPerPage: Number(rowsPerPageSelect?.value ?? 10) || 10,
                };

                if (rowsPerPageSelect) {
                    rowsPerPageSelect.value = String(state.rowsPerPage);
                }

                const toDatasetKey = (key) =>
                    String(key ?? '')
                        .toLowerCase()
                        .replace(/[-_](\w)/g, (_, letter) => letter.toUpperCase());

                const getRowValue = (row, key) => {
                    const datasetKey = toDatasetKey(key);
                    const raw = row?.dataset?.[datasetKey];

                    if (numericKeys.has(key)) {
                        const numericValue = Number(raw);
                        return Number.isNaN(numericValue) ? 0 : numericValue;
                    }

                    return String(raw ?? '').toLowerCase();
                };

                const updateSortIndicators = () => {
                    sortButtons.forEach((button) => {
                        const icon = button.querySelector('[data-sort-icon]');
                        const isActive = state.sortKey === button.dataset.sortKey;

                        if (!icon) {
                            return;
                        }

                        if (isActive) {
                            icon.classList.remove('hidden');
                            icon.classList.toggle('rotate-180', state.sortDirection === 'desc');
                        } else {
                            icon.classList.add('hidden');
                            icon.classList.remove('rotate-180');
                        }
                    });
                };

                const updateRowsPerPageDisplay = () => {
                    if (rowsPerPageDisplay) {
                        rowsPerPageDisplay.textContent = state.rowsPerPage;
                    }
                };

                const getTotalPages = () => {
                    if (!state.sortedRows.length) {
                        return 1;
                    }

                    return Math.ceil(state.sortedRows.length / state.rowsPerPage);
                };

                const renderPaginationControls = (totalPages) => {
                    if (!paginationContainer) {
                        return;
                    }

                    paginationContainer.innerHTML = '';

                    const hasRows = state.sortedRows.length > 0;

                    const createButton = ({ label, onClick, disabled = false, isActive = false }) => {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.textContent = label;

                        const baseClasses = [
                            'inline-flex',
                            'items-center',
                            'rounded-md',
                            'px-3',
                            'py-1.5',
                            'text-xs',
                            'font-semibold',
                            'transition',
                        ];

                        if (isActive) {
                            baseClasses.push('bg-neutral-900', 'text-white', 'dark:bg-white', 'dark:text-neutral-900');
                        } else {
                            baseClasses.push('text-neutral-700', 'hover:bg-neutral-100', 'dark:text-neutral-200', 'dark:hover:bg-neutral-800');
                        }

                        const isDisabled = disabled || !hasRows;

                        if (isDisabled) {
                            baseClasses.push('cursor-not-allowed', 'opacity-50');
                            button.disabled = true;
                        } else if (typeof onClick === 'function') {
                            button.addEventListener('click', onClick);
                        }

                        button.className = baseClasses.join(' ');

                        return button;
                    };

                    const goToPage = (page) => () => {
                        state.page = Math.min(Math.max(1, page), totalPages);
                        renderPage();
                    };

                    paginationContainer.appendChild(
                        createButton({
                            label: '<< First',
                            onClick: goToPage(1),
                            disabled: state.page === 1,
                        }),
                    );

                    paginationContainer.appendChild(
                        createButton({
                            label: '< Back',
                            onClick: goToPage(state.page - 1),
                            disabled: state.page === 1,
                        }),
                    );

                    for (let page = 1; page <= totalPages; page += 1) {
                        paginationContainer.appendChild(
                            createButton({
                                label: String(page),
                                onClick: goToPage(page),
                                isActive: state.page === page,
                            }),
                        );
                    }

                    paginationContainer.appendChild(
                        createButton({
                            label: 'Next >',
                            onClick: goToPage(state.page + 1),
                            disabled: state.page === totalPages,
                        }),
                    );

                    paginationContainer.appendChild(
                        createButton({
                            label: 'Last >>',
                            onClick: goToPage(totalPages),
                            disabled: state.page === totalPages,
                        }),
                    );
                };

                const renderPage = () => {
                    const totalPages = getTotalPages();

                    state.page = Math.min(Math.max(1, state.page), totalPages);

                    const startIndex = (state.page - 1) * state.rowsPerPage;
                    const endIndex = startIndex + state.rowsPerPage;

                    state.sortedRows.forEach((row, index) => {
                        const shouldShow = index >= startIndex && index < endIndex;
                        row.classList.toggle('hidden', !shouldShow);
                    });

                    updateRowsPerPageDisplay();
                    renderPaginationControls(totalPages);
                };

                const applySort = () => {
                    state.sortedRows = [...state.rows];

                    if (state.sortKey) {
                        state.sortedRows.sort((rowA, rowB) => {
                            const valueA = getRowValue(rowA, state.sortKey);
                            const valueB = getRowValue(rowB, state.sortKey);

                            if (valueA < valueB) {
                                return state.sortDirection === 'asc' ? -1 : 1;
                            }

                            if (valueA > valueB) {
                                return state.sortDirection === 'asc' ? 1 : -1;
                            }

                            return 0;
                        });
                    }

                    state.sortedRows.forEach((row) => rowsContainer.appendChild(row));

                    updateSortIndicators();
                    renderPage();
                };

                sortButtons.forEach((button) => {
                    const key = button.dataset.sortKey;

                    if (!key) {
                        return;
                    }

                    button.addEventListener('click', () => {
                        if (state.sortKey === key) {
                            state.sortDirection = state.sortDirection === 'asc' ? 'desc' : 'asc';
                        } else {
                            state.sortKey = key;
                            state.sortDirection = 'asc';
                        }

                        state.page = 1;
                        applySort();
                    });
                });

                rowsPerPageSelect?.addEventListener('change', (event) => {
                    const value = Number(event.target.value);
                    state.rowsPerPage = Number.isNaN(value) ? 10 : value;
                    state.page = 1;
                    renderPage();
                });

                applySort();
            };

            const runInitializer = () => {
                window.requestAnimationFrame(() => namespace.initializeEnhancements());
            };

            if (!namespace.listenersBound) {
                document.addEventListener('DOMContentLoaded', runInitializer);
                document.addEventListener('livewire:navigated', runInitializer);
                namespace.listenersBound = true;
            }

            runInitializer();
        })();
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php /**PATH /var/www/gd/resources/views/admin/master-perhitungan-gadai/index.blade.php ENDPATH**/ ?>