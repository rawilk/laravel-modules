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

        $this->app['files']->deleteDirectory(base_path('Modules'));
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
            'modules'   => base_path('modules'),
            'assets'    => public_path('modules'),
            'migration' => base_path('database/migrations'),
            'generator' => [
                'assets'        => ['path' => 'assets', 'generate' => true],
                'config'        => ['path' => 'config', 'generate' => true],
                'command'       => ['path' => 'Console', 'generate' => true],
                'event'         => ['path' => 'Events', 'generate' => true],
                'listener'      => ['path' => 'Listeners', 'generate' => true],
                'migration'     => ['path' => 'database/migrations', 'generate' => true],
                'factory'       => ['path' => 'database/factories', 'generate' => true],
                'model'         => ['path' => 'Models', 'generate' => true],
                'repository'    => ['path' => 'Repositories', 'generate' => true],
                'seeder'        => ['path' => 'database/seeders', 'generate' => true],
                'controller'    => ['path' => 'Http/Controllers', 'generate' => true],
                'filter'        => ['path' => 'Http/Middleware', 'generate' => true],
                'request'       => ['path' => 'Http/Requests', 'generate' => true],
                'provider'      => ['path' => 'Providers', 'generate' => true],
                'lang'          => ['path' => 'resources/lang', 'generate' => true],
                'views'         => ['path' => 'resources/views', 'generate' => true],
                'policies'      => ['path' => 'Policies', 'generate' => true],
                'rules'         => ['path' => 'Rules', 'generate' => true],
                'test'          => ['path' => 'Tests', 'generate' => true],
                'jobs'          => ['path' => 'Jobs', 'generate' => true],
                'emails'        => ['path' => 'Mail', 'generate' => true],
                'notifications' => ['path' => 'Notifications', 'generate' => true],
                'resource'      => ['path' => 'Transformers', 'generate' => true],
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
