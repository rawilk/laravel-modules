<?php

namespace Rawilk\LaravelModules\Tests;

use Rawilk\LaravelModules\Collection;
use Rawilk\LaravelModules\Laravel\Module;

class CollectionTest extends BaseTestCase
{
    /** @test */
    public function it_sets_a_path_attribute_with_to_array()
    {
        $moduleOnePath = __DIR__ . '/stubs/valid/Recipe';
        $moduleTwoPath = __DIR__ . '/stubs/valid/Requirement';

        $modules = [
            new Module($this->app, 'module-one', $moduleOnePath),
            new Module($this->app, 'module-two', $moduleTwoPath)
        ];

        $collection = new Collection($modules);
        $collectionArray = $collection->toArray();

        $this->assertArrayHasKey('path', $collectionArray[0]);
        $this->assertEquals($moduleOnePath, $collectionArray[0]['path']);
        $this->assertArrayHasKey('path', $collectionArray[1]);
        $this->assertEquals($moduleTwoPath, $collectionArray[1]['path']);
    }

    /** @test */
    public function it_returns_items_from_a_collection()
    {
        $modules = [
            new Module($this->app, 'module-one', __DIR__ . '/stubs/valid/Recipe'),
            new Module($this->app, 'module-two', __DIR__ . '/stubs/valid/Requirement')
        ];

        $collection = new Collection($modules);

        $items = $collection->getItems();

        $this->assertCount(2, $items);
        $this->assertInstanceOf(Module::class, $items[0]);
    }
}
