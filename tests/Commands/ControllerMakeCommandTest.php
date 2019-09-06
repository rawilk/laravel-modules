<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Concerns\TestsGenerators;
use Spatie\Snapshots\MatchesSnapshots;

class ControllerMakeCommandTest extends BaseTestCase
{
    use MatchesSnapshots, TestsGenerators;

    /** @test */
    public function it_generates_a_new_controller_class()
    {
        $this->artisan('module:make-controller', ['controller' => 'MyController', 'module' => 'Blog']);

        $this->assertTrue(is_file($this->modulePath . '/Http/Controllers/MyController.php'));
    }

    /** @test */
    public function it_generates_the_correct_file_with_content()
    {
        $this->artisan('module:make-controller', ['controller' => 'MyController', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Http/Controllers/MyController.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_appends_controller_to_name_if_not_present()
    {
        $this->artisan('module:make-controller', ['controller' => 'My', 'module' => 'Blog']);

        $this->assertTrue(is_file($this->modulePath . '/Http/Controllers/MyController.php'));

        $file = $this->finder->get($this->modulePath . '/Http/Controllers/MyController.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_generates_a_plain_controller()
    {
        $this->artisan('module:make-controller', [
            'controller' => 'MyController',
            'module'     => 'Blog',
            '--plain'    => true
        ]);

        $file = $this->finder->get($this->modulePath . '/Http/Controllers/MyController.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_change_the_default_namespace()
    {
        $this->app['config']->set('modules.paths.generator.controller.path', 'Controllers');

        $this->artisan('module:make-controller', ['controller' => 'MyController', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Controllers/MyController.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_change_the_default_namespace_specificly()
    {
        $this->app['config']->set('modules.paths.generator.controller.namespace', 'Controllers');

        $this->artisan('module:make-controller', ['controller' => 'MyController', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Http/Controllers/MyController.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_generate_a_controller_in_a_nested_namespace()
    {
        $this->artisan('module:make-controller', ['controller' => 'My/Nested/NestedController', 'module' => 'Blog']);

        $this->assertTrue(is_file($this->modulePath . '/Http/Controllers/My/Nested/NestedController.php'));

        $file = $this->finder->get($this->modulePath . '/Http/Controllers/My/Nested/NestedController.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_use_a_custom_base_controller_class()
    {
        $this->artisan('module:make-controller', [
            'controller'   => 'MyController',
            'module'       => 'Blog',
            '--base_class' => 'App/Http/Controllers/BaseController'
        ]);

        $file = $this->finder->get($this->modulePath . '/Http/Controllers/MyController.php');

        $this->assertMatchesSnapshot($file);
    }
}
