<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Commands\Traits\SetsCommandTestsUp;
use Spatie\Snapshots\MatchesSnapshots;

class RequestMakeCommandTest extends BaseTestCase
{
	use MatchesSnapshots, SetsCommandTestsUp;

	/** @test */
	public function it_generates_a_new_form_request_class()
	{
		$this->artisan('module:make-request', ['name' => 'CreateBlogPostRequest', 'module' => 'Blog']);

		$this->assertTrue(is_file($this->modulePath . '/Http/Requests/CreateBlogPostRequest.php'));
	}

	/** @test */
	public function it_generates_the_correct_file_with_content()
	{
		$this->artisan('module:make-request', ['name' => 'CreateBlogPostRequest', 'module' => 'Blog']);

		$file = $this->finder->get($this->modulePath . '/Http/Requests/CreateBlogPostRequest.php');

		$this->assertMatchesSnapshot($file);
	}

	/** @test */
	public function it_can_override_the_class_it_extends()
	{
	    $this->artisan('module:make-request', [
		    'name'         => 'CreateBlogPostRequest',
		    'module'       => 'Blog',
		    '--base_class' => 'Some/Other/RequestClass',
	    ]);

		$file = $this->finder->get($this->modulePath . '/Http/Requests/CreateBlogPostRequest.php');

		$this->assertMatchesSnapshot($file);
	}

	/** @test */
	public function it_can_change_the_default_namespace()
	{
		$this->app['config']->set('modules.paths.generator.request.path', 'OtherNamespace');

		$this->artisan('module:make-request', ['name' => 'CreateBlogPostRequest', 'module' => 'Blog']);

		$file = $this->finder->get($this->modulePath . '/OtherNamespace/CreateBlogPostRequest.php');

		$this->assertMatchesSnapshot($file);
	}
}
