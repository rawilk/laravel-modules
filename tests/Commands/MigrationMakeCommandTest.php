<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Concerns\TestsGenerators;
use Spatie\Snapshots\MatchesSnapshots;

class MigrationMakeCommandTest extends BaseTestCase
{
    use MatchesSnapshots, TestsGenerators;

    /** @test */
    public function it_generates_a_migration_class()
    {
        $this->artisan('module:make-migration', ['name' => 'create_posts_table', 'module' => 'Blog']);

        $files = $this->finder->allFiles($this->modulePath . '/database/migrations');

        $this->assertCount(1, $files);
    }

    /** @test */
    public function it_generates_the_correct_file_with_content()
    {
        $this->artisan('module:make-migration', ['name' => 'create_posts_table', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/database/migrations/' . $this->getMigrationFile());

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_generates_the_correct_add_migration_file_content()
    {
        $this->artisan('module:make-migration', ['name' => 'add_something_to_posts_table', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/database/migrations/' . $this->getMigrationFile());

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_generates_the_correct_delete_migration_file_content()
    {
        $this->artisan('module:make-migration', ['name' => 'delete_something_from_posts_table', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/database/migrations/' . $this->getMigrationFile());

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_generates_the_correct_drop_migration_file_content()
    {
        $this->artisan('module:make-migration', ['name' => 'drop_posts_table', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/database/migrations/' . $this->getMigrationFile());

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_generates_the_correct_default_migration_file_content()
    {
        $this->artisan('module:make-migration', ['name' => 'some_random_name', 'module' => 'Blog']);

        $file = $this->finder->get($this->modulePath . '/database/migrations/' . $this->getMigrationFile());

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_generates_foreign_key_constraints()
    {
        $this->artisan('module:make-migration', [
            'name'     => 'create_posts_table',
            'module'   => 'Blog',
            '--fields' => 'belongsTo:user:id:users'
        ]);

        $file = $this->finder->get($this->modulePath . '/database/migrations/' . $this->getMigrationFile());

        $this->assertMatchesSnapshot($file);
    }

    private function getMigrationFile(): string
    {
        $migrations = $this->finder->allFiles($this->modulePath . '/database/migrations');

        return $migrations[0]->getRelativePathname();
    }
}
