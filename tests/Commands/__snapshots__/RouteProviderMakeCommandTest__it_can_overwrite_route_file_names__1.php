<?php return '<?php

namespace Modules\\Blog\\Providers;

use Illuminate\\Support\\Facades\\Route;
use Illuminate\\Foundation\\Support\\Providers\\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace is assumed when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = \'Modules\\Blog\\Http\\Controllers\';

    public function map(): void
    {
        $this->mapWebRoutes();
    }

    protected function mapWebRoutes(): void
    {
        Route::middleware(\'web\')
            ->namespace($this->moduleNamespace)
            ->group(__DIR__ . \'/../CustomPath/web.php\');
    }
}
';
