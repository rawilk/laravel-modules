<?php

namespace Rawilk\LaravelModules\Tests\Commands\Traits;

trait SetsCommandTestsUp
{
	/**
	 * @var \Illuminate\Contracts\Filesystem\Filesystem
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
}
