<?php

namespace Rawilk\LaravelModules\Tests;

use Illuminate\Support\Facades\DB;
use Rawilk\LaravelModules\Models\Module;

class ModuleModelTest extends BaseTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->seedModules();
    }

    /** @test */
    public function it_saves_a_new_record_to_the_database()
    {
        $data = [
            'name' => 'Test Module',
            'path' => __DIR__ . '/stubs/valid'
        ];

        $module = new Module($data);
        $module->save();

        $this->assertDatabaseHas('modules', $data);
    }

    /** @test */
    public function it_gets_all_disabled_modules()
    {
        $disabled = Module::allDisabled();

        $this->assertCount(1, $disabled);
        $this->assertEquals('Disabled 1', $disabled->first()->name);
    }

    /** @test */
    public function it_gets_all_enabled_modules()
    {
        $enabled = Module::allEnabled();

        $this->assertCount(2, $enabled);
        $this->assertEquals('Enabled 1', $enabled->first()->name);
        $this->assertEquals('Enabled 2', $enabled[1]->name);
    }

    /** @test */
    public function it_gets_all_modules()
    {
        $modules = Module::allModules();

        $this->assertCount(3, $modules);
    }

    /** @test */
    public function it_finds_modules_by_alias()
    {
        $module = Module::findByAlias('enabled_1_alias');

        $this->assertEquals('Enabled 1', $module->name);
    }

    /** @test */
    public function it_finds_modules_by_name()
    {
        $module = Module::findModule('Enabled 1');

        $this->assertEquals('enabled_1_alias', $module->alias);
        $this->assertTrue($module->is_active);
    }

    /** @test */
    public function it_gets_modules_by_activation_status()
    {
        $enabled = Module::getByStatus(true);
        $disabled = Module::getByStatus(false);

        $this->assertCount(2, $enabled);
        $this->assertCount(1, $disabled);
    }

    /** @test */
    public function it_gets_the_count_of_all_modules()
    {
        $count = Module::getCount();

        $this->assertEquals(3, $count);
    }

    /** @test */
    public function it_gets_all_enabled_modules_ordered()
    {
        $enabled = Module::getOrdered();

        $this->assertCount(2, $enabled);
        $this->assertEquals('Enabled 2', $enabled->first()->name);
        $this->assertEquals('Enabled 1', $enabled[1]->name);
    }

    /** @test */
    public function it_enables_a_module()
    {
        $this->assertDatabaseHas('modules', ['name' => 'Disabled 1', 'is_active' => false]);

        Module::enable('Disabled 1');

        $this->assertDatabaseHas('modules', ['name' => 'Disabled 1', 'is_active' => true]);
    }

    /** @test */
    public function it_disables_a_module()
    {
        $this->assertDatabaseHas('modules', ['name' => 'Enabled 1', 'is_active' => true]);

        Module::disable('Enabled 1');

        $this->assertDatabaseHas('modules', ['name' => 'Enabled 1', 'is_active' => false]);
    }

    /** @test */
    public function it_checks_if_a_module_has_a_status()
    {
        $enabledModule = Module::findModule('Enabled 1');
        $disabledModule = Module::findModule('Disabled 1');

        $this->assertTrue($enabledModule->hasStatus(true));
        $this->assertFalse($enabledModule->hasStatus(false));
        $this->assertTrue($disabledModule->hasStatus(false));
        $this->assertFalse($disabledModule->hasStatus(true));
    }

    /** @test */
    public function it_can_check_if_it_is_disabled()
    {
        $enabledModule = Module::findModule('Enabled 1');
        $disabledModule = Module::findModule('Disabled 1');

        $this->assertFalse($enabledModule->isDisabled());
        $this->assertTrue($disabledModule->isDisabled());
    }

    /** @test */
    public function it_can_check_if_it_is_enabled()
    {
        $enabledModule = Module::findModule('Enabled 1');
        $disabledModule = Module::findModule('Disabled 1');

        $this->assertTrue($enabledModule->isEnabled());
        $this->assertFalse($disabledModule->isEnabled());
    }

    /** @test */
    public function it_returns_the_module_name()
    {
        $module = Module::findModule('Enabled 1');

        $this->assertEquals('Enabled 1', $module->getName());
    }

    private function seedModules(): void
    {
        $data = [
            ['name' => 'Enabled 1', 'path' => __DIR__ . '/stubs/valid', 'is_active' => true, 'alias' => 'enabled_1_alias', 'order' => 1],
            ['name' => 'Enabled 2', 'path' => __DIR__ . '/stubs/valid', 'is_active' => true, 'alias' => 'enabled_2_alias', 'order' => 0],
            ['name' => 'Disabled 1', 'path' => __DIR__ . '/stubs/valid', 'is_active' => false, 'alias' => 'enabled_3_alias', 'order' => 2],
        ];

        DB::table('modules')->insert($data);
    }
}
