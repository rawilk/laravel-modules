<?php

namespace Rawilk\LaravelModules\Generators;

use Illuminate\Config\Repository as Config;
use Illuminate\Console\Command as Console;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Rawilk\LaravelModules\Repository;
use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;

class ModuleGenerator extends Generator
{
	/**
	 * The name of the module that will be created.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * The laravel config instance.
	 *
	 * @var \Illuminate\Config\Repository
	 */
	protected $config;

	/**
	 * The laravel filesystem instance.
	 *
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $filesystem;

	/**
	 * The laravel console instance.
	 *
	 * @var \Illuminate\Console\Command
	 */
	protected $console;

	/**
	 * The module instance.
	 *
	 * @var \Rawilk\LaravelModules\Module
	 */
	protected $module;

	/**
	 * Force status.
	 *
	 * @var bool
	 */
	protected $force = false;

	/**
	 * Generate a plain module.
	 *
	 * @var bool
	 */
	protected $plain = false;

	/**
	 * Create a new class instance.
	 *
	 * @param string $name
	 * @param \Rawilk\LaravelModules\Repository $module
	 * @param \Illuminate\Config\Repository $config
	 * @param \Illuminate\Filesystem\Filesystem $filesystem
	 * @param \Illuminate\Console\Command $console
	 */
	public function __construct(
		$name,
		Repository $module = null,
		Config $config = null,
		Filesystem $filesystem = null,
		Console $console = null
	) {
		$this->name = $name;
		$this->config = $config;
		$this->filesystem = $filesystem;
		$this->console = $console;
		$this->module = $module;
	}

	/**
	 * Set plain flag.
	 *
	 * @param bool $plain
	 * @return $this
	 */
	public function setPlain($plain)
	{
		$this->plain = $plain;

		return $this;
	}

	/**
	 * Get the name of module will created. By default in studly case.
	 *
	 * @return string
	 */
	public function getName()
	{
		return Str::studly($this->name);
	}

	/**
	 * Get the laravel config instance.
	 *
	 * @return \Illuminate\Config\Repository
	 */
	public function getConfig()
	{
		return $this->config;
	}

	/**
	 * Set the laravel config instance.
	 *
	 * @param \Illuminate\Config\Repository $config
	 * @return $this
	 */
	public function setConfig($config)
	{
		$this->config = $config;

		return $this;
	}

	/**
	 * Get the laravel filesystem instance.
	 *
	 * @return \Illuminate\Filesystem\Filesystem
	 */
	public function getFilesystem()
	{
		return $this->filesystem;
	}

	/**
	 * Set the laravel filesystem instance.
	 *
	 * @param \Illuminate\Filesystem\Filesystem $filesystem
	 * @return $this
	 */
	public function setFilesystem($filesystem)
	{
		$this->filesystem = $filesystem;

		return $this;
	}

	/**
	 * Get the laravel console instance.
	 *
	 * @return \Illuminate\Console\Command
	 */
	public function getConsole()
	{
		return $this->console;
	}

	/**
	 * Set the laravel console instance.
	 *
	 * @param \Illuminate\Console\Command $console
	 * @return $this
	 */
	public function setConsole($console)
	{
		$this->console = $console;

		return $this;
	}

	/**
	 * Get the module instance.
	 *
	 * @return \Rawilk\LaravelModules\Module
	 */
	public function getModule()
	{
		return $this->module;
	}

	/**
	 * Set the module instance.
	 *
	 * @param \Rawilk\LaravelModules\Module $module
	 * @return $this
	 */
	public function setModule($module)
	{
		$this->module = $module;

		return $this;
	}

	/**
	 * Get the list of folders that will be created.
	 *
	 * @return array
	 */
	public function getFolders()
	{
		return $this->module->config('paths.generator');
	}

	/**
	 * Get the list of files that will be created.
	 *
	 * @return array
	 */
	public function getFiles()
	{
		return $this->module->config('stubs.files');
	}

	/**
	 * Set force status.
	 *
	 * @param bool|int $force
	 * @return $this
	 */
	public function setForce($force)
	{
		$this->force = $force;

		return $this;
	}

	/**
	 * Generate the module.
	 */
	public function generate()
	{
		$name = $this->getName();

		if ($this->module->has($name)) {
			if ($this->force) {
				$this->module->delete($name);
			} else {
				$this->console->error("Module [{$name}] already exists!");

				return;
			}
		}

		$this->generateFolders();
		$this->generateModuleJsonFile();

		if (! $this->plain) {
			$this->generateFiles();
			$this->generateResources();
		} else {
			$this->cleanModuleJsonFile();
		}

		$this->console->info("Module [{$name}] was created successfully.");
	}

