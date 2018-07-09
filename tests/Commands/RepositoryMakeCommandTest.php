<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Commands\Traits\SetsCommandTestsUp;
use Spatie\Snapshots\MatchesSnapshots;

class RepositoryMakeCommandTest  extends BaseTestCase
{
    use MatchesSnapshots, SetsCommandTestsUp;

    /** @test */
    public function it_generates_a_new_repository_class()
    {
        $this->artisan('module:make-repository', ['repository' => 'MyRepository', 'module' => 'Blog']);

        $this->assertTrue(is_file($this->modulePath . '/Repositories/MyRepository.php'));
    }

    /** @test */
    public function it_generates_the_correct_file_with_content()
    {
        $this->artisan('module:make-repository', ['repository' => 'MyRepository', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Repositories/MyRepository.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_appends_repository_to_name_if_not_present()
    {
        $this->artisan('module:make-repository', ['repository' => 'My', 'module' => 'Blog']);

        $this->assertTrue(is_file($this->modulePath . '/Repositories/MyRepository.php'));
    }

    /** @test */
    public function it_generates_a_plain_repository()
    {
        $this->artisan('module:make-repository', [
            'repository' => 'MyRepository',
            'module'     => 'Blog',
            '--plain'    => true
        ]);

        $file = $this->finder->get($this->modulePath . '/Repositories/MyRepository.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_change_the_default_namespace()
    {
        $this->app['config']->set('modules.paths.generator.repository.path', 'SomeOtherNamespace');

        $this->artisan('module:make-repository', ['repository' => 'MyRepository', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/SomeOtherNamespace/MyRepository.php');

        $this->assertMatchesSnapshot($file);
    }
}
