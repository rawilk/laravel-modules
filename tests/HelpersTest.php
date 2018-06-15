<?php

namespace Rawilk\LaravelModules\Tests;

use Rawilk\LaravelModules\Tests\Commands\Traits\SetsCommandTestsUp;

class HelpersTest extends BaseTestCase
{
    use SetsCommandTestsUp;

    /** @test */
    public function it_finds_the_module_path()
    {
        $this->assertTrue(str_contains(module_path('Blog'), 'Modules/Blog'));
    }
}
