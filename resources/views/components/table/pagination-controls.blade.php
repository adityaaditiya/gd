@props([
    'paginator',
    'perPage' => 10,
    'perPageOptions' => [10, 25, 50, 100],
    'formAction' => null,
])

@php
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
@endphp

<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <form method="GET" action="{{ $formAction }}" class="flex flex-wrap items-center gap-3 text-sm text-neutral-600 dark:text-neutral-300">
        @foreach ($queryParams as $name => $value)
            {!! $buildInputs($name, $value) !!}
        @endforeach
        <label for="per-page-select" class="flex items-center gap-3">
            <span class="text-sm font-medium">{{ __('Rows per page') }}: <span class="font-semibold text-neutral-900 dark:text-white">{{ $perPage }}</span></span>
            <select
                id="per-page-select"
                name="per_page"
                class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm font-medium text-neutral-700 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                onchange="this.form.submit()"
            >
                @foreach ($perPageOptions as $option)
                    <option value="{{ $option }}" @selected((int) $option === (int) $perPage)>{{ $option }}</option>
                @endforeach
            </select>
        </label>
    </form>

    <nav class="flex flex-wrap items-center gap-2 text-sm text-neutral-600 dark:text-neutral-300" aria-label="{{ __('Navigasi halaman') }}">
        @if ($currentPage > 1)
            <a href="{{ $paginator->url(1) }}" class="{{ $buttonInactive }}">&laquo;&laquo; {{ __('First') }}</a>
        @else
            <span class="{{ $buttonDisabled }}">&laquo;&laquo; {{ __('First') }}</span>
        @endif

        @if ($currentPage > 1)
            <a href="{{ $paginator->previousPageUrl() }}" class="{{ $buttonInactive }}">&lsaquo; {{ __('Back') }}</a>
        @else
            <span class="{{ $buttonDisabled }}">&lsaquo; {{ __('Back') }}</span>
        @endif

        @foreach ($pages as $page)
            @if (str_starts_with((string) $page, 'ellipsis'))
                <span class="px-2 text-neutral-400 dark:text-neutral-500">&hellip;</span>
            @else
                @php $pageNumber = (int) $page; @endphp
                @if ($pageNumber === $currentPage)
                    <span class="{{ $buttonActive }}" aria-current="page">{{ $pageNumber }}</span>
                @else
                    <a href="{{ $paginator->url($pageNumber) }}" class="{{ $buttonInactive }}">{{ $pageNumber }}</a>
                @endif
            @endif
        @endforeach

        @if ($currentPage < $lastPage)
            <a href="{{ $paginator->nextPageUrl() }}" class="{{ $buttonInactive }}">{{ __('Next') }} &rsaquo;</a>
        @else
            <span class="{{ $buttonDisabled }}">{{ __('Next') }} &rsaquo;</span>
        @endif

        @if ($currentPage < $lastPage)
            <a href="{{ $paginator->url($lastPage) }}" class="{{ $buttonInactive }}">{{ __('Last') }} &raquo;&raquo;</a>
        @else
            <span class="{{ $buttonDisabled }}">{{ __('Last') }} &raquo;&raquo;</span>
        @endif
    </nav>
</div>
