<?php

namespace Rawilk\LaravelModules\Tests\Concerns;

use Rawilk\LaravelModules\Contracts\Repository;

/**
 * @mixin \Rawilk\LaravelModules\Tests\BaseTestCase
 */
trait TestsGenerators
{
    /** @var \Illuminate\Filesystem\Filesystem */
    private $finder;

    /** @var string */
    private $modulePath;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->modulePath = base_path('Modules/Blog');
        $this->finder = $this->app['files'];
        $this->artisan('module:make', ['name' => ['Blog']]);
    }

    /**
     * Clean up the testing environment before the next test.
     */
    protected function tearDown(): void
    {
        $this->app[Repository::class]->delete('Blog');

        parent::tearDown();
    }
}
