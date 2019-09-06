<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Concerns\TestsGenerators;
use Spatie\Snapshots\MatchesSnapshots;

class JobMakeCommandTest extends BaseTestCase
{
    use MatchesSnapshots, TestsGenerators;

    /** @test */
    public function it_generates_a_job_class()
    {
        $this->artisan('module:make-job', ['name' => 'SomeJob', 'module' => 'Blog']);

        $this->assertTrue(is_file($this->modulePath . '/Jobs/SomeJob.php'));
    }

    /** @test */
    public function it_can_generate_the_correct_file_with_content()
    {
        $this->artisan('module:make-job', ['name' => 'SomeJob', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Jobs/SomeJob.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_generate_a_sync_job()
    {
        $this->artisan('module:make-job', [
            'name'   => 'SomeJob',
            'module' => 'Blog',
            '--sync' => true
        ]);

        $file = $this->finder->get($this->modulePath . '/Jobs/SomeJob.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_change_the_default_namespace()
    {
        $this->app['config']->set('modules.paths.generator.jobs.path', 'CustomPath');

        $this->artisan('module:make-job', ['name' => 'SomeJob', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/CustomPath/SomeJob.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_change_the_default_namespace_specifically()
    {
        $this->app['config']->set('modules.paths.generator.jobs.namespace', 'CustomPath');

        $this->artisan('module:make-job', ['name' => 'SomeJob', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Jobs/SomeJob.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_generate_a_job_in_a_nested_namespace()
    {
        $this->artisan('module:make-job', ['name' => 'Nested/Path/SomeJob', 'module' => 'Blog']);

        $path = $this->modulePath . '/Jobs/Nested/Path/SomeJob.php';

        $this->assertTrue(is_file($path));
        $this->assertMatchesSnapshot($this->finder->get($path));
    }
}
