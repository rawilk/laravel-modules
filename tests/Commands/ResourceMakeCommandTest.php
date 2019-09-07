<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Concerns\TestsGenerators;
use Spatie\Snapshots\MatchesSnapshots;

class ResourceMakeCommandTest extends BaseTestCase
{
    use MatchesSnapshots, TestsGenerators;

    /** @test */
    public function it_generates_a_resource_class()
    {
        $this->artisan('module:make-resource', ['name' => 'PostsTransformer', 'module' => 'Blog']);

        $path = $this->modulePath . '/Transformers/PostsTransformer.php';

        $this->assertTrue(is_file($path));
        $this->assertMatchesSnapshot($this->finder->get($path));
    }

    /** @test */
    public function it_can_generate_a_resource_collection_class()
    {
        $this->artisan('module:make-resource', ['name' => 'PostsTransformer', 'module' => 'Blog', '--collection' => true]);

        $file = $this->finder->get($this->modulePath . '/Transformers/PostsTransformer.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_change_the_default_namespace()
    {
        $this->app['config']->set('modules.paths.generator.resource.path', 'Http/Resources');

        $this->artisan('module:make-resource', ['name' => 'PostsTransformer', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Http/Resources/PostsTransformer.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_change_the_default_namespace_specifically()
    {
        $this->app['config']->set('modules.paths.generator.resource.namespace', 'Http/Resources');

        $this->artisan('module:make-resource', ['name' => 'PostsTransformer', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Transformers/PostsTransformer.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_generate_a_resource_class_in_a_nested_namespace()
    {
        $this->artisan('module:make-resource', ['name' => 'Nested/Path/PostsTransformer', 'module' => 'Blog']);

        $path = $this->modulePath . '/Transformers/Nested/Path/PostsTransformer.php';

        $this->assertTrue(is_file($path));
        $this->assertMatchesSnapshot($this->finder->get($path));
    }
}
