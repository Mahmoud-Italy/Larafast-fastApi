<?php

namespace Larafast\Fastapi;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Larafast\Fastapi\Skeleton\SkeletonClass
 */
class FastapiFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'fastApi';
    }
}
