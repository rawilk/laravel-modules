<?php

namespace Rawilk\LaravelModules\Providers;

use Illuminate\Support\ServiceProvider;
use Rawilk\LaravelModules\Contracts\Repository;

class BootstrapServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app[Repository::class]->boot();
    }

    public function register(): void
    {
        $this->app[Repository::class]->register();
    }
}
