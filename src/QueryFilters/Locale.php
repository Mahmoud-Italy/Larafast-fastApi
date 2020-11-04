<?php

namespace Larafast\Fastapi\QueryFilters;

use Closure;

class Locale extends Filter
{
    public function applyFilter($builder)
    {
        app()->setLocale(request($this->getFileterName()));
    }
}
