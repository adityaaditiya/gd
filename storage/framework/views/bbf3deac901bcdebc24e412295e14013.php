<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Tambah Data Barang')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Tambah Data Barang'))]); ?>
    <div class="space-y-8">
        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-3 text-sm text-neutral-500 dark:text-neutral-400">
                <a href="<?php echo e(route('barang.data-barang')); ?>" class="inline-flex items-center gap-1 font-medium text-emerald-600 hover:text-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:text-emerald-400 dark:hover:text-emerald-300">
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    <span><?php echo e(__('Kembali')); ?></span>
                </a>
            </div>

            <div class="space-y-1">
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Tambah Data Barang')); ?></h1>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    <?php echo e(__('Lengkapi formulir di bawah untuk menyimpan data barang cicilan emas.')); ?>

                </p>
            </div>
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <form method="POST" action="<?php echo e(route('barang.data-barang.store')); ?>" class="space-y-5">
                <?php echo csrf_field(); ?>
                <?php
                    $isCreateContext = old('context') === 'create';
                ?>
                <input type="hidden" name="context" value="create">

                <div class="space-y-1.5">
                    <label for="kode_barcode" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Kode Barcode')); ?></label>
                    <input
                        type="text"
                        id="kode_barcode"
                        name="kode_barcode"
                        value="<?php echo e(old('kode_barcode')); ?>"
                        required
                        maxlength="191"
                        autofocus
                        class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-950 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                    />
                    <?php $__errorArgs = ['kode_barcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-sm text-rose-600 dark:text-rose-400"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="space-y-1.5">
                    <label for="nama_barang" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Nama Barang')); ?></label>
                    <input
                        type="text"
                        id="nama_barang"
                        name="nama_barang"
                        value="<?php echo e(old('nama_barang')); ?>"
                        required
                        maxlength="191"
                        class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-950 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                    />
                    <?php $__errorArgs = ['nama_barang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-sm text-rose-600 dark:text-rose-400"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="space-y-1.5">
                    <label for="kode_intern" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Kode Intern')); ?></label>
                    <input
                        type="text"
                        id="kode_intern"
                        name="kode_intern"
                        value="<?php echo e(old('kode_intern')); ?>"
                        required
                        maxlength="191"
                        class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-950 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                    />
                    <?php $__errorArgs = ['kode_intern'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-sm text-rose-600 dark:text-rose-400"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="space-y-1.5">
                    <label for="kode_baki" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Kode Baki')); ?></label>
                    <input
                        type="text"
                        id="kode_baki"
                        name="kode_baki"
                        value="<?php echo e(old('kode_baki')); ?>"
                        required
                        maxlength="191"
                        class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-950 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                    />
                    <?php $__errorArgs = ['kode_baki'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-sm text-rose-600 dark:text-rose-400"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="space-y-1.5">
                    <label for="kode_jenis" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Kode Jenis')); ?></label>
                    <input
                        type="text"
                        id="kode_jenis"
                        name="kode_jenis"
                        value="<?php echo e(old('kode_jenis')); ?>"
                        required
                        maxlength="191"
                        class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-950 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                    />
                    <?php $__errorArgs = ['kode_jenis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-sm text-rose-600 dark:text-rose-400"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="space-y-1.5">
                    <label for="kode_group" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Kode Group')); ?></label>
                    <select
                        id="kode_group"
                        name="kode_group"
                        data-master-kode-group-select
                        required
                        class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-950 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                    >
                        <option value=""><?php echo e(__('Pilih kode group')); ?></option>
                        <?php $__currentLoopData = $masterKodeGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $masterKodeGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option
                                value="<?php echo e($masterKodeGroup->kode_group); ?>"
                                data-price="<?php echo e($masterKodeGroup->harga); ?>"
                                <?php if($isCreateContext && old('kode_group') === $masterKodeGroup->kode_group): echo 'selected'; endif; ?>
                            >
                                <?php echo e($masterKodeGroup->kode_group); ?> — Rp <?php echo e(number_format((float) $masterKodeGroup->harga, 2, ',', '.')); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php if($isCreateContext && $errors->has('kode_group')): ?>
                        <p class="text-sm text-rose-600 dark:text-rose-400"><?php echo e($errors->first('kode_group')); ?></p>
                    <?php endif; ?>
                    <?php if($masterKodeGroups->isEmpty()): ?>
                        <p class="text-xs text-amber-600 dark:text-amber-400"><?php echo e(__('Belum ada data kode group. Tambahkan data melalui menu Master Kode Group terlebih dahulu.')); ?></p>
                    <?php endif; ?>
                </div>

                <div class="space-y-1.5">
                    <label for="kadar" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Kadar')); ?></label>
                    <input
                        type="number"
                        id="kadar"
                        name="kadar"
                        value="<?php echo e(old('kadar')); ?>"
                        step="0.01"
                        min="0"
                        class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-950 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                    />
                    <?php $__errorArgs = ['kadar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-sm text-rose-600 dark:text-rose-400"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="space-y-1.5">
                    <label for="berat" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Berat (gram)')); ?></label>
                    <input
                        type="number"
                        id="berat"
                        name="berat"
                        value="<?php echo e(old('berat')); ?>"
                        required
                        step="0.001"
                        min="0"
                        class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-950 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                    />
                    <?php $__errorArgs = ['berat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-sm text-rose-600 dark:text-rose-400"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="space-y-1.5">
                    <label for="harga" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Harga (Rp)')); ?></label>
                    <div class="flex rounded-lg border border-neutral-300 bg-white text-sm shadow-sm focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-950 dark:focus-within:border-emerald-400 dark:focus-within:ring-emerald-900/40">
                        <span class="flex items-center px-3 text-neutral-500 dark:text-neutral-400">Rp</span>
                        <input
                            type="number"
                            id="harga"
                            name="harga"
                            value="<?php echo e($isCreateContext ? old('harga') : ''); ?>"
                            step="0.01"
                            min="0"
                            data-master-kode-group-price
                            readonly
                            class="w-full rounded-r-lg border-0 bg-transparent px-3 py-2 text-neutral-900 focus:outline-none focus:ring-0 dark:text-white"
                        />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a
                        href="<?php echo e(route('barang.data-barang')); ?>"
                        class="inline-flex items-center gap-2 rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-600 transition hover:border-neutral-400 hover:text-neutral-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-400 dark:border-neutral-600 dark:text-neutral-200 dark:hover:border-neutral-500 dark:hover:text-white"
                    >
                        <?php echo e(__('Batal')); ?>

                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
                    >
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span><?php echo e(__('Simpan Barang')); ?></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php echo $__env->make('barang.partials.master-kode-group-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
<?php /**PATH /var/www/gd/resources/views/barang/create.blade.php ENDPATH**/ ?>