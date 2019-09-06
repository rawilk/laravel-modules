<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Concerns\TestsGenerators;

class ListCommandTest extends BaseTestCase
{
    use TestsGenerators;

    /** @test */
    public function it_can_list_modules()
    {
        $exitCode = $this->artisan('module:list');

        $this->assertEquals(0, $exitCode);
    }
}
