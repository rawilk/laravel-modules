<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;

class PublishCommandTest extends BaseTestCase
{
	/**
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	private $finder;

	/**
	 * @var string
	 */
	private $modulePath;

	/**
	 * Setup the test environment.
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();

		$this->modulePath = base_path('Modules/Blog');
		$this->finder = $this->app['files'];
		$this->artisan('module:make', ['name' => ['Blog']]);
		$this->finder->put($this->modulePath . '/assets/script.js', 'assetfile');
	}

	/**
	 * Clean up the testing environment before the next test.
	 *
	 * @return void
	 */
	public function tearDown()
	{
		$this->finder->deleteDirectory($this->modulePath);

		parent::tearDown();
	}

	/** @test */
	public function it_publishes_module_assets()
	{
	    $this->artisan('module:publish', ['module' => 'Blog']);

	    $this->assertTrue(is_file(public_path('modules/blog/script.js')));
	}
}
