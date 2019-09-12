<?php return '<?php

namespace Modules\\Blog\\Providers;

use Config;
use Illuminate\\Support\\ServiceProvider;
use Illuminate\\Database\\Eloquent\\Factory;

class BlogServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . \'/../migrations\');
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
            __DIR__ . \'/../config/config.php\' => config_path(\'blog.php\')
        ], \'config\');

        $this->mergeConfigFrom(
            __DIR__ . \'/../config/config.php\', \'blog\'
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
        $langPath = resource_path(\'lang/modules/blog\');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, \'blog\');
        } else {
            $this->loadTranslationsFrom(__DIR__  .\'/../resources/lang\', \'blog\');
        }
    }

    private function registerViews(): void
    {
        $viewPath = resource_path(\'views/modules/blog\');

        $sourcePath = __DIR__ . \'/../resources/views\';

        $this->publishes([
            $sourcePath => $viewPath
        ], \'views\');

        $this->loadViewsFrom(array_merge(array_map(static function ($path) {
            return "{$path}/modules/blog";
        }, Config::get(\'view.paths\')), [$sourcePath]), \'blog\');
    }
}
';
