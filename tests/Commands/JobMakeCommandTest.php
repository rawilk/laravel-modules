<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Commands\Traits\SetsCommandTestsUp;
use Spatie\Snapshots\MatchesSnapshots;

class JobMakeCommandTest extends BaseTestCase
{
    use MatchesSnapshots, SetsCommandTestsUp;

    /** @test */
    public function it_generates_a_new_job_class()
    {
        $this->artisan('module:make-job', ['name' => 'SomeJob', 'module' => 'Blog']);

        $this->assertTrue(is_file($this->modulePath . '/Jobs/SomeJob.php'));
    }

    /** @test */
    public function it_generates_the_correct_file_with_content()
    {
        $this->artisan('module:make-job', ['name' => 'SomeJob', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Jobs/SomeJob.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_generate_a_sync_job_class()
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
        $this->app['config']->set('modules.paths.generator.jobs.path', 'OtherNamespace');

        $this->artisan('module:make-job', ['name' => 'SomeJob', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/OtherNamespace/SomeJob.php');

        $this->assertMatchesSnapshot($file);
    }
}
