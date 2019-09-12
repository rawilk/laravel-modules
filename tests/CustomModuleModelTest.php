<?php

namespace Rawilk\LaravelModules\Tests;

use Rawilk\LaravelModules\Contracts\ModuleModel;
use Rawilk\LaravelModules\Tests\Models\CustomModule;

class CustomModuleModelTest extends BaseTestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('modules.models.module', CustomModule::class);

        parent::getEnvironmentSetUp($app);
    }

    /** @test */
    public function it_can_use_a_custom_module_model()
    {
        $module = new $this->app[ModuleModel::class];

        $this->assertInstanceOf(CustomModule::class, $module);

        $module->name = 'My new module';
        $module->path = __DIR__ . '/stubs/valid';
        $module->save();

        $this->assertDatabaseHas('modules', ['name' => 'My new module', 'path' => __DIR__ . '/stubs/valid']);
    }
}
