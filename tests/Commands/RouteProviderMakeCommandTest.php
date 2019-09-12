<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Concerns\TestsGenerators;
use Spatie\Snapshots\MatchesSnapshots;

class RouteProviderMakeCommandTest extends BaseTestCase
{
    use MatchesSnapshots, TestsGenerators;

    /** @test */
    public function it_generates_a_new_service_provider_class()
    {
        $this->artisan('module:route-provider', ['module' => 'Blog']);

        $path = $this->modulePath . '/Providers/RouteServiceProvider.php';

        $this->assertTrue(is_file($path));
        $this->assertMatchesSnapshot($this->finder->get($path));
    }

    /** @test */
    public function it_can_change_the_default_namespace()
    {
        $this->app['config']->set('modules.paths.generator.provider.path', 'CustomPath');

        $this->artisan('module:route-provider', ['module' => 'Blog', '--force' => true]);

        $file = $this->finder->get($this->modulePath . '/CustomPath/RouteServiceProvider.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_change_the_default_namespace_specifically()
    {
        $this->app['config']->set('modules.paths.generator.provider.namespace', 'CustomPath');

        $this->artisan('module:route-provider', ['module' => 'Blog', '--force' => true]);

        $file = $this->finder->get($this->modulePath . '/Providers/RouteServiceProvider.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_overwrite_route_file_names()
    {
        $this->app['config']->set('modules.stubs.files.routes/web', 'CustomPath/web.php');

        $this->artisan('module:route-provider', ['module' => 'Blog', '--force' => true]);

        $file = $this->finder->get($this->modulePath . '/Providers/RouteServiceProvider.php');

        $this->assertMatchesSnapshot($file);
    }
}
