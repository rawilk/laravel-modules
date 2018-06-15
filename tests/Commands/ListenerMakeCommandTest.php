<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Commands\Traits\SetsCommandTestsUp;
use Spatie\Snapshots\MatchesSnapshots;

class ListenerMakeCommandTest extends BaseTestCase
{
	use MatchesSnapshots, SetsCommandTestsUp;

	/** @test */
	public function it_generates_a_new_listener_class()
	{
		$this->artisan('module:make-listener', [
			'name'    => 'NotifyUsersOfANewPost',
			'module'  => 'Blog',
			'--event' => 'UserWasCreated'
		]);

		$this->assertTrue(is_file($this->modulePath . '/Listeners/NotifyUsersOfANewPost.php'));
	}

	/** @test */
	public function it_generates_the_correct_file_with_content()
	{
		$this->artisan('module:make-listener', [
			'name'    => 'NotifyUsersOfANewPost',
			'module'  => 'Blog',
			'--event' => 'UserWasCreated'
		]);

		$file = $this->finder->get($this->modulePath . '/Listeners/NotifyUsersOfANewPost.php');

		$this->assertMatchesSnapshot($file);
	}

	/** @test */
	public function it_generates_a_listener_without_an_event()
	{
		$this->artisan('module:make-listener', [
			'name'   => 'NotifyUsersOfANewPost',
			'module' => 'Blog'
		]);

		$file = $this->finder->get($this->modulePath . '/Listeners/NotifyUsersOfANewPost.php');

		$this->assertMatchesSnapshot($file);
	}

	/** @test */
	public function it_generates_a_queued_listener()
	{
		$this->artisan('module:make-listener', [
			'name'     => 'NotifyUsersOfANewPost',
			'module'   => 'Blog',
			'--event'  => 'UserWasCreated',
			'--queued' => true
		]);

		$file = $this->finder->get($this->modulePath . '/Listeners/NotifyUsersOfANewPost.php');

		$this->assertMatchesSnapshot($file);
	}

	/** @test */
	public function it_generates_a_queued_listener_without_an_event()
	{
		$this->artisan('module:make-listener', [
			'name'     => 'NotifyUsersOfANewPost',
			'module'   => 'Blog',
			'--queued' => true
		]);

		$file = $this->finder->get($this->modulePath . '/Listeners/NotifyUsersOfANewPost.php');

		$this->assertMatchesSnapshot($file);
	}

	/** @test */
	public function it_can_change_the_default_namespace()
	{
		$this->app['config']->set('modules.paths.generator.listener.path', 'Events/Handlers');

		$this->artisan('module:make-listener', [
			'name'   => 'NotifyUsersOfANewPost',
			'module' => 'Blog'
		]);

		$file = $this->finder->get($this->modulePath . '/Events/Handlers/NotifyUsersOfANewPost.php');

		$this->assertMatchesSnapshot($file);
	}
}
