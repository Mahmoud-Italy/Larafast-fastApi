<?php

namespace Larafast\Fastapi\Controller\Actions;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;

trait Index
{
    /**
     * @param string $model
     * @param string $collection
     * @param bool $getAll
     * @param array|string[] $allowedFilters
     * @param array|string[] $defaultSort
     * @param array|string[] $allowedSorts
     * @return JsonResponse
     */
    public function ApiIndex(Builder $model, $callback, bool $getAll = false, array $allowedFilters = [], array $defaultSort = [], array $allowedSorts = [])
    {
        $rows = QueryBuilder::for($model)
            ->allowedFilters($allowedFilters)
            ->defaultSort($defaultSort)
            ->allowedSorts($allowedSorts);

        $data = ($getAll) ? $rows->get() : $rows->paginate(request()->get('perPage', 15));

        if ($callback instanceof Closure) {
            return $callback($data);
        }

        return response()->json(['data'=>$data], 200);
    }
}
