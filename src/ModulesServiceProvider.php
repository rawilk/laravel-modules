<?php

namespace Rawilk\LaravelModules;

use Illuminate\Support\ServiceProvider;
use Rawilk\LaravelModules\Providers\BootstrapServiceProvider;
use Rawilk\LaravelModules\Providers\ConsoleServiceProvider;
use Rawilk\LaravelModules\Providers\ContractServiceProvider;

abstract class ModulesServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the modules.
	 */
	protected function registerModules()
	{
		$this->app->register(BootstrapServiceProvider::class);
	}

	/**
	 * Register package namespaces.
	 */
	protected function registerNamespaces()
	{
		$configPath = __DIR__ . '/../config/config.php';

		$this->mergeConfigFrom($configPath, 'modules');
		$this->publishes([
			$configPath => config_path('modules.php')
		], 'config');
	}

	/**
	 * Register the service provider.
	 */
	abstract protected function registerServices();

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['modules'];
	}

	/**
	 * Register providers.
	 */
	protected function registerProviders()
	{
		$this->app->register(ConsoleServiceProvider::class);
		$this->app->register(ContractServiceProvider::class);
	}
}
