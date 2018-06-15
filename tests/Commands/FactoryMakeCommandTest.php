<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Commands\Traits\SetsCommandTestsUp;
use Spatie\Snapshots\MatchesSnapshots;

class FactoryMakeCommandTest extends BaseTestCase
{
    use MatchesSnapshots, SetsCommandTestsUp;

    /** @test */
    public function it_makes_a_new_factory()
    {
        $this->artisan('module:make-factory', ['name' => 'PostFactory', 'module' => 'Blog']);

        $file = $this->modulePath . '/database/factories/PostFactory.php';

        $this->assertTrue(is_file($file), 'Factory file was not created.');
        $this->assertMatchesSnapshot($this->finder->get($file));
    }
}
