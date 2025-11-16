@props([
    'paginator',
    'perPageOptions' => [10, 25, 50, 100],
    'filters' => [],
])

@php
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
@endphp

<div class="mt-4 border-t border-neutral-200 pt-4 dark:border-neutral-800">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" class="flex items-center gap-3 text-sm text-neutral-600 dark:text-neutral-300">
            @foreach ($filters as $name => $value)
                @continue(in_array($name, ['page', 'per_page'], true))
                @php($renderHiddenInputs($name, $value))
            @endforeach

            <label for="{{ $fieldId }}" class="flex items-center gap-1">
                <span class="font-medium">Rows per page</span>
                <span class="rounded bg-neutral-100 px-2 py-0.5 text-xs font-semibold text-neutral-700 dark:bg-neutral-800 dark:text-neutral-100">
                    {{ $paginator->perPage() }}
                </span>
            </label>
            <select
                id="{{ $fieldId }}"
                name="per_page"
                class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm font-medium text-neutral-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100"
                onchange="this.form.submit()"
            >
                @foreach ($perPageOptions as $option)
                    <option value="{{ $option }}" @selected($paginator->perPage() === (int) $option)>{{ $option }}</option>
                @endforeach
            </select>
        </form>

        <nav class="flex flex-wrap items-center gap-1 text-sm font-medium" aria-label="Pagination">
            <a
                href="{{ $isOnFirstPage ? '#' : $paginator->url(1) }}"
                class="inline-flex items-center rounded-lg border px-3 py-2 {{ $isOnFirstPage ? 'cursor-not-allowed border-neutral-200 text-neutral-400 dark:border-neutral-700 dark:text-neutral-600' : 'border-neutral-300 text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800/70' }}"
                aria-disabled="{{ $isOnFirstPage ? 'true' : 'false' }}"
            >&laquo; First</a>
            <a
                href="{{ $isOnFirstPage ? '#' : $paginator->previousPageUrl() }}"
                class="inline-flex items-center rounded-lg border px-3 py-2 {{ $isOnFirstPage ? 'cursor-not-allowed border-neutral-200 text-neutral-400 dark:border-neutral-700 dark:text-neutral-600' : 'border-neutral-300 text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800/70' }}"
                aria-disabled="{{ $isOnFirstPage ? 'true' : 'false' }}"
            >&lsaquo; Back</a>

            @for ($page = 1; $page <= $totalPages; $page++)
                <a
                    href="{{ $paginator->url($page) }}"
                    class="inline-flex items-center rounded-lg border px-3 py-2 {{ $page === $paginator->currentPage() ? 'border-neutral-900 bg-neutral-900 text-white dark:border-white dark:bg-white dark:text-neutral-900' : 'border-neutral-300 text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800/70' }}"
                    aria-current="{{ $page === $paginator->currentPage() ? 'page' : 'false' }}"
                >
                    {{ $page }}
                </a>
            @endfor

            <a
                href="{{ $isOnLastPage ? '#' : $paginator->nextPageUrl() }}"
                class="inline-flex items-center rounded-lg border px-3 py-2 {{ $isOnLastPage ? 'cursor-not-allowed border-neutral-200 text-neutral-400 dark:border-neutral-700 dark:text-neutral-600' : 'border-neutral-300 text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800/70' }}"
                aria-disabled="{{ $isOnLastPage ? 'true' : 'false' }}"
            >Next &rsaquo;</a>
            <a
                href="{{ $isOnLastPage ? '#' : $paginator->url($paginator->lastPage()) }}"
                class="inline-flex items-center rounded-lg border px-3 py-2 {{ $isOnLastPage ? 'cursor-not-allowed border-neutral-200 text-neutral-400 dark:border-neutral-700 dark:text-neutral-600' : 'border-neutral-300 text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800/70' }}"
                aria-disabled="{{ $isOnLastPage ? 'true' : 'false' }}"
            >Last &raquo;</a>
        </nav>
    </div>
</div>
