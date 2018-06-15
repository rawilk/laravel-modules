<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Commands\Traits\SetsCommandTestsUp;
use Spatie\Snapshots\MatchesSnapshots;

class MailMakeCommandTest extends BaseTestCase
{
	use MatchesSnapshots, SetsCommandTestsUp;

	/** @test */
	public function it_generates_a_mailable()
	{
		$this->artisan('module:make-mail', ['name' => 'SomeMail', 'module' => 'Blog']);

		$this->assertTrue(is_file($this->modulePath . '/Mail/SomeMail.php'));
	}

	/** @test */
	public function it_generates_the_correct_file_with_content()
	{
		$this->artisan('module:make-mail', ['name' => 'SomeMail', 'module' => 'Blog']);

		$file = $this->finder->get($this->modulePath . '/Mail/SomeMail.php');

		$this->assertMatchesSnapshot($file);
	}

	/** @test */
	public function it_can_change_the_default_namespace()
	{
		$this->app['config']->set('modules.paths.generator.emails.path', 'OtherNamespace');

		$this->artisan('module:make-mail', ['name' => 'SomeMail', 'module' => 'Blog']);

		$file = $this->finder->get($this->modulePath . '/OtherNamespace/SomeMail.php');

		$this->assertMatchesSnapshot($file);
	}
}
