<?php

namespace Rawilk\LaravelModules;

use Rawilk\LaravelModules\Contracts\Activator;
use Rawilk\LaravelModules\Contracts\Repository;
use Rawilk\LaravelModules\Laravel\LaravelFileRepository;
use Rawilk\LaravelModules\Support\Stub;

class LaravelModulesServiceProvider extends ModuleServiceProvider
{
    public function boot(): void
    {
        $this->registerNamespaces();
        $this->registerModules();
    }

    public function register(): void
    {
        $this->registerServices();
        $this->setupStubPath();
        $this->registerProviders();
    }

    public function setupStubPath(): void
    {
        Stub::setBasePath(__DIR__ . '/Commands/stubs');

        $this->app->booted(static function ($app) {
            /** @var \Rawilk\LaravelModules\Contracts\Repository $moduleRepository */
            $moduleRepository = $app[Repository::class];

            if ($moduleRepository->config('stubs.enabled')) {
                Stub::setBasePath($moduleRepository->config('stubs.path'));
            }
        });
    }

    protected function registerServices(): void
    {
        $this->app->singleton(Repository::class, static function ($app) {
            $path = $app['config']->get('modules.paths.modules');

            return new LaravelFileRepository($app, $path);
        });

        $this->app->singleton(Activator::class, static function ($app) {
            $activator = $app['config']->get('modules.activator');
            $class = $app['config']->get('modules.activators.' . $activator)['class'];

            return new $class($app);
        });

        $this->app->alias(Repository::class, 'modules');
    }
}
