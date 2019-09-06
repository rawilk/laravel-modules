<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Concerns\TestsGenerators;
use Spatie\Snapshots\MatchesSnapshots;

class MailMakeCommandTest extends BaseTestCase
{
    use MatchesSnapshots, TestsGenerators;

    /** @test */
    public function it_generates_a_mailable()
    {
        $this->artisan('module:make-mail', ['name' => 'SomeMail', 'module' => 'Blog']);

        $this->assertTrue(is_file($this->modulePath . '/Mail/SomeMail.php'));
    }

    /** @test */
    public function it_generates_the_correct_file_with_content()
    {
        $this->artisan('module:make-mail', ['name' => 'SomeMail', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Mail/SomeMail.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_change_the_default_namespace()
    {
        $this->app['config']->set('modules.paths.generator.emails.path', 'CustomPath');

        $this->artisan('module:make-mail', ['name' => 'SomeMail', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/CustomPath/SomeMail.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_change_the_default_namespace_specifically()
    {
        $this->app['config']->set('modules.paths.generator.emails.namespace', 'CustomPath');

        $this->artisan('module:make-mail', ['name' => 'SomeMail', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Mail/SomeMail.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_generate_a_mailable_in_a_nested_namespace()
    {
        $this->artisan('module:make-mail', ['name' => 'Nested/Path/SomeMail', 'module' => 'Blog']);

        $path = $this->modulePath . '/Mail/Nested/Path/SomeMail.php';

        $this->assertTrue(is_file($path));
        $this->assertMatchesSnapshot($this->finder->get($path));
    }

    /** @test */
    public function it_can_use_a_custom_base_mailable_class()
    {
        $this->artisan('module:make-mail', ['name' => 'SomeMail', 'module' => 'Blog', '--base_class' => 'App/Mail/BaseMailable']);

        $file = $this->finder->get($this->modulePath . '/Mail/SomeMail.php');

        $this->assertMatchesSnapshot($file);
    }
}
