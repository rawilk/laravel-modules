<?php

namespace Rawilk\LaravelModules\Traits;

trait CanClearModulesCache
{
    public function clearCache(): void
    {
        if (config('modules.cache.enabled')) {
            app('cache')->forget(config('modules.cache.key'));
        }
    }
}
