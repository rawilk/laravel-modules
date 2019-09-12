<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Concerns\TestsGenerators;
use Spatie\Snapshots\MatchesSnapshots;

class FactoryMakeCommandTest extends BaseTestCase
{
    use MatchesSnapshots, TestsGenerators;

    /** @test */
    public function it_makes_a_factory()
    {
        $this->artisan('module:make-factory', ['name' => 'PostFactory', 'module' => 'Blog']);

        $path = $this->modulePath . '/database/factories/PostFactory.php';

        $this->assertTrue(is_file($path), 'Factory file was not created.');
        $this->assertMatchesSnapshot($this->finder->get($path));
    }

    /** @test */
    public function it_can_make_a_factory_for_a_model()
    {
        $this->artisan('module:make-factory', [
            'name'    => 'PostFactory',
            'module'  => 'Blog',
            '--model' => 'Modules\Blog\Post'
        ]);

        $file = $this->finder->get($this->modulePath . '/database/factories/PostFactory.php');

        $this->assertMatchesSnapshot($file);
    }
}
