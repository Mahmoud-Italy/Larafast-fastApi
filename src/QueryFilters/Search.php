<?php

namespace Larafast\Fastapi\QueryFilters;

use Closure;
use Larafast\Fastapi\QueryFilters\Filter;

class Search extends Filter
{
    public function applyFilter($builder)
    {
        $search     = request($this->getFileterName());
        return $builder->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('id', $search);
    }
}
