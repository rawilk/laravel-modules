<?php

namespace Rawilk\LaravelModules;

use Illuminate\Support\ServiceProvider;
use Rawilk\LaravelModules\Contracts\Repository;
use Rawilk\LaravelModules\Providers\BootstrapServiceProvider;
use Rawilk\LaravelModules\Providers\ConsoleServiceProvider;
use Rawilk\LaravelModules\Providers\ContractServiceProvider;

abstract class ModuleServiceProvider extends ServiceProvider
{
    abstract protected function registerServices(): void;

    public function provides(): array
    {
        return [Repository::class, 'modules'];
    }

    protected function registerModules(): void
    {
        $this->app->register(BootstrapServiceProvider::class);
    }

    protected function registerNamespaces(): void
    {
        $configPath = __DIR__ . '/../config/config.php';

        $this->mergeConfigFrom($configPath, 'modules');
        $this->publishes([
            $configPath => config_path('modules.php')
        ], 'config');
    }

    protected function registerProviders(): void
    {
        $this->app->register(ConsoleServiceProvider::class);
        $this->app->register(ContractServiceProvider::class);
    }
}
