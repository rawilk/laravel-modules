<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Concerns\TestsGenerators;
use Spatie\Snapshots\MatchesSnapshots;

class EventMakeCommandTest extends BaseTestCase
{
    use MatchesSnapshots, TestsGenerators;

    /** @test */
    public function it_generates_a_new_event_class()
    {
        $this->artisan('module:make-event', ['name' => 'PostWasCreated', 'module' => 'Blog']);

        $this->assertTrue(is_file($this->modulePath . '/Events/PostWasCreated.php'));
    }

    /** @test */
    public function it_generates_the_correct_file_with_content()
    {
        $this->artisan('module:make-event', ['name' => 'PostWasCreated', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Events/PostWasCreated.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_change_the_default_namespace()
    {
        $this->app['config']->set('modules.paths.generator.event.path', 'CustomNamespace');

        $this->artisan('module:make-event', ['name' => 'PostWasCreated', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/CustomNamespace/PostWasCreated.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_change_the_default_namespace_specificly()
    {
        $this->app['config']->set('modules.paths.generator.event.namespace', 'CustomNamespace');

        $this->artisan('module:make-event', ['name' => 'PostWasCreated', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Events/PostWasCreated.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_generate_a_plain_event()
    {
        $this->artisan('module:make-event', ['name' => 'PostWasCreated', 'module' => 'Blog', '--plain' => true]);

        $file = $this->finder->get($this->modulePath . '/Events/PostWasCreated.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_generate_an_event_in_a_nested_namespace()
    {
        $this->artisan('module:make-event', ['name' => 'Nested/Namespace/PostWasCreated', 'module' => 'Blog']);

        $this->assertTrue(is_file($this->modulePath . '/Events/Nested/Namespace/PostWasCreated.php'));

        $file = $this->finder->get($this->modulePath . '/Events/Nested/Namespace/PostWasCreated.php');

        $this->assertMatchesSnapshot($file);
    }
}
