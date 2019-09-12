<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Concerns\TestsGenerators;
use Spatie\Snapshots\MatchesSnapshots;

class RuleMakeCommandTest extends BaseTestCase
{
    use MatchesSnapshots, TestsGenerators;

    /** @test */
    public function it_makes_a_new_rule_class()
    {
        $this->artisan('module:make-rule', ['name' => 'UniqueRule', 'module' => 'Blog']);

        $path = $this->modulePath . '/Rules/UniqueRule.php';

        $this->assertTrue(is_file($path), 'Rule file was not created.');
        $this->assertMatchesSnapshot($this->finder->get($path));
    }

    /** @test */
    public function it_can_change_the_default_namespace()
    {
        $this->app['config']->set('modules.paths.generator.rules.path', 'CustomPath');

        $this->artisan('module:make-rule', ['name' => 'UniqueRule', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/CustomPath/UniqueRule.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_change_the_default_namespace_specifically()
    {
        $this->app['config']->set('modules.paths.generator.rules.namespace', 'CustomPath');

        $this->artisan('module:make-rule', ['name' => 'UniqueRule', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Rules/UniqueRule.php');

        $this->assertMatchesSnapshot($file);
    }
}
