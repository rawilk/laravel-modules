<?php

namespace Rawilk\LaravelModules;

use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;

abstract class Module extends ServiceProvider
{
    use Macroable;

    /**
     * The laravel application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The module name.
     *
     * @var
     */
    protected $name;

    /**
     * The module path.
     *
     * @var string
     */
    protected $path;

    /**
     * @var array of cached Json objects, keyed by filename
     */
    protected $moduleJson = [];

    /**
     * Create a new provider instance.
     *
     * @param Container $app
     * @param string $name
     * @param string $path
     */
    public function __construct(Container $app, $name, $path)
    {
        parent::__construct($app);

        $this->name = $name;
        $this->path = $path;
    }

    /**
     * Get laravel instance.
     *
     * @return \Illuminate\Contracts\Foundation\Application
     */
    public function getLaravel()
    {
        return $this->app;
    }

    /**
     * Get the module's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the module name as lower case.
     *
     * @return string
     */
    public function getLowerName()
    {
        return strtolower($this->name);
    }

    /**
     * Get the module name as studly case.
     *
     * @return string
     */
    public function getStudlyName()
    {
        return Str::studly($this->name);
    }

    /**
     * Get the module name as snake case.
     *
     * @return string
     */
    public function getSnakeName()
    {
        return Str::snake($this->name);
    }

    /**
     * Get the module's description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->get('description');
    }

    /**
     * Get the module's alias.
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->get('alias');
    }

    /**
     * Get the module's priority.
     *
     * @return string
     */
    public function getPriority()
    {
        return $this->get('priority');
    }

    /**
     * Get the module's requirements.
     *
     * @return array
     */
    public function getRequires()
    {
        return $this->get('requires');
    }

    /**
     * Get the module's path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the module's path.
     *
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        if (config('modules.register.translations', true)) {
            $this->registerTranslation();
        }

        if ($this->isLoadFilesOnBoot()) {
            $this->registerFiles();
        }

        $this->fireEvent('boot');
    }

    /**
     * Register the module's translations.
     *
     * @return void
     */
    protected function registerTranslation()
    {
        $lowerName = $this->getLowerName();

        $langPath = $this->getPath() . '/resources/lang';

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $lowerName);
        }
    }

    /**
     * Get json contents from the cache, setting as needed.
     *
     * @param string $file
     * @return Json|callable
     */
    public function json($file = null) : Json
    {
        if (is_null($file)) {
            $file = 'module.json';
        }

        return array_get($this->moduleJson, $file, function () use ($file) {
            return $this->moduleJson[$file] = new Json($this->getPath() . '/' . $file, $this->app['files']);
        });
    }

    /**
     * Get a value from the json file from the given key.
     *
     * @param string $key
     * @param null|mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->json()->get($key, $default);
    }

    /**
     * Get a value from the composer.json from the given key.
     *
     * @param string $key
     * @param null|mixed $default
     * @return mixed
     */
    public function getComposerAttr($key, $default = null)
    {
        return $this->json('composer.json')->get($key, $default);
    }

    /**
     * Register the module.
     */
    public function register()
    {
        $this->registerAliases();
        $this->registerProviders();

        if (! $this->isLoadFilesOnBoot()) {
            $this->registerFiles();
        }

        $this->fireEvent('register');
    }

    /**
     * Register the module event.
     *
     * @param string $event
     */
    protected function fireEvent($event)
    {
        $this->app['events']->dispatch(sprintf('modules.%s.' . $event, $this->getLowerName()), [$this]);
    }

    /**
     * Register the aliases from this module.
     */
    abstract public function registerAliases();

    /**
     * Register the service providers from this module.
     */
    abstract public function registerProviders();

    /**
     * Get the path to the cached *_module.php file.
     *
     * @return string
     */
    abstract public function getCachedServicesPath();

    /**
     * Register the files from this module.
     */
    protected function registerFiles()
    {
        foreach ($this->get('files', []) as $file) {
            include $this->path . '/' . $file;
        }
    }

    /**
     * Handle magic method __toString.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getStudlyName();
    }

    /**
     * Determine whether the given status is the same with the current module status.
     *
     * @param int $status
     * @return bool
     */
    public function isStatus($status) : bool
    {
        return $this->get('active', 0) === $status;
    }

    /**
     * Determine if the current module is activated.
     *
     * @return bool
     */
    public function enabled() : bool
    {
        return $this->isStatus(1);
    }

    /**
     *  Determine if the current module is disabled.
     *
     * @return bool
     */
    public function disabled() : bool
    {
        return ! $this->enabled();
    }

    /**
     * Set active state for current module.
     *
     * @param int $active
     * @return int
     */
    public function setActive($active)
    {
        return $this->json()->set('active', $active)->save();
    }

    /**
     * Disable the current module.
     */
    public function disable()
    {
        $this->fireEvent('disabling');

        $this->setActive(0);

        $this->fireEvent('disabled');
    }

    /**
     * Enable the current module.
     */
    public function enable()
    {
        $this->fireEvent('enabling');

        $this->setActive(1);

        $this->fireEvent('enabled');
    }

    /**
     * Delete the current module.
     *
     * @return bool
     */
    public function delete()
    {
        return $this->json()->getFilesystem()->deleteDirectory($this->getPath());
    }

    /**
     * Get extra path.
     *
     * @param string $path
     * @return string
     */
    public function getExtraPath(string $path) : string
    {
        return $this->getPath() . '/' . $path;
    }

    /**
     * Handle magic method __get.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Check if can load files of module on boot method.
     *
     * @return bool
     */
    protected function isLoadFilesOnBoot()
    {
        return config('modules.register.files', 'register') === 'boot';
    }
}
