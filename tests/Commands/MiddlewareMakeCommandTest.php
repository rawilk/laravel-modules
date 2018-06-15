<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Commands\Traits\SetsCommandTestsUp;
use Spatie\Snapshots\MatchesSnapshots;

class MiddlewareMakeCommandTest extends BaseTestCase
{
	use MatchesSnapshots, SetsCommandTestsUp;

	/** @test */
	public function it_generates_a_new_middleware_class()
	{
		$this->artisan('module:make-middleware', ['name' => 'SomeMiddleware', 'module' => 'Blog']);

		$this->assertTrue(is_file($this->modulePath . '/Http/Middleware/SomeMiddleware.php'));
	}

	/** @test */
	public function it_generates_the_correct_file_with_content()
	{
		$this->artisan('module:make-middleware', ['name' => 'SomeMiddleware', 'module' => 'Blog']);

		$file = $this->finder->get($this->modulePath . '/Http/Middleware/SomeMiddleware.php');

		$this->assertMatchesSnapshot($file);
	}

	/** @test */
	public function it_can_change_the_default_namespace()
	{
		$this->app['config']->set('modules.paths.generator.filter.path', 'OtherNamespace');

		$this->artisan('module:make-middleware', ['name' => 'SomeMiddleware', 'module' => 'Blog']);

		$file = $this->finder->get($this->modulePath . '/OtherNamespace/SomeMiddleware.php');

		$this->assertMatchesSnapshot($file);
	}
}
