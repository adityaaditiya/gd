<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Tambah Nasabah')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Tambah Nasabah'))]); ?>
    <div class="space-y-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Tambah Nasabah')); ?></h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                <?php echo e(__('Isi formulir berikut untuk mendaftarkan member baru dan mendapatkan kode member otomatis setelah data tersimpan.')); ?>

            </p>
        </div>

        <?php if(session('status')): ?>
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-700 dark:border-emerald-500/60 dark:bg-emerald-500/10 dark:text-emerald-300">
                <p class="font-semibold text-black"><?php echo e(session('status')); ?></p>
                <?php if(session('kode_member')): ?>
                    <p class="mt-1 text-sm text-black"><?php echo e(__('Kode member otomatis:')); ?></p>
                    <input
                        type="text"
                        readonly
                        value="<?php echo e(session('kode_member')); ?>"
                        class="mt-2 w-full rounded-lg border border-emerald-300 bg-white px-3 py-2 font-semibold tracking-wide text-emerald-700 shadow-sm dark:border-emerald-500/60 dark:bg-neutral-900 dark:text-emerald-300"
                    />
                    <p class="mt-1 text-x text-black" ><?php echo e(__('Salin kode ini untuk keperluan verifikasi dan layanan selanjutnya.')); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <form
                method="POST"
                action="<?php echo e(route('nasabah.data-nasabah.store')); ?>"
                class="space-y-6 p-6"
                data-nasabah-form
            >
                <?php echo csrf_field(); ?>

                <?php if (isset($component)) { $__componentOriginal82cc22a52c1f1aa48f2b896d206c406a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal82cc22a52c1f1aa48f2b896d206c406a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nasabah.form-fields','data' => ['redirectRoute' => 'nasabah.tambah-nasabah']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nasabah.form-fields'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['redirect-route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('nasabah.tambah-nasabah')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal82cc22a52c1f1aa48f2b896d206c406a)): ?>
<?php $attributes = $__attributesOriginal82cc22a52c1f1aa48f2b896d206c406a; ?>
<?php unset($__attributesOriginal82cc22a52c1f1aa48f2b896d206c406a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal82cc22a52c1f1aa48f2b896d206c406a)): ?>
<?php $component = $__componentOriginal82cc22a52c1f1aa48f2b896d206c406a; ?>
<?php unset($__componentOriginal82cc22a52c1f1aa48f2b896d206c406a); ?>
<?php endif; ?>
            </form>
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
<?php /**PATH /var/www/gd/resources/views/nasabah/tambah-nasabah.blade.php ENDPATH**/ ?>