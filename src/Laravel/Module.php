<?php

namespace Rawilk\LaravelModules\Laravel;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\ProviderRepository;
use Illuminate\Support\Str;
use Rawilk\LaravelModules\Module as BaseModule;

class Module extends BaseModule
{
    /**
     * Register the aliases from this module.
     */
    public function registerAliases()
    {
        $loader = AliasLoader::getInstance();

        foreach ($this->get('aliases', []) as $name => $class) {
            $loader->alias($name, $class);
        }
    }

    /**
     * Register the service providers from this module.
     */
    public function registerProviders()
    {
        (new ProviderRepository($this->app, new Filesystem(), $this->getCachedServicesPath()))
            ->load($this->get('providers', []));
    }

    /**
     * Get the path to the cached *_module.php file.
     *
     * @return string
     */
    public function getCachedServicesPath()
    {
        return Str::replaceLast(
            'services.php', $this->getSnakeName() . '_module.php',
            $this->app->getCachedServicesPath()
        );
    }
}
