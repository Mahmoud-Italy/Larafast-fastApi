<?php

namespace Larafast\Fastapi\Controller\Actions;

use Closure;
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
    public function ApiIndex(string $model, $callback, bool $getAll = false, array $allowedFilters = ['id'], array $defaultSort = ['id'], array $allowedSorts = ['id'])
    {
        $rows = QueryBuilder::for($model::query())
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
