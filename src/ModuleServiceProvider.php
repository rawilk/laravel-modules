<?php

namespace Rawilk\LaravelModules;

use Illuminate\Support\ServiceProvider;
use Rawilk\LaravelModules\Contracts\ModuleModel;
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

    protected function publishMigrations(): void
    {
        if (! class_exists('CreateModulesTable')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/create_modules_table.php.stub' => database_path('migrations/' . date('Y_m_d_His') . '_create_modules_table.php')
            ], 'migrations');
        }
    }

    protected function registerModules(): void
    {
        $this->app->register(BootstrapServiceProvider::class);
    }

    protected function registerModuleModel(): void
    {
        $config = $this->app->config['modules.models'];

        $this->app->bind(ModuleModel::class, $config['module']);
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
