<?php

namespace Rawilk\LaravelModules;

use Countable;
use Illuminate\Contracts\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use Rawilk\LaravelModules\Contracts\Repository;
use Rawilk\LaravelModules\Exceptions\InvalidAssetPath;
use Rawilk\LaravelModules\Exceptions\ModuleNotFound;
use Rawilk\LaravelModules\Process\Installer;
use Rawilk\LaravelModules\Process\Updater;
use Symfony\Component\Process\Process;

abstract class FileRepository implements Repository, Countable
{
    use Macroable;

    /** @var \Illuminate\Contracts\Foundation\Application */
    protected $app;

    /** @var \Illuminate\Cache\CacheManager */
    private $cache;

    /** @var \Illuminate\Contracts\Config\Repository */
    private $config;

    /** @var \Illuminate\Filesystem\Filesystem */
    private $files;

    /** @var bool */
    private $onlyCustomPaths = false;

    /** @var string */
    protected $path;

    /** @var array */
    protected $paths = [];

    /** @var string */
    protected $stubPath;

    /** @var \Illuminate\Contracts\Routing\UrlGenerator */
    private $url;

    /**
     * @param \Illuminate\Contracts\Container\Container $app
     * @param string|null $path
     */
    public function __construct(Container $app, ?string $path = null)
    {
        $this->app = $app;
        $this->path = $path;
        $this->url = $app['url'];
        $this->config = $app['config'];
        $this->files = $app['files'];
        $this->cache = $app['cache'];
    }

    abstract protected function createModule(...$args): Module;

    public function addLocation(string $path): self
    {
        $this->paths[] = $path;

        return $this;
    }

    public function all(): array
    {
        if (! $this->config('cache.enabled')) {
            return $this->scan();
        }

        return $this->formatCached($this->getCached());
    }

    public function allDisabled(): array
    {
        return $this->getByStatus(false);
    }

    public function allEnabled(): array
    {
        return $this->getByStatus(true);
    }

    public function asset(string $asset): string
    {
        if (! Str::contains($asset, ':')) {
            throw InvalidAssetPath::missingModuleName($asset);
        }

        [$name, $url] = explode(':', $asset);

        $baseUrl = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $this->getAssetsPath());

        $url = $this->url->asset("{$baseUrl}/{$name}/{$url}");

        return str_replace(['http://', 'https://'], '//', $url);
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

    public function collections(bool $status = true): Collection
    {
        return new Collection($this->getByStatus($status));
    }

    public function count(): int
    {
        return count($this->all());
    }

    public function delete(string $name): bool
    {
        return $this->findOrFail($name)->delete();
    }

    public function disable(string $name): void
    {
        $this->findOrFail($name)->disable();
    }

    public function enable(string $name): void
    {
        $this->findOrFail($name)->enable();
    }

    public function find(string $name): ?Module
    {
        /** @var \Rawilk\LaravelModules\Module $module */
        foreach ($this->all() as $module) {
            if ($module->getLowerName() === strtolower($name)) {
                return $module;
            }
        }

        return null;
    }

    public function findByAlias(string $alias): ?Module
    {
        /** @var \Rawilk\LaravelModules\Module $module */
        foreach ($this->all() as $module) {
            if ($module->getAlias() === $alias) {
                return $module;
            }
        }

        return null;
    }

