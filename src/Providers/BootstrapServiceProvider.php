<?php

namespace Rawilk\LaravelModules\Providers;

use Illuminate\Support\ServiceProvider;

class BootstrapServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['modules']->boot();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['modules']->register();
    }
}
