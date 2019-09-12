<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Concerns\TestsGenerators;
use Spatie\Snapshots\MatchesSnapshots;

class TestMakeCommandTest extends BaseTestCase
{
    use MatchesSnapshots, TestsGenerators;

    /** @test */
    public function it_generates_a_new_test_class()
    {
        $this->artisan('module:make-test', ['name' => 'PostRepositoryTest', 'module' => 'Blog']);
        $this->artisan('module:make-test', ['name' => 'PostRepositoryTest', 'module' => 'Blog', '--feature' => true]);

        $unitTestPath = $this->modulePath . '/tests/Unit/PostRepositoryTest.php';
        $featureTestPath = $this->modulePath . '/tests/Feature/PostRepositoryTest.php';

        $this->assertTrue(is_file($unitTestPath));
        $this->assertTrue(is_file($featureTestPath));

        $this->assertMatchesSnapshot($this->finder->get($unitTestPath));
        $this->assertMatchesSnapshot($this->finder->get($featureTestPath));
    }

    /** @test */
    public function it_can_change_the_default_namespace()
    {
        $this->app['config']->set('modules.paths.generator.test.path', 'CustomPath/Unit');
        $this->app['config']->set('modules.paths.generator.test-feature.path', 'CustomPath/Feature');

        $this->artisan('module:make-test', ['name' => 'PostRepositoryTest', 'module' => 'Blog']);
        $this->artisan('module:make-test', ['name' => 'PostRepositoryTest', 'module' => 'Blog', '--feature' => true]);

        $unitTest = $this->finder->get($this->modulePath . '/CustomPath/Unit/PostRepositoryTest.php');
        $featureTest = $this->finder->get($this->modulePath . '/CustomPath/Feature/PostRepositoryTest.php');

        $this->assertMatchesSnapshot($unitTest);
        $this->assertMatchesSnapshot($featureTest);
    }

    /** @test */
    public function it_can_change_the_namespace_specifically()
    {
        $this->app['config']->set('modules.paths.generator.test.namespace', 'CustomPath/Unit');
        $this->app['config']->set('modules.paths.generator.test-feature.namespace', 'CustomPath/Feature');

        $this->artisan('module:make-test', ['name' => 'PostRepositoryTest', 'module' => 'Blog']);
        $this->artisan('module:make-test', ['name' => 'PostRepositoryTest', 'module' => 'Blog', '--feature' => true]);

        $unitTest = $this->finder->get($this->modulePath . '/tests/Unit/PostRepositoryTest.php');
        $featureTest = $this->finder->get($this->modulePath . '/tests/Feature/PostRepositoryTest.php');

        $this->assertMatchesSnapshot($unitTest);
        $this->assertMatchesSnapshot($featureTest);
    }
}
