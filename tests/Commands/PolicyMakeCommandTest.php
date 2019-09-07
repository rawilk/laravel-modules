<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Concerns\TestsGenerators;
use Spatie\Snapshots\MatchesSnapshots;

class PolicyMakeCommandTest extends BaseTestCase
{
    use MatchesSnapshots, TestsGenerators;

    /** @test */
    public function it_makes_a_policy_class()
    {
        $this->artisan('module:make-policy', ['name' => 'PostPolicy', 'module' => 'Blog']);

        $file = $this->modulePath . '/Policies/PostPolicy.php';

        $this->assertTrue(is_file($file), 'Policy file was not created');
        $this->assertMatchesSnapshot($this->finder->get($file));
    }

    /** @test */
    public function it_can_change_the_default_namespace()
    {
        $this->app['config']->set('modules.paths.generator.policies.path', 'CustomPath');

        $this->artisan('module:make-policy', ['name' => 'PostPolicy', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/CustomPath/PostPolicy.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_change_the_default_namespace_specifically()
    {
        $this->app['config']->set('modules.paths.generator.policies.namespace', 'CustomPath');

        $this->artisan('module:make-policy', ['name' => 'PostPolicy', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Policies/PostPolicy.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_create_a_policy_in_a_nested_namespace()
    {
        $this->artisan('module:make-policy', ['name' => 'Nested/Path/PostPolicy', 'module' => 'Blog']);

        $path = $this->modulePath . '/Policies/Nested/Path/PostPolicy.php';

        $this->assertTrue(is_file($path));
        $this->assertMatchesSnapshot($this->finder->get($path));
    }
}
