<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Illuminate\Support\Facades\Artisan;
use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Commands\Traits\SetsCommandTestsUp;
use Spatie\Snapshots\MatchesSnapshots;

class ModelMakeCommandTest extends BaseTestCase
{
	use MatchesSnapshots, SetsCommandTestsUp;

	/** @test */
	public function it_generates_a_new_model_class()
	{
		$this->artisan('module:make-model', ['model' => 'Post', 'module' => 'Blog']);

		$this->assertTrue(is_file($this->modulePath . '/Models/Post.php'));
	}

	/** @test */
	public function it_generates_the_correct_file_with_content()
	{
		$this->artisan('module:make-model', ['model' => 'Post', 'module' => 'Blog']);

		$file = $this->finder->get($this->modulePath . '/Models/Post.php');

		$this->assertMatchesSnapshot($file);
	}

	/** @test */
	public function it_generates_a_model_with_fillable_fields()
	{
		$this->artisan('module:make-model', [
			'model'      => 'Post',
			'module'     => 'Blog',
			'--fillable' => 'title,slug'
		]);

		$file = $this->finder->get($this->modulePath . '/Models/Post.php');

		$this->assertMatchesSnapshot($file);
	}

	/** @test */
	public function it_generates_a_migration_file_with_model()
	{
		$this->artisan('module:make-model', [
			'model'       => 'Post',
			'module'      => 'Blog',
			'--migration' => true
		]);

		$migrations = $this->finder->allFiles($this->modulePath . '/database/migrations');
		$migrationFile = $migrations[0];
		$migrationContent = $this->finder->get($this->modulePath . '/database/migrations/' . $migrationFile->getFilename());

		$this->assertCount(1, $migrations);
		$this->assertMatchesSnapshot($migrationContent);
	}

	/** @test */
	public function it_generates_with_a_migration_with_correct_name_if_model_name_is_multiple_words()
	{
		$this->artisan('module:make-model', [
			'model'  => 'ProductDetail',
			'module' => 'Blog',
			'-m'     => true
		]);

		$migrations = $this->finder->allFiles($this->modulePath . '/database/migrations');
		$migrationFile = $migrations[0];
		$migrationContent = $this->finder->get($this->modulePath . '/database/migrations/' . $migrationFile->getFilename());

		$this->assertContains('create_product_details_table', $migrationFile->getFilename());
		$this->assertMatchesSnapshot($migrationContent);
	}

	/** @test */
	public function it_displays_an_error_if_model_already_exists()
	{
		$this->artisan('module:make-model', ['model' => 'Post', 'module' => 'Blog']);
		$this->artisan('module:make-model', ['model' => 'Post', 'module' => 'Blog']);

		$this->assertContains('already exists', Artisan::output());
	}

	/** @test */
	public function it_can_change_the_default_namespace()
	{
		$this->app['config']->set('modules.paths.generator.model.path', 'OtherNamespace');

		$this->artisan('module:make-model', ['model' => 'Post', 'module' => 'Blog']);

		$file = $this->finder->get($this->modulePath . '/OtherNamespace/Post.php');

		$this->assertMatchesSnapshot($file);
	}
}
