<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Edit Nasabah')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Edit Nasabah'))]); ?>
    <div class="space-y-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Edit Nasabah')); ?></h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                <?php echo e(__('Perbarui informasi nasabah berikut dengan memastikan seluruh data valid dan terbaru.')); ?>

            </p>
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <form
                method="POST"
                action="<?php echo e(route('nasabah.update', $nasabah)); ?>"
                class="space-y-6 p-6"
                data-nasabah-form
            >
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <?php if (isset($component)) { $__componentOriginal82cc22a52c1f1aa48f2b896d206c406a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal82cc22a52c1f1aa48f2b896d206c406a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nasabah.form-fields','data' => ['nasabah' => $nasabah,'submitLabel' => __('Perbarui')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nasabah.form-fields'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['nasabah' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($nasabah),'submit-label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Perbarui'))]); ?>
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
<?php /**PATH /var/www/gd/resources/views/nasabah/edit-nasabah.blade.php ENDPATH**/ ?>