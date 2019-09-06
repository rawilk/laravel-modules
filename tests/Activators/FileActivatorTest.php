<?php

namespace Rawilk\LaravelModules\Tests\Activators;

use Rawilk\LaravelModules\Activators\FileActivator;
use Rawilk\LaravelModules\Laravel\Module;
use Rawilk\LaravelModules\Tests\BaseTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class FileActivatorTest extends BaseTestCase
{
    use MatchesSnapshots;

    /** @var \Rawilk\LaravelModules\Tests\Activators\TestModule */
    private $module;

    /** @var \Illuminate\Filesystem\Filesystem */
    private $finder;

    /** @var \Rawilk\LaravelModules\Activators\FileActivator */
    private $activator;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->module = new TestModule($this->app, 'Recipe', __DIR__ . '/stubs/valid/Recipe');
        $this->finder = $this->app['files'];
        $this->activator = new FileActivator($this->app);
    }

    /**
     * Clean up the testing environment before the next test.
     */
    protected function tearDown(): void
    {
        $this->activator->reset();

        parent::tearDown();
    }

    /** @test */
    public function it_creates_valid_json_files_after_enabling()
    {
        $this->activator->enable($this->module);
        $this->assertMatchesSnapshot($this->finder->get($this->activator->getStatusesFilePath()));

        $this->activator->setActive($this->module, true);
        $this->assertMatchesSnapshot($this->finder->get($this->activator->getStatusesFilePath()));
    }

    /** @test */
    public function it_creates_valid_json_files_after_disabling()
    {
        $this->activator->disable($this->module);
        $this->assertMatchesSnapshot($this->finder->get($this->activator->getStatusesFilePath()));

        $this->activator->setActive($this->module, false);
        $this->assertMatchesSnapshot($this->finder->get($this->activator->getStatusesFilePath()));
    }

    /** @test */
    public function it_can_check_module_disabled_status()
    {
        $this->activator->disable($this->module);
        $this->assertTrue($this->activator->hasStatus($this->module, false));

        $this->activator->setActive($this->module, false);
        $this->assertTrue($this->activator->hasStatus($this->module, false));
    }

    /** @test */
    public function it_can_check_the_status_of_a_module_that_hasnt_been_enabled_or_disabled()
    {
        $this->assertTrue($this->activator->hasStatus($this->module, false));
    }
}

class TestModule extends Module
{
}
