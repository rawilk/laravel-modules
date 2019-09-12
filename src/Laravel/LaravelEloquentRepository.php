<?php

namespace Rawilk\LaravelModules\Laravel;

use Rawilk\LaravelModules\EloquentRepository;
use Rawilk\LaravelModules\Module as BaseModule;

class LaravelEloquentRepository extends EloquentRepository
{
    protected function createModule(...$args): BaseModule
    {
        return new Module(...$args);
    }
}
