<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Commands\Traits\SetsCommandTestsUp;
use Spatie\Snapshots\MatchesSnapshots;

class RouteProviderMakeCommandTest extends BaseTestCase
{
	use MatchesSnapshots, SetsCommandTestsUp;

	/** @test */
	public function it_generates_a_new_service_provider_class()
	{
	    $this->artisan('module:route-provider', ['module' => 'Blog']);

	    $this->assertTrue(is_file($this->modulePath . '/Providers/RouteServiceProvider.php'));
	}

	/** @test */
	public function it_generates_the_correct_file_with_content()
	{
		$this->artisan('module:route-provider', ['module' => 'Blog']);

		$file = $this->finder->get($this->modulePath . '/Providers/RouteServiceProvider.php');

		$this->assertMatchesSnapshot($file);
	}

	/** @test */
	public function it_can_change_the_default_namespace()
	{
		$this->app['config']->set('modules.paths.generator.provider.path', 'OtherNamespace');

		$this->artisan('module:route-provider', ['module' => 'Blog']);

		$file = $this->finder->get($this->modulePath . '/OtherNamespace/RouteServiceProvider.php');

		$this->assertMatchesSnapshot($file);
	}
}
