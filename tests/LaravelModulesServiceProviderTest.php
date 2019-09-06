<?php

namespace Rawilk\LaravelModules\Tests;

use Rawilk\LaravelModules\Contracts\Activator;
use Rawilk\LaravelModules\Contracts\Repository;

class LaravelModulesServiceProviderTest extends BaseTestCase
{
    /** @test */
    public function it_binds_modules_key_to_the_repository_class()
    {
        $this->assertInstanceOf(Repository::class, app(Repository::class));
        $this->instance(Repository::class, app('modules'));
    }

    /** @test */
    public function it_binds_activator_to_the_activator_class()
    {
        $this->assertInstanceOf(Activator::class, app(Activator::class));
    }
}
