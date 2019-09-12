<?php

namespace Rawilk\LaravelModules\Tests\Activators;

use Rawilk\LaravelModules\Activators\DatabaseActivator;
use Rawilk\LaravelModules\Laravel\Module as LaravelModule;
use Rawilk\LaravelModules\Models\Module;
use Rawilk\LaravelModules\Tests\BaseTestCase;

class DatabaseActivatorTest extends BaseTestCase
{
    /** @var \Rawilk\LaravelModules\Activators\DatabaseActivator */
    private $activator;

    /** @var \Rawilk\LaravelModules\Models\Module */
    private $module;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->activator = new DatabaseActivator($this->app);

        /** @var \Rawilk\LaravelModules\Models\Module $module */
        $module = Module::create(['name' => 'Blog', 'path' => __DIR__ . '/../stubs/valid/Recipe', 'is_active' => true]);
        $this->module = new LaravelModule($this->app, $module->getName(), $module->path);
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
    public function it_can_check_a_module_disabled_status()
    {
        $this->activator->disable($this->module);
        $this->assertTrue($this->activator->hasStatus($this->module, false));

        $this->activator->setActive($this->module, false);
        $this->assertTrue($this->activator->hasStatus($this->module, false));
    }
}
