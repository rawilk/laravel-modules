<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Illuminate\Support\Facades\Artisan;
use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Concerns\TestsGenerators;
use Spatie\Snapshots\MatchesSnapshots;

class ModelMakeCommandTest extends BaseTestCase
{
    use MatchesSnapshots, TestsGenerators;

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
    public function it_generates_the_correct_fillable_fields()
    {
        $this->artisan('module:make-model', ['model' => 'Post', 'module' => 'Blog', '--fillable' => 'title,slug']);

        $file = $this->finder->get($this->modulePath . '/Models/Post.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_generates_a_migration_file_with_the_model()
    {
        $this->artisan('module:make-model', ['model' => 'Post', 'module' => 'Blog', '--migration' => true]);

        $migration = $this->finder->get($this->modulePath . '/database/migrations/' . $this->getMigrationFile());

        $this->assertCount(1, $this->finder->allFiles($this->modulePath . '/database/migrations'));
        $this->assertMatchesSnapshot($migration);
    }

    /** @test */
    public function it_generates_a_migration_file_with_model_using_a_shortcut_option()
    {
        $this->artisan('module:make-model', ['model' => 'Post', 'module' => 'Blog', '-m' => true]);

        $migration = $this->finder->get($this->modulePath . '/database/migrations/' . $this->getMigrationFile());

        $this->assertCount(1, $this->finder->allFiles($this->modulePath . '/database/migrations'));
        $this->assertMatchesSnapshot($migration);
    }

    /** @test */
    public function it_generates_the_correct_migration_file_name_with_multiple_words_in_the_model_name()
    {
        $this->artisan('module:make-model', ['model' => 'ProductDetail', 'module' => 'Blog', '-m' => true]);

        $migrations = $this->finder->allFiles($this->modulePath . '/database/migrations');
        $migrationFile = $migrations[0];
        $migration = $this->finder->get($this->modulePath . '/database/migrations/' . $migrationFile->getRelativePathname());

        $this->assertStringContainsString('create_product_details_table', $migrationFile->getFilename());
        $this->assertMatchesSnapshot($migration);
    }

    /** @test */
    public function it_displays_an_error_if_the_model_already_exists()
    {
        $this->artisan('module:make-model', ['model' => 'Post', 'module' => 'Blog']);
        $this->artisan('module:make-model', ['model' => 'Post', 'module' => 'Blog']);

        $this->assertStringContainsString('already exists', Artisan::output());
    }

    /** @test */
    public function it_can_change_the_default_namespace()
    {
        $this->app['config']->set('modules.paths.generator.model.path', 'Entities');

        $this->artisan('module:make-model', ['model' => 'Post', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Entities/Post.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_change_the_default_namespace_specifically()
    {
        $this->app['config']->set('modules.paths.generator.model.namespace', 'Entities');

        $this->artisan('module:make-model', ['model' => 'Post', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Models/Post.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_generate_a_model_in_a_nested_namespace()
    {
        $this->artisan('module:make-model', ['model' => 'Nested/Folder/Post', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/Models/Nested/Folder/Post.php');

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_can_use_a_custom_base_model_class()
    {
        $this->artisan('module:make-model', ['model' => 'Post', 'module' => 'Blog', '--base_class' => 'App/Models/BaseModel']);

        $file = $this->finder->get($this->modulePath . '/Models/Post.php');

        $this->assertMatchesSnapshot($file);
    }

    private function getMigrationFile(): string
    {
        $migrations = $this->finder->allFiles($this->modulePath . '/database/migrations');

        return $migrations[0]->getRelativePathname();
    }
}
