<?php

namespace Rawilk\LaravelModules\Laravel;

use Rawilk\LaravelModules\FileRepository;
use Rawilk\LaravelModules\Module as BaseModule;

class LaravelFileRepository extends FileRepository
{
    protected function createModule(...$args): BaseModule
    {
        return new Module(...$args);
    }
}
