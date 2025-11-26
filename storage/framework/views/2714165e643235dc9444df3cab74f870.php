<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Master Kode Group')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Master Kode Group'))]); ?>
    <div class="space-y-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Master Kode Group')); ?></h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                <?php echo e(__('Kelola daftar kode group dan harga dasar yang digunakan saat menambah atau mengubah data barang.')); ?>

            </p>
        </div>

        <?php if(session('status')): ?>
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-700 dark:border-emerald-500/60 dark:bg-emerald-500/10 dark:text-emerald-300">
                <p class="font-semibold"><?php echo e(session('status')); ?></p>
            </div>
        <?php endif; ?>

        <div class="space-y-6 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <div>
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Tambah Kode Group Baru')); ?></h2>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    <?php echo e(__('Masukkan kode group dan harga agar tersedia pada formulir data barang.')); ?>

                </p>
            </div>

            <form method="POST" action="<?php echo e(route('admin.master-kode-group.store')); ?>" class="grid gap-4 md:grid-cols-3 md:items-end">
                <?php echo csrf_field(); ?>
                <?php
                    $isCreateContext = old('form_mode') === 'create';
                ?>
                <input type="hidden" name="form_mode" value="create">

                <div class="space-y-2">
                    <label for="kode_group" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Kode Group')); ?></label>
                    <input
                        type="text"
                        id="kode_group"
                        name="kode_group"
                        value="<?php echo e($isCreateContext ? old('kode_group') : ''); ?>"
                        maxlength="191"
                        required
                        class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
                        placeholder="<?php echo e(__('Masukkan kode group')); ?>"
                    >
                    <?php if($isCreateContext && $errors->has('kode_group')): ?>
                        <p class="text-xs text-rose-600 dark:text-rose-400"><?php echo e($errors->first('kode_group')); ?></p>
                    <?php endif; ?>
                </div>

                <div class="space-y-2">
                    <label for="harga" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Harga (Rp)')); ?></label>
                    <input
                        type="number"
                        id="harga"
                        name="harga"
                        value="<?php echo e($isCreateContext ? old('harga') : ''); ?>"
                        step="0.01"
                        min="0"
                        required
                        class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
                        placeholder="<?php echo e(__('Masukkan harga')); ?>"
                    >
                    <?php if($isCreateContext && $errors->has('harga')): ?>
                        <p class="text-xs text-rose-600 dark:text-rose-400"><?php echo e($errors->first('harga')); ?></p>
                    <?php endif; ?>
                </div>

                <div class="flex items-center gap-3 md:justify-end">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
                    >
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span><?php echo e(__('Tambah Kode Group')); ?></span>
                    </button>
                </div>
            </form>
        </div>

        <div class="space-y-4 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Daftar Kode Group Tersimpan')); ?></h2>
                    <span class="text-sm text-neutral-500 dark:text-neutral-400">
                        <?php echo e(trans_choice('{0}Tidak ada kode group|{1}1 kode group|[2,*]:count kode group', $masterKodeGroups->count(), ['count' => $masterKodeGroups->count()])); ?>

                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 text-left text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200">
                    <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">#</th>
                            <th scope="col" class="px-4 py-3"><?php echo e(__('Kode Group')); ?></th>
                            <th scope="col" class="px-4 py-3"><?php echo e(__('Harga (Rp)')); ?></th>
                            <th scope="col" class="px-4 py-3"><?php echo e(__('Terakhir Diubah')); ?></th>
                            <th scope="col" class="px-4 py-3 text-right"><?php echo e(__('Aksi')); ?></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-800 dark:bg-neutral-900">
                        <?php $__empty_1 = true; $__currentLoopData = $masterKodeGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $masterKodeGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $isActiveRow = (int) old('master_kode_group_id') === $masterKodeGroup->id;
                            ?>
                            <form method="POST" action="<?php echo e(route('admin.master-kode-group.update', $masterKodeGroup)); ?>" class="contents">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <input type="hidden" name="master_kode_group_id" value="<?php echo e($masterKodeGroup->id); ?>">
                                <tr class="transition hover:bg-neutral-50 dark:hover:bg-neutral-800/60">
                                    <td class="px-4 py-3 align-top text-neutral-500 dark:text-neutral-400"><?php echo e($loop->iteration); ?></td>
                                    <td class="px-4 py-3 align-top">
                                        <div class="space-y-2">
                                            <label class="sr-only" for="kode_group-<?php echo e($masterKodeGroup->id); ?>"><?php echo e(__('Kode Group')); ?></label>
                                            <input
                                                type="text"
                                                id="kode_group-<?php echo e($masterKodeGroup->id); ?>"
                                                name="kode_group"
                                                value="<?php echo e($isActiveRow ? old('kode_group') : $masterKodeGroup->kode_group); ?>"
                                                maxlength="191"
                                                required
                                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
                                            >
                                            <?php if($isActiveRow && $errors->has('kode_group')): ?>
                                                <p class="text-xs text-rose-600 dark:text-rose-400"><?php echo e($errors->first('kode_group')); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <div class="space-y-2">
                                            <label class="sr-only" for="harga-<?php echo e($masterKodeGroup->id); ?>"><?php echo e(__('Harga')); ?></label>
                                            <input
                                                type="number"
                                                id="harga-<?php echo e($masterKodeGroup->id); ?>"
                                                name="harga"
                                                value="<?php echo e($isActiveRow ? old('harga') : $masterKodeGroup->harga); ?>"
                                                step="0.01"
                                                min="0"
                                                required
                                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-emerald-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
                                            >
                                            <?php if($isActiveRow && $errors->has('harga')): ?>
                                                <p class="text-xs text-rose-600 dark:text-rose-400"><?php echo e($errors->first('harga')); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top text-neutral-500 dark:text-neutral-400"><?php echo e($masterKodeGroup->updated_at?->translatedFormat('d F Y H:i') ?? '—'); ?></td>
                                    <td class="px-4 py-3 align-top">
                                        <div class="flex justify-end gap-2">
                                            <button
                                                type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg border border-emerald-600 bg-emerald-600 px-3 py-2 text-xs font-semibold text-blue-600 shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
                                            >
                                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                </svg>
                                                <span><?php echo e(__('Simpan')); ?></span>
                                            </button>

                                            <button
                                                type="submit"
                                                form="delete-master-kode-group-<?php echo e($masterKodeGroup->id); ?>"
                                                class="inline-flex items-center gap-1 rounded-lg border border-rose-600 bg-rose-600 px-3 py-2 text-xs font-semibold text-rose-600 shadow-sm transition hover:border-rose-700 hover:bg-rose-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-500 dark:border-rose-500 dark:bg-rose-500 dark:hover:border-rose-400 dark:hover:bg-rose-400"
                                                onclick="return confirm('<?php echo e(__('Hapus kode group ini?')); ?>')"
                                            >
                                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 4.5 15 15m0-15-15 15" />
                                                </svg>
                                                <span><?php echo e(__('Hapus')); ?></span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </form>
                            <form method="POST" action="<?php echo e(route('admin.master-kode-group.destroy', $masterKodeGroup)); ?>" id="delete-master-kode-group-<?php echo e($masterKodeGroup->id); ?>" class="hidden">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                            </form>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                    <?php echo e(__('Belum ada data kode group yang tersimpan.')); ?>

                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
<?php /**PATH /var/www/gd/resources/views/admin/master-kode-group/index.blade.php ENDPATH**/ ?>