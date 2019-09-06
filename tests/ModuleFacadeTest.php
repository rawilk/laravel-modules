<?php

namespace Rawilk\LaravelModules\Tests;

use Rawilk\LaravelModules\Facades\Module;

class ModuleFacadeTest extends BaseTestCase
{
    /** @test */
    public function it_resolves_the_module_facade()
    {
        $modules = Module::all();

        $this->assertIsArray($modules);
    }

    /** @test */
    public function it_can_create_macros_via_facade()
    {
        Module::macro('testMacro', function () {
            return true;
        });

        $this->assertTrue(Module::hasMacro('testMacro'));
    }

    /** @test */
    public function it_calls_macros_via_facade()
    {
        Module::macro('testMacro', function () {
            return 'a value';
        });

        $this->assertEquals('a value', Module::testMacro());
    }
}
