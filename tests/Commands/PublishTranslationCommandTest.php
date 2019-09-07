<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Concerns\TestsGenerators;

class PublishTranslationCommandTest extends BaseTestCase
{
    use TestsGenerators;

    /** @test */
    public function it_publishes_module_translations()
    {
        $this->artisan('module:publish-translation', ['module' => 'Blog']);

        $this->assertDirectoryExists(base_path('resources/lang/blog'));
    }
}
