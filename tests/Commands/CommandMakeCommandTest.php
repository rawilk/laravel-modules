<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Concerns\TestsGenerators;
use Spatie\Snapshots\MatchesSnapshots;

class CommandMakeCommandTest extends BaseTestCase
{
    use MatchesSnapshots, TestsGenerators;

    /** @test */
    public function it_generates_a_new_console_command_class()
    {
        $this->artisan('module:make-command', ['name' => 'MyAwesomeCommand', 'module' => 'Blog']);

        $this->assertTrue(is_file($this->modulePath . '/Console/MyAwesomeCommand.php'));
    }

    /** @test */
    public function it_generates_the_correct_file_with_content()
    {
        $this->artisan('module:make-command', ['name' => 'MyAwesomeCommand', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Console/MyAwesomeCommand.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_uses_set_command_signature_in_the_class()
    {
        $this->artisan(
            'module:make-command',
            ['name' => 'MyAwesomeCommand', 'module' => 'Blog', '--signature' => 'my:awesome']
        );

        $file = $this->finder->get($this->modulePath . '/Console/MyAwesomeCommand.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_change_the_default_namespace()
    {
        $this->app['config']->set('modules.paths.generator.command.path', 'Commands');

        $this->artisan('module:make-command', ['name' => 'AwesomeCommand', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Commands/AwesomeCommand.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_change_the_default_namespace_specificly()
    {
        $this->app['config']->set('modules.paths.generator.command.namespace', 'Commands');

        $this->artisan('module:make-command', ['name' => 'AwesomeCommand', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Console/AwesomeCommand.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_generate_nested_folders()
    {
        $this->artisan('module:make-command', ['name' => 'Nested/Folder/Command', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Console/Nested/Folder/Command.php');

        $this->assertMatchesSnapshot($file);
    }
}
