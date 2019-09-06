<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Contracts\Activator;
use Rawilk\LaravelModules\Contracts\Repository;
use Rawilk\LaravelModules\Tests\BaseTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class ModuleMakeCommandTest extends BaseTestCase
{
    use MatchesSnapshots;

    /** @var \Illuminate\Filesystem\Filesystem */
    private $finder;

    /** @var string */
    private $modulePath;

    /** @var \Rawilk\LaravelModules\Contracts\Activator */
    private $activator;

    /** @var \Rawilk\LaravelModules\Contracts\Repository */
    private $repository;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->modulePath = base_path('modules/Blog');
        $this->finder = $this->app['files'];
        $this->repository = $this->app[Repository::class];
        $this->activator = $this->app[Activator::class];
    }

    /**
     * Clean up the testing environment before the next test.
     */
    protected function tearDown(): void
    {
        $this->finder->deleteDirectory($this->modulePath);

        if ($this->finder->isDirectory(base_path('Modules/ModuleName'))) {
            $this->finder->deleteDirectory(base_path('Modules/ModuleName'));
        }

        $this->activator->reset();

        parent::tearDown();
    }

    /** @test */
    public function it_generates_a_module()
    {
        $exitCode = $this->artisan('module:make', ['name' => ['Blog']]);

        $this->assertEquals(0, $exitCode);
        $this->assertDirectoryExists($this->modulePath);
    }

    /** @test */
    public function it_generates_module_folders()
    {
        $this->generateModule();

        foreach (config('modules.paths.generator') as $directory) {
            $this->assertDirectoryExists($this->modulePath . '/' . $directory['path']);
        }
    }

    /** @test */
    public function it_generates_module_files()
    {
        $this->generateModule();

        foreach (config('modules.stubs.files') as $file) {
            $path = $this->modulePath . '/' . $file;

            $this->assertTrue($this->finder->exists($path), "[{$file}] does not exist!");
        }

        $path = $this->modulePath . '/module.json';

        $this->assertTrue($this->finder->exists($path), '[module.json] does not exist!');
        $this->assertMatchesSnapshot($this->finder->get($path));
    }

    /** @test */
    public function it_generates_a_web_routes_file()
    {
        $files = $this->app['modules']->config('stubs.files');
        $this->generateModule();

        $path = $this->modulePath . '/' . $files['routes/web'];

        $this->assertMatchesSnapshot($this->finder->get($path));
    }

    /** @test */
    public function it_generates_a_webpack_file()
    {
        $this->generateModule();

        $path = $this->modulePath . '/' . $this->app['modules']->config('stubs.files.webpack');

        $this->assertMatchesSnapshot($this->finder->get($path));
    }

    /** @test */
    public function it_generates_module_resources()
    {
        $this->generateModule();

        $paths = [
            '/Providers/BlogServiceProvider.php',
            '/database/seeds/BlogDatabaseSeeder.php',
            '/Providers/RouteServiceProvider.php'
        ];

        foreach ($paths as $path) {
            $filePath = $this->modulePath . $path;

            $this->assertTrue($this->finder->exists($filePath), "Resource [{$filePath}] does not exist!");
            $this->assertMatchesSnapshot($this->finder->get($filePath));
        }
    }

    /** @test */
    public function it_generates_a_composer_json_file()
    {
        $this->generateModule();

        $file = $this->finder->get($this->modulePath . '/composer.json');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_generates_a_module_folder_using_studly_case()
    {
        $this->generateModule('ModuleName');

        $this->assertTrue($this->finder->exists(base_path('Modules/ModuleName')));
    }

    /** @test */
    public function it_generates_a_module_namespace_using_studly_case()
    {
        $this->generateModule('ModuleName');

        $file = $this->finder->get(base_path('Modules/ModuleName') . '/Providers/ModuleNameServiceProvider.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_generates_a_plain_module_with_no_resources()
    {
        $this->generateModule('ModuleName', ['--plain' => true]);

        $paths = [
            '/Providers/ModuleNameServiceProvider.php',
            '/database/seeds/ModuleNameDatabaseSeeder.php'
        ];

        foreach ($paths as $path) {
            $filePath = base_path('Modules/ModuleName') . $path;

            $this->assertFalse($this->finder->exists($filePath), "[{$path}] exists!");
        }
    }

    /** @test */
    public function it_generates_a_plain_module_with_no_files()
    {
        $this->generateModule('ModuleName', ['--plain' => true]);

        foreach (config('modules.stubs.files') as $file) {
            $path = base_path('Modules/ModuleName') . '/' . $file;

            $this->assertFalse($this->finder->exists($path), "[{$file}] exists!");
        }

        $path = base_path('Modules/ModuleName') . '/module.json';

        $this->assertTrue($this->finder->exists($path), '[module.json] does not exist!');
    }

    /** @test */
    public function it_generates_a_plain_module_with_no_service_provider_in_module_json_file()
    {
        $this->generateModule('ModuleName', ['--plain' => true]);

        $path = base_path('Modules/ModuleName') . '/module.json';
        $content = json_decode($this->finder->get($path), true);

        $this->assertCount(0, $content['providers']);
    }

    private function generateModule(string $moduleName = 'Blog', array $arguments = []): void
    {
        $this->artisan('module:make', array_merge(['name' => [$moduleName]], $arguments));
    }
}
