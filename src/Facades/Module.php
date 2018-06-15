<?php

namespace Rawilk\LaravelModules\Facades;

use Illuminate\Support\Facades\Facade;

class Module extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return 'modules';
    }
}
