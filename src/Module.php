<?php

namespace Rawilk\LaravelModules;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use Rawilk\LaravelModules\Contracts\Activator;

abstract class Module
{
    use Macroable;

    /** @var \Rawilk\LaravelModules\Contracts\Activator */
    private $activator;

    /** @var \Illuminate\Contracts\Foundation\Application */
    protected $app;

    /** @var \Illuminate\Cache\CacheManager */
    private $cache;

    /** @var \Illuminate\Filesystem\Filesystem */
    private $files;

    /** @var array */
    protected $moduleJson = [];

    /** @var string */
    protected $name;

    /** @var string */
    protected $path;

    /** @var \Illuminate\Translation\Translator */
    private $translator;

    /**
     * @param \Illuminate\Contracts\Container\Container $app
     * @param string $name
     * @param string|null $path
     */
    public function __construct(Container $app, string $name, ?string $path)
    {
        $this->name = $name;
        $this->path = $path;
        $this->cache = $app['cache'];
        $this->files = $app['files'];
        $this->translator = $app['translator'];
        $this->activator = $app[Activator::class];
        $this->app = $app;
    }

    /**
     * Get the path to the cached *_module.php file.
     *
     * @return string
     */
    abstract public function getCachedServicesPath(): string;

    abstract public function registerAliases(): void;

    abstract public function registerProviders(): void;

    public function boot(): void
    {
        if (config('modules.register.translations', true)) {
            $this->registerTranslation();
        }

        if ($this->isLoadFilesOnBoot()) {
            $this->registerFiles();
        }

        $this->fireEvent('boot');
    }

    public function delete(): bool
    {
        $this->activator->delete($this);

        return $this->json()->getFilesystem()->deleteDirectory($this->getPath());
    }

    public function disable(): void
    {
        $this->fireEvent('disabling');

        $this->activator->disable($this);
        $this->flushCache();

        $this->fireEvent('disabled');
    }

    public function enable(): void
    {
        $this->fireEvent('enabling');

        $this->activator->enable($this);
        $this->flushCache();

        $this->fireEvent('enabled');
    }

    public function get(string $key, $default = null)
    {
        return $this->json()->get($key, $default);
    }

    public function getAlias(): string
    {
        return $this->get('alias');
    }

    public function getAssets(): array
    {
        try {
            return $this->json('assets.json')->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getAssetAttr(string $key, $default = null)
    {
        try {
            return $this->json('assets.json')->get($key, $default);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getComposerAttr(string $key, $default = null)
    {
        return $this->json('composer.json')->get($key, $default);
    }

    public function getDescription(): string
    {
        return $this->get('description');
    }

    public function getExtraPath(string $path): string
    {
        return $this->getPath() . '/' . $path;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLowerName(): string
    {
        return strtolower($this->name);
    }

    public function getPriority(): string
    {
        return $this->get('priority');
    }

    public function getRequires(): array
    {
        return $this->get('requires');
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getSnakeName(): string
    {
        return Str::snake($this->name);
    }

    public function getStudlyName(): string
    {
        return Str::studly($this->name);
    }

    public function isDisabled(): bool
    {
        return $this->activator->hasStatus($this, false);
    }

    public function isEnabled(): bool
    {
        return $this->activator->hasStatus($this, true);
    }

    public function isStatus(bool $status): bool
    {
        return $this->activator->hasStatus($this, $status);
    }

    public function json(?string $file = null): Json
    {
        if ($file === null) {
            $file = 'module.json';
        }

        return Arr::get($this->moduleJson, $file, function () use ($file) {
            return $this->moduleJson[$file] = new Json($this->getPath() . '/' . $file, $this->files);
        });
    }

    public function register(): void
    {
        $this->registerAliases();

        $this->registerProviders();

        if (! $this->isLoadFilesOnBoot()) {
            $this->registerFiles();
        }

        $this->fireEvent('register');
    }

    public function setActive(bool $active): bool
    {
        return $this->activator->setActive($this, $active);
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    protected function fireEvent(string $event): void
    {
        $this->app['events']->dispatch(sprintf('modules.%s.' . $event, $this->getLowerName()), [$this]);
    }

    protected function isLoadFilesOnBoot(): bool
    {
        return config('modules.register.files', 'register') === 'boot';
    }

    protected function registerFiles(): void
    {
        foreach ($this->get('files', []) as $file) {
            include $this->path . '/' . $file;
        }
    }

    protected function registerTranslation(): void
    {
        $lowerName = $this->getLowerName();

        $langPath = $this->getPath() . '/resources/lang';

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $lowerName);
        }
    }

    private function flushCache(): void
    {
        if (config('modules.cache.enabled')) {
            $this->cache->store()->flush();
        }
    }

    private function loadTranslationsFrom(string $path, string $namespace): void
    {
        $this->translator->addNamespace($namespace, $path);
    }

    public function __toString()
    {
        return $this->getStudlyName();
    }
}
