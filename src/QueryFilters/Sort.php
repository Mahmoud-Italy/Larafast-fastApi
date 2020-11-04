<?php

namespace Larafast\Fastapi\QueryFilters;

use Closure;
use Larafast\Fastapi\QueryFilters\Filter;

class Sort extends Filter
{
    public function applyFilter($builder)
    {
        $sort = in_array(strtolower(request($this->getFileterName())), ['asc', 'desc']) ? request($this->getFileterName()) : 'desc';
        return $builder->orderBy('id', $sort);
    }
}
