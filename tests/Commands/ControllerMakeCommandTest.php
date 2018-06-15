<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Commands\Traits\SetsCommandTestsUp;
use Spatie\Snapshots\MatchesSnapshots;

class ControllerMakeCommandTest extends BaseTestCase
{
    use MatchesSnapshots, SetsCommandTestsUp;

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
    }

    /** @test */
    public function it_appends_controller_to_class_name_if_not_present()
    {
        $this->artisan('module:make-controller', ['controller' => 'My', 'module' => 'Blog']);

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
        $this->app['config']->set('modules.paths.generator.controller.path', 'SomeOtherNamespace');

        $this->artisan('module:make-controller', ['controller' => 'MyController', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/SomeOtherNamespace/MyController.php');

        $this->assertMatchesSnapshot($file);
    }
}
