<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'paginator',
    'perPageOptions' => [10, 25, 50, 100],
    'filters' => [],
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'paginator',
    'perPageOptions' => [10, 25, 50, 100],
    'filters' => [],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $totalPages = max(1, $paginator->lastPage());
    $isOnFirstPage = $paginator->onFirstPage();
    $isOnLastPage = $paginator->currentPage() === $paginator->lastPage();
    $fieldId = 'table-rows-per-page-' . uniqid();

    $renderHiddenInputs = function ($name, $value) use (&$renderHiddenInputs) {
        if (is_array($value)) {
            foreach ($value as $key => $nested) {
                $renderHiddenInputs($name . '[' . $key . ']', $nested);
            }

            return;
        }

        echo '<input type="hidden" name="' . e($name) . '" value="' . e($value) . '">';
    };
?>

<div class="mt-4 border-t border-neutral-200 pt-4 dark:border-neutral-800">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" class="flex items-center gap-3 text-sm text-neutral-600 dark:text-neutral-300">
            <?php $__currentLoopData = $filters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(in_array($name, ['page', 'per_page'], true)) continue; ?>
                <?php ($renderHiddenInputs($name, $value)); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <label for="<?php echo e($fieldId); ?>" class="flex items-center gap-1">
                <span class="font-medium">Rows per page</span>
                <span class="rounded bg-neutral-100 px-2 py-0.5 text-xs font-semibold text-neutral-700 dark:bg-neutral-800 dark:text-neutral-100">
                    <?php echo e($paginator->perPage()); ?>

                </span>
            </label>
            <select
                id="<?php echo e($fieldId); ?>"
                name="per_page"
                class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm font-medium text-neutral-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100"
                onchange="this.form.submit()"
            >
                <?php $__currentLoopData = $perPageOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($option); ?>" <?php if($paginator->perPage() === (int) $option): echo 'selected'; endif; ?>><?php echo e($option); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </form>

        <nav class="flex flex-wrap items-center gap-1 text-sm font-medium" aria-label="Pagination">
            <a
                href="<?php echo e($isOnFirstPage ? '#' : $paginator->url(1)); ?>"
                class="inline-flex items-center rounded-lg border px-3 py-2 <?php echo e($isOnFirstPage ? 'cursor-not-allowed border-neutral-200 text-neutral-400 dark:border-neutral-700 dark:text-neutral-600' : 'border-neutral-300 text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800/70'); ?>"
                aria-disabled="<?php echo e($isOnFirstPage ? 'true' : 'false'); ?>"
            >&laquo; First</a>
            <a
                href="<?php echo e($isOnFirstPage ? '#' : $paginator->previousPageUrl()); ?>"
                class="inline-flex items-center rounded-lg border px-3 py-2 <?php echo e($isOnFirstPage ? 'cursor-not-allowed border-neutral-200 text-neutral-400 dark:border-neutral-700 dark:text-neutral-600' : 'border-neutral-300 text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800/70'); ?>"
                aria-disabled="<?php echo e($isOnFirstPage ? 'true' : 'false'); ?>"
            >&lsaquo; Back</a>

            <?php for($page = 1; $page <= $totalPages; $page++): ?>
                <a
                    href="<?php echo e($paginator->url($page)); ?>"
                    class="inline-flex items-center rounded-lg border px-3 py-2 <?php echo e($page === $paginator->currentPage() ? 'border-neutral-900 bg-neutral-900 text-white dark:border-white dark:bg-white dark:text-neutral-900' : 'border-neutral-300 text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800/70'); ?>"
                    aria-current="<?php echo e($page === $paginator->currentPage() ? 'page' : 'false'); ?>"
                >
                    <?php echo e($page); ?>

                </a>
            <?php endfor; ?>

            <a
                href="<?php echo e($isOnLastPage ? '#' : $paginator->nextPageUrl()); ?>"
                class="inline-flex items-center rounded-lg border px-3 py-2 <?php echo e($isOnLastPage ? 'cursor-not-allowed border-neutral-200 text-neutral-400 dark:border-neutral-700 dark:text-neutral-600' : 'border-neutral-300 text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800/70'); ?>"
                aria-disabled="<?php echo e($isOnLastPage ? 'true' : 'false'); ?>"
            >Next &rsaquo;</a>
            <a
                href="<?php echo e($isOnLastPage ? '#' : $paginator->url($paginator->lastPage())); ?>"
                class="inline-flex items-center rounded-lg border px-3 py-2 <?php echo e($isOnLastPage ? 'cursor-not-allowed border-neutral-200 text-neutral-400 dark:border-neutral-700 dark:text-neutral-600' : 'border-neutral-300 text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800/70'); ?>"
                aria-disabled="<?php echo e($isOnLastPage ? 'true' : 'false'); ?>"
            >Last &raquo;</a>
        </nav>
    </div>
</div>
<?php /**PATH /var/www/geka/gd/resources/views/components/table-pagination.blade.php ENDPATH**/ ?>