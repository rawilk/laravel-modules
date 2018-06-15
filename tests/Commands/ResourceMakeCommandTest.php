<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Commands\Traits\SetsCommandTestsUp;
use Spatie\Snapshots\MatchesSnapshots;

class ResourceMakeCommandTest extends BaseTestCase
{
	use MatchesSnapshots, SetsCommandTestsUp;

	/** @test */
	public function it_generates_a_new_resource_class()
	{
		$this->artisan('module:make-resource', ['name' => 'PostTransformer', 'module' => 'Blog']);

		$this->assertTrue(is_file($this->modulePath . '/Transformers/PostTransformer.php'));
	}

	/** @test */
	public function it_generates_the_correct_file_with_content()
	{
		$this->artisan('module:make-resource', ['name' => 'PostTransformer', 'module' => 'Blog']);

		$file = $this->finder->get($this->modulePath . '/Transformers/PostTransformer.php');

		$this->assertMatchesSnapshot($file);
	}

	/** @test */
	public function it_can_generate_a_collection_resource_class()
	{
		$this->artisan('module:make-resource', [
			'name'         => 'PostTransformer',
			'module'       => 'Blog',
			'--collection' => true
		]);

		$file = $this->finder->get($this->modulePath . '/Transformers/PostTransformer.php');

		$this->assertMatchesSnapshot($file);
	}

	/** @test */
	public function it_can_change_the_default_namespace()
	{
		$this->app['config']->set('modules.paths.generator.resource.path', 'Http/Resources');

		$this->artisan('module:make-resource', [
			'name'         => 'PostTransformer',
			'module'       => 'Blog',
			'--collection' => true
		]);

		$file = $this->finder->get($this->modulePath . '/Http/Resources/PostTransformer.php');

		$this->assertMatchesSnapshot($file);
	}
}
