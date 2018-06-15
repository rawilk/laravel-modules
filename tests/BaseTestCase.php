<?php

namespace Rawilk\LaravelModules\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Rawilk\LaravelModules\LaravelModulesServiceProvider;

class BaseTestCase extends OrchestraTestCase
{
	/**
	 * Setup the test environment.
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();
	}

	/**
	 * Reset the database.
	 */
	private function resetDatabase()
	{
		$this->artisan('migrate:reset', [
			'--database' => 'sqlite'
		]);
	}

	/**
	 * Get package providers.
	 *
	 * @param \Illuminate\Foundation\Application  $app
	 * @return array
	 */
	protected function getPackageProviders($app)
	{
		return [
			LaravelModulesServiceProvider::class
		];
	}

	/**
	 * Define environment setup.
	 *
	 * @param \Illuminate\Foundation\Application $app
	 * @return void
	 */
	protected function getEnvironmentSetUp($app)
	{
		$app['config']->set('database.default', 'sqlite');

		$app['config']->set('database.connections.sqlite', [
			'driver'   => 'sqlite',
			'database' => ':memory:',
			'prefix'   => '',
		]);

		$app['config']->set('modules.paths.modules', base_path('Modules'));

		$app['config']->set('modules.paths', [
			'modules'   => base_path('Modules'),
			'assets'    => public_path('modules'),
			'migration' => base_path('database/migrations'),
			'generator' => [
				'config'        => ['path' => 'config', 'generate' => true],
				'command'       => ['path' => 'Console', 'generate' => false],
				'migration'     => ['path' => 'database/migrations', 'generate' => true],
				'seeder'        => ['path' => 'database/seeds', 'generate' => true],
				'factory'       => ['path' => 'database/factories', 'generate' => false],
				'model'         => ['path' => 'Models', 'generate' => true],
				'controller'    => ['path' => 'Http/Controllers', 'generate' => true],
				'filter'        => ['path' => 'Http/Middleware', 'generate' => false],
				'request'       => ['path' => 'Http/Requests', 'generate' => true],
				'provider'      => ['path' => 'Providers', 'generate' => true],
				'assets'        => ['path' => 'resources/assets', 'generate' => true],
				'lang'          => ['path' => 'resources/lang', 'generate' => true],
				'views'         => ['path' => 'resources/views', 'generate' => true],
				'test'          => ['path' => 'Tests', 'generate' => true],
				'repository'    => ['path' => 'Repositories', 'generate' => false],
				'event'         => ['path' => 'Events', 'generate' => false],
				'listener'      => ['path' => 'Listeners', 'generate' => false],
				'policies'      => ['path' => 'Policies', 'generate' => false],
				'rules'         => ['path' => 'Rules', 'generate' => false],
				'jobs'          => ['path' => 'Jobs', 'generate' => false],
				'emails'        => ['path' => 'Mail', 'generate' => false],
				'notifications' => ['path' => 'Notifications', 'generate' => false],
				'resource'      => ['path' => 'Transformers', 'generate' => false],
			],
		]);
	}

	/**
	 * Set up the database.
	 */
	protected function setUpDatabase()
	{
		$this->resetDatabase();
	}
}
