<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Concerns\TestsGenerators;
use Spatie\Snapshots\MatchesSnapshots;

class ProviderMakeCommandTest extends BaseTestCase
{
    use MatchesSnapshots, TestsGenerators;

    /** @test */
    public function it_generates_a_service_provider()
    {
        $this->artisan('module:make-provider', ['name' => 'MyBlogServiceProvider', 'module' => 'Blog']);

        $path = $this->modulePath . '/Providers/MyBlogServiceProvider.php';

        $this->assertTrue(is_file($path));
        $this->assertMatchesSnapshot($this->finder->get($path));
    }

    /** @test */
    public function it_generates_a_master_service_provider_with_resource_loading()
    {
        $path = $this->generateMaster();

        $this->assertMatchesSnapshot($this->finder->get($path));
    }

    /** @test */
    public function it_can_have_custom_migration_resources_location_paths()
    {
        $this->app['config']->set('modules.paths.generator.migration', 'migrations');

        $path = $this->generateMaster(true);

        $this->assertMatchesSnapshot($this->finder->get($path));
    }

    /** @test */
    public function it_can_change_the_default_namespace()
    {
        $this->app['config']->set('modules.paths.generator.provider.path', 'CustomPath');

        $path = $this->generateMaster();

        $this->assertMatchesSnapshot($this->finder->get($path));
    }

    /** @test */
    public function it_can_change_the_default_namespace_specifically()
    {
        $this->app['config']->set('modules.paths.generator.provider.namespace', 'CustomPath');

        $path = $this->generateMaster(true);

        $this->assertMatchesSnapshot($this->finder->get($path));
    }

    private function generateMaster(bool $deleteFirst = false): string
    {
        $providerPath = $this->modulePath . '/' . $this->app['config']->get('modules.paths.generator.provider.path') . '/BlogServiceProvider.php';

        if ($deleteFirst) {
            $this->finder->exists($providerPath) && $this->finder->delete($providerPath);
        }

        $this->artisan('module:make-provider', ['name' => 'BlogServiceProvider', 'module' => 'Blog', '--master' => true]);

        return $providerPath;
    }
}
