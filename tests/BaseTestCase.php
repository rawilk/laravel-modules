<?php

namespace Rawilk\LaravelModules\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Rawilk\LaravelModules\LaravelModulesServiceProvider;

abstract class BaseTestCase extends OrchestraTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (method_exists($this, 'withoutMockingConsoleOutput')) {
            $this->withoutMockingConsoleOutput();
        }
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => ''
        ]);

        $app['config']->set('modules.paths', [
            'modules' => base_path('Modules'),
            'assets' => public_path('modules'),
            'migration' => base_path('database/migrations'),
            'generator' => [
                'assets'       => ['path' => 'resources/assets', 'generate' => true],
                'command'      => ['path' => 'Console', 'generate' => true],
                'config'       => ['path' => 'config', 'generate' => true],
                'controller'   => ['path' => 'Http/Controllers', 'generate' => true],
                'emails'       => ['path' => 'Mail', 'generate' => true],
                'event'        => ['path' => 'Events', 'generate' => true],
                'factory'      => ['path' => 'database/factories', 'generate' => true],
                'jobs'         => ['path' => 'Jobs', 'generate' => true],
                'lang'         => ['path' => 'resources/lang', 'generate' => true],
                'listener'     => ['path' => 'Listeners', 'generate' => true],
                'middleware'   => ['path' => 'Http/Middleware', 'generate' => true],
                'migration'    => ['path' => 'database/migrations', 'generate' => true],
                'model'        => ['path' => 'Models', 'generate' => true],
                'policies'     => ['path' => 'Policies', 'generate' => true],
                'provider'     => ['path' => 'Providers', 'generate' => true],
                'repository'   => ['path' => 'Repositories', 'generate' => true],
                'request'      => ['path' => 'Http/Requests', 'generate' => true],
                'resource'     => ['path' => 'Transformers', 'generate' => true],
                'rules'        => ['path' => 'Rules', 'generate' => true],
                'seeder'       => ['path' => 'database/seeds', 'generate' => true],
                'test'         => ['path' => 'tests/Unit', 'generate' => true],
                'test-feature' => ['path' => 'tests/Feature', 'generate' => true],
                'views'        => ['path' => 'resources/views', 'generate' => true],
            ]
        ]);
    }

    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            LaravelModulesServiceProvider::class
        ];
    }
}
