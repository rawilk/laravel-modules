<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Concerns\TestsGenerators;

class PublishCommandTest extends BaseTestCase
{
    use TestsGenerators;

    /** @test */
    public function it_publishes_module_assets()
    {
        $this->finder->put($this->modulePath . '/resources/assets/script.js', 'asset_file');

        $this->artisan('module:publish', ['module' => 'Blog']);

        $this->assertTrue(is_file(public_path('modules/blog/script.js')));
    }
}
