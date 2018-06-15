<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Illuminate\Support\Facades\Artisan;
use Rawilk\LaravelModules\Tests\BaseTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class ModuleMakeCommandTest extends BaseTestCase
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
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->finder->deleteDirectory($this->modulePath);
        if ($this->finder->isDirectory(base_path('Modules/ModuleName'))) {
            $this->finder->deleteDirectory(base_path('Modules/ModuleName'));
        }

        parent::tearDown();
    }

    /** @test */
    public function it_generates_a_module()
    {
        $code = $this->artisan('module:make', ['name' => ['Blog']]);

        $this->assertTrue(is_dir($this->modulePath));
        $this->assertSame(0, $code);
    }

    /** @test */
    public function it_generates_a_route_file()
    {
        $this->artisan('module:make', ['name' => ['Blog']]);

        $path = $this->modulePath . '/' . $this->app['modules']->config('stubs.files.routes');

        $this->assertMatchesSnapshot($this->finder->get($path));
    }

    /** @test */
    public function it_generates_a_start_php_file()
    {
        $this->artisan('module:make', ['name' => ['Blog']]);

        $path = $this->modulePath . '/' . $this->app['modules']->config('stubs.files.start');

        $this->assertMatchesSnapshot($this->finder->get($path));
    }

    /** @test */
    public function it_generates_a_webpack_file()
    {
        $this->artisan('module:make', ['name' => ['Blog']]);

        $path = $this->modulePath . '/' . $this->app['modules']->config('stubs.files.webpack');

        $this->assertMatchesSnapshot($this->finder->get($path));
    }

    /** @test */
    public function it_generates_module_resources()
    {
        $this->artisan('module:make', ['name' => ['Blog']]);

        $path = base_path('Modules/Blog') . '/Providers/BlogServiceProvider.php';
        $this->assertTrue($this->finder->exists($path));
        $this->assertMatchesSnapshot($this->finder->get($path));
    }

    /** @test */
    public function it_generates_a_composer_json_file()
    {
        $this->artisan('module:make', ['name' => ['Blog']]);

        $file = $this->finder->get($this->modulePath . '/composer.json');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_generates_a_module_namespace_using_studly_case()
    {
        $this->artisan('module:make', ['name' => ['ModuleName']]);

        $file = $this->finder->get(base_path('Modules/ModuleName') . '/Providers/ModuleNameServiceProvider.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_generates_a_plain_module_with_no_resources()
    {
        $this->artisan('module:make', ['name' => ['ModuleName'], '--plain' => true]);

        $path = base_path('Modules/ModuleName') . '/Providers/ModuleNameServiceProvider.php';
        $this->assertFalse($this->finder->exists($path));
    }

    /** @test */
    public function it_generates_a_plain_module_with_no_files()
    {
        $this->artisan('module:make', ['name' => ['ModuleName'], '--plain' => true]);

        foreach (config('modules.stubs.files') as $file) {
            $path = base_path('Modules/ModuleName') . '/' . $file;

            $this->assertFalse($this->finder->exists($path), "File found: {$file}");
        }

        $path = base_path('Modules/ModuleName') . '/module.json';
        $this->assertTrue($this->finder->exists($path), 'module.json not found');
    }

    /** @test */
    public function it_generates_a_plain_module_with_no_service_provider_in_module_json_file()
    {
        $this->artisan('module:make', ['name' => ['ModuleName'], '--plain' => true]);

        $path = base_path('Modules/ModuleName') . '/module.json';
        $content = json_decode($this->finder->get($path));

        $this->assertCount(0, $content->providers);
    }

    /** @test */
    public function it_alerts_you_when_a_module_already_exists()
    {
        $this->artisan('module:make', ['name' => ['Blog']]);
        $this->artisan('module:make', ['name' => ['Blog']]);

        // The return in this variable is intentional so the strings actually match
        $expected = 'Module [Blog] already exists!
';
        $this->assertEquals($expected, Artisan::output());
    }

    /** @test */
    public function it_still_generates_a_module_if_it_exists_using_force_flag()
    {
        $this->artisan('module:make', ['name' => ['Blog']]);
        $this->artisan('module:make', ['name' => ['Blog'], '--force' => true]);

        $output = Artisan::output();
        $notExpected = 'Module [Blog] already exists!
';

        $this->assertNotEquals($notExpected, $output);
        $this->assertTrue(str_contains($output, 'Module [Blog] was created successfully.'));
    }
}