    public function findOrFail(string $name): Module
    {
        $module = $this->find($name);

        if ($module !== null) {
            return $module;
        }

        throw ModuleNotFound::named($name);
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

    /**
     * Forget modules used in a cli session.
     */
    public function forgetUsed(): void
    {
        $usedStoragePath = $this->getUsedStoragePath();

        if ($this->getFiles()->exists($usedStoragePath)) {
            $this->getFiles()->delete($usedStoragePath);
        }
    }

    public function getAssetsPath(): string
    {
        return $this->config('paths.assets');
    }

    public function getByStatus(bool $active): array
    {
        $modules = [];

        /** @var \Rawilk\LaravelModules\Module $module */
        foreach ($this->all() as $name => $module) {
            if ($module->isStatus($active)) {
                $modules[$name] = $module;
            }
        }

        // Reset our variable
        $this->onlyCustomPaths = false;

        return $modules;
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
        try {
            return $this->findOrFail($name)->getPath() . '/';
        } catch (ModuleNotFound $e) {
            return $this->getPath() . '/' . Str::studly($name) . '/';
        }
    }

    public function getOrdered(string $direction = 'asc'): array
    {
        $modules = $this->allEnabled();

        uasort($modules, static function (Module $a, Module $b) use ($direction) {
            $aOrder = $a->get('order');
            $bOrder = $b->get('order');

            if ($aOrder === $bOrder) {
                return 0;
            }

            if ($direction === 'desc') {
                return $aOrder < $bOrder ? 1 : -1;
            }

            return $aOrder > $bOrder ? 1 : -1;
        });

        return $modules;
    }

    public function getPath(): string
    {
        return $this->path ?: $this->config('paths.modules', base_path('Modules'));
    }

    public function getPaths(): array
    {
        return $this->paths;
    }

    public function getScanPaths(): array
    {
        $paths = $this->paths;

        if (! $this->onlyCustomPaths) {
            $paths[] = $this->getPath();
        }

        if ($this->config('scan.enabled')) {
            $paths = array_merge($paths, $this->config('scan.paths'));
        }

        return array_map(static function ($path) {
            return Str::endsWith($path, '/*') ? $path : Str::finish($path, '/*');
        }, $paths);
    }

    public function getStubPath(): ?string
    {
        if ($this->stubPath !== null) {
            return $this->stubPath;
        }

        if ($this->config('stubs.enabled')) {
            return $this->config('stubs.path');
        }

        return $this->stubPath;
    }

    /**
     * Get the module being used in a cli session.
     *
     * @return string
     */
    public function getUsedNow(): string
    {
        return $this->findOrFail($this->getFiles()->get($this->getUsedStoragePath()));
    }

    public function getUsedStoragePath(): string
    {
        $directory = storage_path('app/modules');
        if (! $this->getFiles()->exists($directory)) {
            $this->getFiles()->makeDirectory($directory, 0777, true);
        }

        $path = storage_path('app/modules/modules.used');
        if (! $this->getFiles()->exists($path)) {
            $this->getFiles()->put($path, '');
        }

        return $path;
    }

    /**
     * Retrieve a sorted list of registered view partials to render for the given module.
     *
     * @param string $moduleName
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getViewPartials(string $moduleName): array
    {
        $modules = $this->getOrdered();

        $partials = [];

        /** @var \Rawilk\LaravelModules\Module $module */
        foreach ($modules as $module) {
            $path = $module->getExtraPath('config/module-views.php');

            if (! $this->files->exists($path)) {
                continue;
            }

            $moduleViews = $this->files->getRequire($path);
            $moduleViews = Arr::get($moduleViews, $moduleName, []);

            if (! empty($moduleViews)) {
                $partials = array_merge($partials, $moduleViews);
            }
        }

        uasort($partials, static function ($a, $b) {
            $aOrder = $a['order'] ?? 0;
            $bOrder = $b['order'] ?? 0;

            if ($aOrder === $bOrder) {
                return 0;
            }

            return $aOrder > $bOrder ? 1 : -1;
        });

        return $partials;
    }

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->all());
    }

    public function install(string $name, ?string $version = 'dev-master', ?string $type = 'composer', bool $subtree = false): Process
    {
        return (new Installer($name, $version, $type, $subtree))->run();
    }

    public function isDisabled(string $name): bool
    {
        return $this->findOrFail($name)->isDisabled();
    }

    public function isEnabled(string $name): bool
    {
        return $this->findOrFail($name)->isEnabled();
    }

    public function onlyCustomPaths(): self
    {
        $this->onlyCustomPaths = true;

        return $this;
    }

    public function register(): void
    {
        /** @var \Rawilk\LaravelModules\Module $module */
        foreach ($this->getOrdered() as $module) {
            $module->register();
        }
    }

    public function setStubPath(string $path): self
    {
        $this->stubPath = $path;

        return $this;
    }

    /**
     * Set a module being used in a cli session.
     *
     * @param string $name
     */
    public function setUsed(string $name): void
    {
        $module = $this->findOrFail($name);

        $this->getFiles()->put($this->getUsedStoragePath(), $module);
    }

    public function scan(): array
    {
        $paths = $this->getScanPaths();

        $modules = [];

        foreach ($paths as $key => $path) {
            $manifests = $this->getFiles()->glob("{$path}/module.json");

            is_array($manifests) || $manifests = [];

            foreach ($manifests as $manifest) {
                $name = Json::make($manifest)->get('name');

                $modules[$name] = $this->createModule($this->app, $name, dirname($manifest));
            }
        }

        return $modules;
    }

    public function toCollection(): Collection
    {
        return new Collection($this->scan());
    }

    public function update(string $name): void
    {
        with(new Updater($this))->update($name);
    }

    protected function formatCached(array $cached): array
    {
        $modules = [];

        foreach ($cached as $name => $module) {
            $path = $module['path'];

            $modules[$name] = $this->createModule($this->app, $name, $path);
        }

        return $modules;
    }
}
