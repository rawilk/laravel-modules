<?php

namespace Rawilk\LaravelModules;

use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Traits\Macroable;
use Rawilk\LaravelModules\Contracts\ModuleModel;
use Rawilk\LaravelModules\Contracts\Repository;
use Rawilk\LaravelModules\Exceptions\ModuleNotFound;

abstract class EloquentRepository implements Repository
{
    use Macroable;

    /** @var \Illuminate\Contracts\Container\Container */
    protected $app;

    /** @var \Illuminate\Contracts\Cache\Repository */
    private $cache;

    /** @var \Illuminate\Contracts\Config\Repository */
    private $config;

    /** @var \Illuminate\Filesystem\Filesystem */
    private $files;

    /** @var \Rawilk\LaravelModules\Contracts\ModuleModel */
    protected $model;

    /**
     * @param \Illuminate\Contracts\Container\Container $app
     * @param \Rawilk\LaravelModules\Contracts\ModuleModel $model
     */
    public function __construct(Container $app, ModuleModel $model)
    {
        $this->app = $app;
        $this->model = $model;
        $this->cache = $app['cache'];
        $this->config = $app['config'];
        $this->files = $app['files'];
    }

    abstract protected function createModule(...$args): Module;

    public function all(array $columns = ['*']): array
    {
        return $this->convertToCollection($this->model::allModules($columns))->toArray();
    }

    public function allDisabled(array $columns = ['*']): array
    {
        return $this->convertToCollection($this->model::allDisabled($columns))->toArray();
    }

    public function allEnabled(array $columns = ['*']): array
    {
        return $this->convertToCollection($this->model::allEnabled($columns))->toArray();
    }

    public function assetPath(string $name): string
    {
        return $this->config('paths.assets') . '/' . $name;
    }

    public function boot(): void
    {
        /** @var \Rawilk\LaravelModules\Module $module */
        foreach ($this->getOrdered() as $module) {
            $module->boot();
        }
    }

    public function config(string $key, $default = null)
    {
        return $this->config->get("modules.{$key}", $default);
    }

    public function count(): int
    {
        return $this->model::getCount();
    }

    public function delete(string $name): bool
    {
        return $this->findOrFail($name)->delete();
    }

    public function exists(string $name): bool
    {
        return $this->model::moduleExists($name);
    }

    public function find(string $name): ?Module
    {
        /** @var \Rawilk\LaravelModules\Models\Module $module */
        $module = $this->model::findModule($name);

        if ($module === null) {
            return null;
        }

        return $this->createModule($this->app, $module->name, $module->path);
    }

    public function findByAlias(string $alias): ?Module
    {
        /** @var \Rawilk\LaravelModules\Models\Module $module */
        $module = $this->model::findByAlias($alias);

        if ($module === null) {
            return null;
        }

        return $this->createModule($this->app, $module->name, $module->path);
    }

    public function findOrFail(string $name): Module
    {
        $module = $this->find($name);

        if ($module === null) {
            throw ModuleNotFound::named($name);
        }

        return $module;
    }

    public function findRequirements(string $name): array
    {
        $requirements = [];

        $module = $this->findOrFail($name);

        foreach ($module->getRequires() as $requirementName) {
            $requirements[] = $this->findByAlias($requirementName);
        }

        return $requirements;
    }

    public function getByStatus(bool $active, array $columns = ['*']): array
    {
        return $this->convertToCollection($this->model::getByStatus($active, $columns))->toArray();
    }

    public function getCached(): array
    {
        return $this->cache->remember($this->config('cache.key'), $this->config('cache.lifetime'), function () {
            return $this->toCollection()->toArray();
        });
    }

    public function getFiles(): Filesystem
    {
        return $this->files;
    }

    public function getModulePath(string $name): string
    {
        $module = $this->findOrFail($name);

        return $module->getPath();
    }

    public function getOrdered(string $direction = 'asc', array $columns = ['*']): array
    {
        return $this->convertToCollection($this->model::getOrdered($direction, $columns))->toArray();
    }

    public function getPath(): string
    {

    }

    public function getScanPaths(): array
    {
        return [];
    }

    public function isDisabled(string $name): bool
    {
        return $this->findOrFail($name)->isDisabled();
    }

    public function isEnabled(string $name): bool
    {
        return $this->findOrFail($name)->isEnabled();
    }

    public function register(): void
    {
        /** @var \Rawilk\LaravelModules\Module $module */
        foreach ($this->getOrdered() as $module) {
            $module->register();
        }
    }

    public function scan(): array
    {
        return $this->toCollection()->toArray();
    }

    public function toCollection(array $columns = ['*']): Collection
    {
        return $this->convertToCollection($this->model::allModules($columns));
    }

    private function convertToCollection(EloquentCollection $eloquentCollection): Collection
    {
        $collection = new Collection;

        $eloquentCollection->map(function ($module) use ($collection) {
            /** @var \Rawilk\LaravelModules\Models\Module $module */
            $collection->push($this->createModule($this->app, $module->name, $module->path));
        });

        return $collection;
    }
}
