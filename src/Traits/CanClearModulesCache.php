<?php

namespace Rawilk\LaravelModules\Traits;

trait CanClearModulesCache
{
    /**
     * Clear the modules cache if enabled.
     */
    public function clearCache()
    {
        if (config('modules.cache.enabled') === true) {
            app('cache')->forget(config('modules.cache.key'));
        }
    }
}
