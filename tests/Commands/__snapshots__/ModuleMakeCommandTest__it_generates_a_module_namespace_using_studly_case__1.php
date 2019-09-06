<?php return '<?php

namespace Modules\\ModuleName\\Providers;

use Config;
use Illuminate\\Support\\ServiceProvider;
use Illuminate\\Database\\Eloquent\\Factory;

class ModuleNameServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . \'/../database/migrations\');
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    public function provides(): array
    {
        return [];
    }

    private function registerConfig(): void
    {
        $this->publishes([
            __DIR__ . \'/../config/config.php\' => config_path(\'modulename.php\')
        ], \'config\');

        $this->mergeConfigFrom(
            __DIR__ . \'/../config/config.php\', \'modulename\'
        );
    }

    private function registerFactories(): void
    {
        if (! $this->app->environment(\'production\') && $this->app->runningInConsole()) {
            app(Factory::class)->load(__DIR__ . \'/../database/factories\');
        }
    }

    private function registerTranslations(): void
    {
        $langPath = resource_path(\'lang/modules/modulename\');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, \'modulename\');
        } else {
            $this->loadTranslationsFrom(__DIR__  .\'/../resources/lang\', \'modulename\');
        }
    }

    private function registerViews(): void
    {
        $viewPath = resource_path(\'views/modules/modulename\');

        $sourcePath = __DIR__ . \'/../resources/views\';

        $this->publishes([
            $sourcePath => $viewPath
        ], \'views\');

        $this->loadViewsFrom(array_merge(array_map(static function ($path) {
            return "{$path}/modules/modulename";
        }, Config::get(\'view.paths\')), [$sourcePath]), \'modulename\');
    }
}
';
