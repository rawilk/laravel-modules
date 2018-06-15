<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class ProviderMakeCommandTest extends BaseTestCase
{
    use MatchesSnapshots;

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $finder;

    /**
     * @var string
     */
    private $modulePath;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->modulePath = base_path('Modules/Blog');
        $this->finder = $this->app['files'];
        $this->artisan('module:make', ['name' => ['Blog'], '--plain' => true]);
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->finder->deleteDirectory($this->modulePath);

        parent::tearDown();
    }

    /** @test */
    public function it_generates_a_service_provider()
    {
        $this->artisan('module:make-provider', ['name' => 'MyBlogServiceProvider', 'module' => 'Blog']);

        $this->assertTrue(is_file($this->modulePath . '/Providers/MyBlogServiceProvider.php'));
    }

    /** @test */
    public function it_generates_the_correct_file_with_content()
    {
        $this->artisan('module:make-provider', ['name' => 'MyBlogServiceProvider', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Providers/MyBlogServiceProvider.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_generates_a_master_service_provider_with_resource_loading()
    {
        $this->artisan('module:make-provider', [
            'name'     => 'BlogServiceProvider',
            'module'   => 'Blog',
            '--master' => true
        ]);

        $file = $this->finder->get($this->modulePath . '/Providers/BlogServiceProvider.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_have_custom_migration_resources_location_paths()
    {
        $this->app['config']->set('modules.paths.generator.migration', 'migrations');

        $this->artisan('module:make-provider', [
            'name'     => 'BlogServiceProvider',
            'module'   => 'Blog',
            '--master' => true
        ]);

        $file = $this->finder->get($this->modulePath . '/Providers/BlogServiceProvider.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_change_the_default_namespace()
    {
        $this->app['config']->set('modules.paths.generator.provider.path', 'OtherNamespace');

        $this->artisan('module:make-provider', [
            'name'     => 'BlogServiceProvider',
            'module'   => 'Blog',
            '--master' => true
        ]);

        $file = $this->finder->get($this->modulePath . '/OtherNamespace/BlogServiceProvider.php');

        $this->assertMatchesSnapshot($file);
    }
}
