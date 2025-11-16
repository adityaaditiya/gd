<?php

namespace App\Support;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class LatestLimitedPaginator
{
    public const MAX_ITEMS = 100;
    public const PER_PAGE_OPTIONS = [10, 25, 50, 100];

    /**
     * Paginate the latest records from the given query by limiting the dataset before slicing.
     */
    public static function fromQuery(
        Builder $query,
        Request $request,
        int $defaultPerPage = self::PER_PAGE_OPTIONS[0],
        int $limit = self::MAX_ITEMS,
    ): LengthAwarePaginatorContract {
        $limited = $query->limit($limit)->get();

        return self::fromCollection($limited, $request, $defaultPerPage);
    }

    /**
     * Paginate an in-memory collection using the provided request context.
     */
    public static function fromCollection(
        Collection $collection,
        Request $request,
        int $defaultPerPage = self::PER_PAGE_OPTIONS[0],
    ): LengthAwarePaginatorContract {
        $perPage = self::resolvePerPage($request, $defaultPerPage);
        $currentPage = max((int) $request->input('page', 1), 1);

        $items = $collection
            ->slice(($currentPage - 1) * $perPage, $perPage)
            ->values();

        return new LengthAwarePaginator(
            $items,
            $collection->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ],
        );
    }

    public static function resolvePerPage(Request $request, int $defaultPerPage = self::PER_PAGE_OPTIONS[0]): int
    {
        $perPage = (int) $request->input('per_page', $defaultPerPage);

        if (! in_array($perPage, self::PER_PAGE_OPTIONS, true)) {
            return $defaultPerPage;
        }

        return $perPage;
    }
}
