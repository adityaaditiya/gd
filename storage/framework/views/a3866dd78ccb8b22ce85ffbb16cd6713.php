<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'paginator',
    'perPage' => 10,
    'perPageOptions' => [10, 25, 50, 100],
    'formAction' => null,
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
    'perPage' => 10,
    'perPageOptions' => [10, 25, 50, 100],
    'formAction' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $formAction = $formAction ?: request()->url();
    $queryParams = request()->except('page', 'per_page');
    $buildInputs = function ($name, $value) use (&$buildInputs) {
        if (is_array($value)) {
            $html = '';
            foreach ($value as $key => $nestedValue) {
                $fieldName = is_int($key) ? $name . '[]' : $name . '[' . $key . ']';
                $html .= $buildInputs($fieldName, $nestedValue);
            }

            return $html;
        }

        return '<input type="hidden" name="' . e($name) . '" value="' . e($value) . '">';
    };

    $currentPage = max(1, $paginator->currentPage());
    $lastPage = max(1, $paginator->lastPage());

    if ($lastPage <= 7) {
        $pages = range(1, $lastPage);
    } else {
        $pages = [1];

        if ($currentPage > 4) {
            $pages[] = 'ellipsis-left';
        }

        $start = max(2, $currentPage - 1);
        $end = min($lastPage - 1, $currentPage + 1);

        for ($page = $start; $page <= $end; $page++) {
            if ($page !== 1 && $page !== $lastPage) {
                $pages[] = $page;
            }
        }

        if ($currentPage < $lastPage - 3) {
            $pages[] = 'ellipsis-right';
        }

        if ($lastPage > 1) {
            $pages[] = $lastPage;
        }

        $pages = array_values(array_unique($pages));
    }

    $buttonBase = 'inline-flex items-center gap-1 rounded-lg border px-3 py-2 text-xs font-semibold transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500';
    $buttonInactive = $buttonBase . ' border-neutral-300 text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-700/60';
    $buttonDisabled = $buttonBase . ' cursor-not-allowed border-neutral-200 text-neutral-400 dark:border-neutral-700 dark:text-neutral-500';
    $buttonActive = $buttonBase . ' border-neutral-900 bg-neutral-900 text-white dark:border-white dark:bg-white dark:text-neutral-900';
?>

<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <form method="GET" action="<?php echo e($formAction); ?>" class="flex flex-wrap items-center gap-3 text-sm text-neutral-600 dark:text-neutral-300">
        <?php $__currentLoopData = $queryParams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $buildInputs($name, $value); ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <label for="per-page-select" class="flex items-center gap-3">
            <span class="text-sm font-medium"><?php echo e(__('Rows per page')); ?>: <span class="font-semibold text-neutral-900 dark:text-white"><?php echo e($perPage); ?></span></span>
            <select
                id="per-page-select"
                name="per_page"
                class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm font-medium text-neutral-700 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                onchange="this.form.submit()"
            >
                <?php $__currentLoopData = $perPageOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($option); ?>" <?php if((int) $option === (int) $perPage): echo 'selected'; endif; ?>><?php echo e($option); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </label>
    </form>

    <nav class="flex flex-wrap items-center gap-2 text-sm text-neutral-600 dark:text-neutral-300" aria-label="<?php echo e(__('Navigasi halaman')); ?>">
        <?php if($currentPage > 1): ?>
            <a href="<?php echo e($paginator->url(1)); ?>" class="<?php echo e($buttonInactive); ?>">&laquo;&laquo; <?php echo e(__('First')); ?></a>
        <?php else: ?>
            <span class="<?php echo e($buttonDisabled); ?>">&laquo;&laquo; <?php echo e(__('First')); ?></span>
        <?php endif; ?>

        <?php if($currentPage > 1): ?>
            <a href="<?php echo e($paginator->previousPageUrl()); ?>" class="<?php echo e($buttonInactive); ?>">&lsaquo; <?php echo e(__('Back')); ?></a>
        <?php else: ?>
            <span class="<?php echo e($buttonDisabled); ?>">&lsaquo; <?php echo e(__('Back')); ?></span>
        <?php endif; ?>

        <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(str_starts_with((string) $page, 'ellipsis')): ?>
                <span class="px-2 text-neutral-400 dark:text-neutral-500">&hellip;</span>
            <?php else: ?>
                <?php $pageNumber = (int) $page; ?>
                <?php if($pageNumber === $currentPage): ?>
                    <span class="<?php echo e($buttonActive); ?>" aria-current="page"><?php echo e($pageNumber); ?></span>
                <?php else: ?>
                    <a href="<?php echo e($paginator->url($pageNumber)); ?>" class="<?php echo e($buttonInactive); ?>"><?php echo e($pageNumber); ?></a>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php if($currentPage < $lastPage): ?>
            <a href="<?php echo e($paginator->nextPageUrl()); ?>" class="<?php echo e($buttonInactive); ?>"><?php echo e(__('Next')); ?> &rsaquo;</a>
        <?php else: ?>
            <span class="<?php echo e($buttonDisabled); ?>"><?php echo e(__('Next')); ?> &rsaquo;</span>
        <?php endif; ?>

        <?php if($currentPage < $lastPage): ?>
            <a href="<?php echo e($paginator->url($lastPage)); ?>" class="<?php echo e($buttonInactive); ?>"><?php echo e(__('Last')); ?> &raquo;&raquo;</a>
        <?php else: ?>
            <span class="<?php echo e($buttonDisabled); ?>"><?php echo e(__('Last')); ?> &raquo;&raquo;</span>
        <?php endif; ?>
    </nav>
</div>
<?php /**PATH E:\New folder\laravel 12 php 8\gd\resources\views/components/table/pagination-controls.blade.php ENDPATH**/ ?>