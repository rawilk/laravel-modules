<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Commands\Traits\SetsCommandTestsUp;
use Spatie\Snapshots\MatchesSnapshots;

class TestMakeCommandTest extends BaseTestCase
{
	use MatchesSnapshots, SetsCommandTestsUp;

	/** @test */
	public function it_generates_a_new_test_class()
	{
		$this->artisan('module:make-test', ['name' => 'PostTest', 'module' => 'Blog']);

		$this->assertTrue(is_file($this->modulePath . '/Tests/PostTest.php'));
	}

	/** @test */
	public function it_generates_the_correct_file_with_content()
	{
		$this->artisan('module:make-test', ['name' => 'PostTest', 'module' => 'Blog']);

		$file = $this->finder->get($this->modulePath . '/Tests/PostTest.php');

		$this->assertMatchesSnapshot($file);
	}

	/** @test */
	public function it_can_change_the_default_namespace()
	{
		$this->app['config']->set('modules.paths.generator.test.path', 'OtherNamespace');

		$this->artisan('module:make-test', ['name' => 'PostTest', 'module' => 'Blog']);

		$file = $this->finder->get($this->modulePath . '/OtherNamespace/PostTest.php');

		$this->assertMatchesSnapshot($file);
	}
}
