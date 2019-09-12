<?php

namespace Rawilk\LaravelModules\Tests;

use Illuminate\Support\Str;

class HelpersTest extends BaseTestCase
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

        $this->modulePath = base_path('modules/Blog');
        $this->finder = $this->app['files'];
        $this->artisan('module:make', ['name' => ['Blog']]);
    }

    /**
     * Clean up the testing environment before the next test.
     */
    protected function tearDown(): void
    {
        $this->finder->deleteDirectory($this->modulePath);

        parent::tearDown();
    }

    /** @test */
    public function it_finds_the_module_path()
    {
        $this->assertTrue(Str::contains(module_path('Blog'), 'Modules/Blog'));
    }
}
