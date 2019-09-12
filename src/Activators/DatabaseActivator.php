<?php

namespace Rawilk\LaravelModules\Activators;

use Illuminate\Contracts\Container\Container;
use Rawilk\LaravelModules\Contracts\Activator;
use Rawilk\LaravelModules\Contracts\ModuleModel;
use Rawilk\LaravelModules\Module;

class DatabaseActivator implements Activator
{
    /** @var \Illuminate\Cache\CacheManager */
    private $cache;

    /** @var string */
    private $cacheKey;

    /** @var int */
    private $cacheLifetime;

    /** @var \Illuminate\Contracts\Config\Repository */
    private $config;

    /** @var \Rawilk\LaravelModules\Contracts\ModuleModel */
    private $model;

    /**
     * @param \Illuminate\Contracts\Container\Container $app
     */
    public function __construct(Container $app)
    {
        $this->cache = $app['cache'];
        $this->config = $app['config'];
        $this->model = $app[ModuleModel::class];
        $this->cacheKey = $this->config('cache-key');
        $this->cacheLifetime = $this->config('cache-lifetime');
    }

    public function delete(Module $module): void
    {
        $this->flushCache($module->getName());
    }

    public function disable(Module $module): void
    {
        $this->setActiveByName($module->getName(), false);
    }

    public function enable(Module $module): void
    {
        $this->setActiveByName($module->getName(), true);
    }

    public function hasStatus(Module $module, bool $status): bool
    {
        return $this->cache->remember(
            $this->moduleCacheKey($module->getName()),
            $this->cacheLifetime,
            function () use ($module, $status) {
                $databaseModule = $this->model::findModule($module->getName());

                if (! $databaseModule) {
                    return $status === false;
                }

                return $databaseModule->hasStatus($status);
            }
        );
    }

    public function reset(): void
    {
        $this->model::allModules()
            ->each(function (ModuleModel $module) {
                $this->flushCache($module->getName());
            });
    }

    public function setActive(Module $module, bool $active): void
    {
        $this->setActiveByName($module->getName(), $active);
    }

    public function setActiveByName(string $name, bool $active): void
    {
        $method = $active === true ? 'enable' : 'disable';

        $this->model::$method($name);

        $this->flushCache($name);
    }

    private function config(string $key, $default = null)
    {
        return $this->config->get("modules.activators.database.{$key}", $default);
    }

    private function flushCache(string $name): void
    {
        $this->cache->forget($this->moduleCacheKey($name));
    }

    private function moduleCacheKey(string $name): string
    {
        return sprintf($this->cacheKey, $name);
    }
}
