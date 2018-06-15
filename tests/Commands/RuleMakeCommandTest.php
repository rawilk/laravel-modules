<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Commands\Traits\SetsCommandTestsUp;
use Spatie\Snapshots\MatchesSnapshots;

class RuleMakeCommandTest extends BaseTestCase
{
	use MatchesSnapshots, SetsCommandTestsUp;

	/** @test */
	public function it_makes_a_new_rule()
	{
		$this->artisan('module:make-rule', ['name' => 'UniqueRule', 'module' => 'Blog']);

		$file = $this->modulePath . '/Rules/UniqueRule.php';

		$this->assertTrue(is_file($file), 'Rule file was not created.');
		$this->assertMatchesSnapshot($this->finder->get($file));
	}

	/** @test */
	public function it_can_change_the_default_namespace()
	{
		$this->app['config']->set('modules.paths.generator.rules.path', 'OtherNamespace');

		$this->artisan('module:make-rule', ['name' => 'UniqueRule', 'module' => 'Blog']);

		$file = $this->finder->get($this->modulePath . '/OtherNamespace/UniqueRule.php');

		$this->assertMatchesSnapshot($file);
	}
}
