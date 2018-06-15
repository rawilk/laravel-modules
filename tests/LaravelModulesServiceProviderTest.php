<?php

namespace Rawilk\LaravelModules\Tests;

use Rawilk\LaravelModules\Laravel\Repository;

class LaravelModulesServiceProviderTest extends BaseTestCase
{
    /** @test */
    public function it_binds_modules_key_to_repository_class()
    {
        $this->assertInstanceOf(Repository::class, app('modules'));
    }
}
