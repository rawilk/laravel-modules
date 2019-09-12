<?php

namespace Rawilk\LaravelModules\Activators;

use Illuminate\Contracts\Container\Container;
use Rawilk\LaravelModules\Contracts\Activator;
use Rawilk\LaravelModules\Module;

class FileActivator implements Activator
{
    /** @var \Illuminate\Cache\CacheManager */
    private $cache;

    /** @var string */
    private $cacheKey;

    /** @var int */
    private $cacheLifetime;

    /** @var \Illuminate\Contracts\Config\Repository */
    private $config;

    /** @var \Illuminate\Filesystem\Filesystem */
    private $files;

    /** @var array */
    private $moduleStatuses;

    /** @var string */
    private $statusesFile;

    /**
     * @param \Illuminate\Contracts\Container\Container $app
     */
    public function __construct(Container $app)
    {
        $this->cache = $app['cache'];
        $this->files = $app['files'];
        $this->config = $app['config'];
        $this->statusesFile = $this->config('statuses-file');
        $this->cacheKey = $this->config('cache-key');
        $this->cacheLifetime = $this->config('cache-lifetime');
        $this->moduleStatuses = $this->getModulesStatuses();
    }

    public function delete(Module $module): void
    {
        if (! isset($this->moduleStatuses[$module->getName()])) {
            return;
        }

        unset($this->moduleStatuses[$module->getName()]);

        $this->writeJson();

        $this->flushCache();
    }

    public function disable(Module $module): void
    {
        $this->setActiveByName($module->getName(), false);
    }

    public function enable(Module $module): void
    {
        $this->setActiveByName($module->getName(), true);
    }

    public function getStatusesFilePath(): string
    {
        return $this->statusesFile;
    }

    public function hasStatus(Module $module, bool $status): bool
    {
        if (! isset($this->moduleStatuses[$module->getName()])) {
            return $status === false;
        }

        return $this->moduleStatuses[$module->getName()] === $status;
    }

    public function reset(): void
    {
        if ($this->files->exists($this->statusesFile)) {
            $this->files->delete($this->statusesFile);
        }

        $this->moduleStatuses = [];

        $this->flushCache();
    }

    public function setActive(Module $module, bool $active): void
    {
        $this->setActiveByName($module->getName(), $active);
    }

    public function setActiveByName(string $name, bool $active): void
    {
        $this->moduleStatuses[$name] = $active;

        $this->writeJson();

        $this->flushCache();
    }

    private function config(string $key, $default = null)
    {
        return $this->config->get("modules.activators.file.{$key}", $default);
    }

    private function flushCache(): void
    {
        $this->cache->forget($this->cacheKey);
    }

    private function getModulesStatuses(): array
    {
        if (! $this->config->get('modules.cache.enabled')) {
            return $this->readJson();
        }

        return $this->cache->remember($this->cacheKey, $this->cacheLifetime, function () {
            return $this->readJson();
        });
    }

    private function readJson(): array
    {
        if (! $this->files->exists($this->statusesFile)) {
            return [];
        }

        return json_decode($this->files->get($this->statusesFile), true);
    }

    private function writeJson(): void
    {
        $this->files->put($this->statusesFile, json_encode($this->moduleStatuses, JSON_PRETTY_PRINT));
    }
}
