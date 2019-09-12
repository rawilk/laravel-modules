<?php

namespace Rawilk\LaravelModules\Tests;

use Rawilk\LaravelModules\Collection;
use Rawilk\LaravelModules\Contracts\ModuleModel;
use Rawilk\LaravelModules\Exceptions\ModuleNotFound;
use Rawilk\LaravelModules\Laravel\LaravelEloquentRepository;

class LaravelEloquentRepositoryTest extends BaseTestCase
{
    /** @var \Rawilk\LaravelModules\Laravel\LaravelEloquentRepository */
    private $repository;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(LaravelEloquentRepository::class);
    }

    /** @test */
    public function it_returns_all_modules()
    {
        $this->createModule('Recipe');

        $this->assertCount(1, $this->repository->all());
        $this->assertCount(1, $this->repository->scan());
    }

    /** @test */
    public function it_returns_a_collection_of_module_instances()
    {
        $this->createModule('Recipe');
        $this->createModule('Requirement');
        $this->createModule('DisabledModule');

        $collection = $this->repository->toCollection();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(3, $collection);
    }

    /** @test */
    public function it_returns_all_enabled_modules()
    {
        $this->createModule('Recipe');
        $this->createModule('Requirement', ['is_active' => false]);

        $this->assertCount(1, $this->repository->allEnabled());
    }

    /** @test */
    public function it_returns_all_disabled_modules()
    {
        $this->createModule('Recipe');
        $this->createModule('Requirement', ['is_active' => false]);

        $this->assertCount(1, $this->repository->allDisabled());
    }

    /** @test */
    public function it_counts_all_modules()
    {
        $this->createModule('Recipe');
        $this->createModule('Requirement');

        $this->assertEquals(2, $this->repository->count());
    }

    /** @test */
    public function it_returns_enabled_modules_in_ascending_order()
    {
        $this->createModule('Recipe', ['order' => 1]);
        $this->createModule('Requirement', ['order' => 10]);
        $this->createModule('DisabledModule', ['order' => 5]);

        $modules = $this->repository->getOrdered();

        $this->assertCount(3, $modules);
        $this->assertEquals('Recipe', $modules[0]['name']);
        $this->assertEquals('DisabledModule', $modules[1]['name']);
        $this->assertEquals('Requirement', $modules[2]['name']);
    }

    /** @test */
    public function it_returns_enabled_modules_in_descending_order()
    {
        $this->createModule('Recipe', ['order' => 1]);
        $this->createModule('Requirement', ['order' => 10]);
        $this->createModule('DisabledModule', ['order' => 5]);

        $modules = $this->repository->getOrdered('desc');

        $this->assertCount(3, $modules);
        $this->assertEquals('Requirement', $modules[0]['name']);
        $this->assertEquals('DisabledModule', $modules[1]['name']);
        $this->assertEquals('Recipe', $modules[2]['name']);
    }

    /** @test */
    public function it_gets_modules_by_a_given_status()
    {
        $this->createModule('Requirement', ['is_active' => false]);

        $this->assertCount(1, $this->repository->getByStatus(false));
        $this->assertCount(0, $this->repository->getByStatus(true));
    }

    /** @test */
    public function it_can_find_modules_by_name()
    {
        $this->createModule('Recipe');
        $this->createModule('Requirement');

        $this->assertEquals('Recipe', $this->repository->find('Recipe')->getName());
    }

    /** @test */
    public function it_returns_null_if_a_module_was_not_found()
    {
        $this->assertNull($this->repository->find('Unknown'));
    }

    /** @test */
    public function it_throws_an_exception_if_a_module_was_not_found_with_find_or_fail()
    {
        $this->expectException(ModuleNotFound::class);

        $this->repository->findOrFail('Unknown');
    }

    /** @test */
    public function it_returns_the_module_path()
    {
        $this->createModule('Recipe');

        $this->assertEquals(
            __DIR__ . '/stubs/valid/Recipe',
            $this->repository->getModulePath('Recipe')
        );
    }

    /** @test */
    public function it_can_check_if_a_module_exists()
    {
        $this->createModule('Recipe');

        $this->assertFalse($this->repository->exists('Unknown'));
        $this->assertTrue($this->repository->exists('Recipe'));
    }

    private function createModule(string $name, array $attributes = []): ModuleModel
    {
        /** @var \Rawilk\LaravelModules\Contracts\ModuleModel $module */
        $module = new $this->app[ModuleModel::class];
        $module->forceFill(array_merge([
            'name' => $name,
            'path' => __DIR__ . '/stubs/valid/' . $name
        ], $attributes));

        $module->save();

        return $module;
    }
}
