<?php

namespace Larafast\Fastapi\QueryFilters;

use Closure;
use Illuminate\Support\Str;

abstract class Filter
{
    protected abstract function applyFilter($builder);

    public function handle($request, Closure $next)
    {
        $builder = $next($request);
        if(! request()->has($this->getFileterName()))
        {
            return $builder;
        }

        return $this->applyFilter($builder);
    }

    protected function getFileterName()
    {
        return Str::snake(class_basename($this));
    }
}
