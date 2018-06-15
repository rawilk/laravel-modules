<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Commands\Traits\SetsCommandTestsUp;

class PublishTranslationCommandTest extends BaseTestCase
{
	use SetsCommandTestsUp;

	/** @test */
	public function it_publishes_module_translations()
	{
	    $this->artisan('module:publish-translation', ['module' => 'Blog']);

	    $this->assertTrue(is_dir(base_path('resources/lang/blog')));
	}
}
