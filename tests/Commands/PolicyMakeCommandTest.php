<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Commands\Traits\SetsCommandTestsUp;
use Spatie\Snapshots\MatchesSnapshots;

class PolicyMakeCommandTest extends BaseTestCase
{
	use MatchesSnapshots, SetsCommandTestsUp;

	/** @test */
	public function it_generates_a_policy_class()
	{
		$this->artisan('module:make-policy', ['name' => 'PostPolicy', 'module' => 'Blog']);

		$file = $this->modulePath . '/Policies/PostPolicy.php';

		$this->assertTrue(is_file($file), 'Policy file was not created.');
		$this->assertMatchesSnapshot($this->finder->get($file));
	}

	/** @test */
	public function it_can_change_the_default_namespace()
	{
		$this->app['config']->set('modules.paths.generator.policies.path', 'OtherNamespace');

		$this->artisan('module:make-policy', ['name' => 'PostPolicy', 'module' => 'Blog']);

		$file = $this->finder->get($this->modulePath . '/OtherNamespace/PostPolicy.php');

		$this->assertMatchesSnapshot($file);
	}
}
