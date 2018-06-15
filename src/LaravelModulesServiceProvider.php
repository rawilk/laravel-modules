<?php

namespace Rawilk\LaravelModules;

use Rawilk\LaravelModules\Support\Stub;

class LaravelModulesServiceProvider extends ModulesServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerNamespaces();
		$this->registerModules();
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerServices();
		$this->setupStubPath();
		$this->registerProviders();
	}

	/**
	 * Setup the stub path.
	 */
	protected function setupStubPath()
	{
		Stub::setBasePath(__DIR__ . '/Commands/stubs');

		$this->app->booted(function ($app) {
			if ($app['modules']->config('stubs.enabled')) {
				Stub::setBasePath($app['modules']->config('stubs.path'));
			}
		});
	}

	/**
	 * Register the service provider.
	 */
	protected function registerServices()
	{
		$this->app->singleton('modules', function ($app) {
			$path = $app['config']->get('modules.paths.modules');

			return new \Rawilk\LaravelModules\Laravel\Repository($app, $path);
		});
	}
}