	/**
	 * Generate the folders.
	 */
	public function generateFolders()
	{
		foreach ($this->getFolders() as $key => $folder) {
			$folder = GenerateConfigReader::read($key);

			if (! $folder->generate()) {
				continue;
			}

			$path = $this->module->getModulePath($this->getName()) . '/' . $folder->getPath();

			if (! $this->filesystem->isDirectory($path)) {
				$this->filesystem->makeDirectory($path, 0755, true);
			}

			if (config('modules.stubs.gitkeep')) {
				$this->generateGitKeep($path);
			}
		}
	}

	/**
	 * Generate git keep to the given path.
	 *
	 * @param string $path
	 */
	public function generateGitKeep($path)
	{
		$this->filesystem->put($path . '/.gitkeep', '');
	}

	/**
	 * Generate the files.
	 */
	public function generateFiles()
	{
		foreach ($this->getFiles() as $stub => $file) {
			$path = $this->module->getModulePath($this->getName()) . $file;

			if (! $this->filesystem->isDirectory($dir = dirname($path))) {
				$this->filesystem->makeDirectory($dir, 0775, true);
			}

			$this->filesystem->put($path, $this->getStubContents($stub));

			$this->console->info("Created: {$path}");
		}
	}

	/**
	 * Generate some resources.
	 */
	public function generateResources()
	{
		$this->console->call('module:make-seed', [
			'name'     => $this->getName(),
			'module'   => $this->getName(),
			'--master' => true,
		]);

		$this->console->call('module:make-provider', [
			'name'     => $this->getName() . 'ServiceProvider',
			'module'   => $this->getName(),
			'--master' => true,
		]);
	}

	/**
	 * Get the contents of the stub file by given stub name.
	 *
	 * @param string $stub
	 * @return string
	 */
	protected function getStubContents($stub)
	{
		return (new Stub(
			'/' . $stub . '.stub',
			$this->getReplacement($stub)
		))->render();
	}

	/**
	 * Get the list of the replacements.
	 *
	 * @return array
	 */
	public function getReplacements()
	{
		return $this->module->config('stubs.replacements');
	}

	/**
	 * Get replacements for the given stub.
	 *
	 * @param string $stub
	 * @return array
	 */
	protected function getReplacement($stub)
	{
		$replacements = $this->module->config('stubs.replacements');

		if (! isset($replacements[$stub])) {
			return [];
		}

		$keys = $replacements[$stub];

		$replaces = [];

		foreach ($keys as $key) {
			if (method_exists($this, $method = 'get' . ucfirst(studly_case(strtolower($key))) . 'Replacement')) {
				$replaces[$key] = $this->$method();
			} else {
				$replaces[$key] = null;
			}
		}

		return $replaces;
	}

	/**
	 * Generate the module.json file
	 */
	private function generateModuleJsonFile()
	{
		$path = $this->module->getModulePath($this->getName()) . 'module.json';

		if (! $this->filesystem->isDirectory($dir = dirname($path))) {
			$this->filesystem->makeDirectory($dir, 0775, true);
		}

		$this->filesystem->put($path, $this->getStubContents('json'));

		$this->console->info("Created: {$path}");
	}

	/**
	 * Remove the default service provider that was added in the module.json file
	 * This is needed when a --plain module was created
	 */
	private function cleanModuleJsonFile()
	{
		$path = $this->module->getModulePath($this->getName()) . 'module.json';

		$content = $this->filesystem->get($path);

		$namespace = $this->getModuleNamespaceReplacement();

		$studlyName = $this->getStudlyNameReplacement();

		$provider = '"' . $namespace . '\\\\' . $studlyName . '\\\\Providers\\\\' . $studlyName . 'ServiceProvider"';

		$content = str_replace($provider, '', $content);

		$this->filesystem->put($path, $content);
	}

	/**
	 * Get the module name in lower case.
	 *
	 * @return string
	 */
	protected function getLowerNameReplacement()
	{
		return strtolower($this->getName());
	}

	/**
	 * Get the module name in studly case.
	 *
	 * @return string
	 */
	protected function getStudlyNameReplacement()
	{
		return $this->getName();
	}

	/**
	 * Get replacement for $VENDOR$.
	 *
	 * @return string
	 */
	protected function getVendorReplacement()
	{
		return $this->module->config('composer.vendor');
	}

	/**
	 * Get replacement for $MODULE_NAMESPACE$.
	 *
	 * @return string
	 */
	protected function getModuleNamespaceReplacement()
	{
		return str_replace('\\', '\\\\', $this->module->config('namespace'));
	}

	/**
	 * Get replacement for $AUTHOR_NAME$.
	 *
	 * @return string
	 */
	protected function getAuthorNameReplacement()
	{
		return $this->module->config('composer.author.name');
	}

	/**
	 * Get replacement for $AUTHOR_EMAIL$.
	 *
	 * @return string
	 */
	protected function getAuthorEmailReplacement()
	{
		return $this->module->config('composer.author.email');
	}

	protected function getRoutesLocationReplacement()
	{
		return '/' . $this->module->config('stubs.files.routes');
	}
}
