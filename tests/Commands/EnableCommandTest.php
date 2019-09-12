<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Contracts\Repository;
use Rawilk\LaravelModules\Tests\BaseTestCase;

class EnableCommandTest extends BaseTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

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

    /** @test */
    public function it_enables_a_module()
    {
        /** @var \Rawilk\LaravelModules\Module $module */
        $module = $this->app[Repository::class]->find('Blog');
        $module->disable();

        $this->artisan('module:enable', ['module' => 'Blog']);

        $this->assertTrue($module->isEnabled());
    }
}
