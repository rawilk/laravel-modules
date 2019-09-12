<?php

namespace Rawilk\LaravelModules\Tests;

use Illuminate\Support\Str;
use Rawilk\LaravelModules\Support\Stub;

class StubTest extends BaseTestCase
{
    /** @var \Illuminate\Filesystem\Filesystem */
    private $finder;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->finder = $this->app['files'];
    }

    /**
     * Clean up the testing environment before the next test.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->finder->delete([
            base_path('my-command.php'),
            base_path('stub-override-exists.php'),
            base_path('stub-override-not-exists.php')
        ]);
    }

    /** @test */
    public function it_initializes_a_stub_instance()
    {
        $stub = new Stub('/model.stub', [
            'NAME' => 'Name'
        ]);

        $this->assertTrue(Str::contains($stub->getPath(), 'src/Commands/stubs/model.stub'));
        $this->assertEquals(['NAME' => 'Name'], $stub->getReplaces());
    }

    /** @test */
    public function it_sets_a_new_replaces_array()
    {
        $stub = new Stub('/model.stub', [
            'NAME' => 'Name'
        ]);

        $stub->replace(['VENDOR' => 'MyVendor']);

        $this->assertEquals(['VENDOR' => 'MyVendor'], $stub->getReplaces());
    }

    /** @test */
    public function it_stores_a_stub_to_a_specific_path()
    {
        $stub = new Stub('/command.stub', [
            'COMMAND_NAME' => 'my:command',
            'NAMESPACE'    => 'Blog\Commands',
            'CLASS'        => 'MyCommand'
        ]);

        $stub->saveTo(base_path(), 'my-command.php');

        $this->assertTrue($this->finder->exists(base_path('my-command.php')));
    }

    /** @test */
    public function it_sets_a_new_path()
    {
        $stub = new Stub('/model.stub', [
            'NAME' => 'Name'
        ]);

        $stub->setPath('/new-path/');

        $this->assertTrue(Str::contains($stub->getPath(), 'Commands/stubs/new-path/'));
    }

    /** @test */
    public function it_uses_a_default_stub_if_override_does_not_exist()
    {
        $stub = new Stub('/command.stub', [
            'COMMAND_NAME' => 'my:command',
            'NAMESPACE'    => 'Blog\Commands',
            'CLASS'        => 'MyCommand'
        ]);

        $stub->setBasePath(__DIR__ . '/stubs');

        $stub->saveTo(base_path(), 'stub-override-not-exists.php');

        $this->assertTrue($this->finder->exists(base_path('stub-override-not-exists.php')));
    }

    /** @test */
    public function it_uses_an_override_stub_if_one_exists()
    {
        $stub = new Stub('/model.stub', [
            'NAME' => 'name'
        ]);

        $stub->setBasePath(__DIR__ . '/stubs');

        $stub->saveTo(base_path(), 'stub-override-exists.php');

        $this->assertTrue($this->finder->exists(base_path('stub-override-exists.php')));
        $this->assertEquals('stub-override', $this->finder->get(base_path('stub-override-exists.php')));
    }
}
