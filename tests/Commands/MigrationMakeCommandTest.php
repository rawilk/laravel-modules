<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Commands\Traits\SetsCommandTestsUp;
use Spatie\Snapshots\MatchesSnapshots;

class MigrationMakeCommandTest extends BaseTestCase
{
    use MatchesSnapshots, SetsCommandTestsUp;

    /** @test */
    public function it_generates_a_new_migration_class()
    {
        $this->artisan('module:make-migration', ['name' => 'create_posts_table', 'module' => 'Blog']);

        $files = $this->finder->allFiles($this->modulePath . '/database/migrations');

        $this->assertCount(1, $files);
    }

    /** @test */
    public function it_generates_the_correct_file_with_content()
    {
        $this->artisan('module:make-migration', ['name' => 'create_posts_table', 'module' => 'Blog']);

        $migrations = $this->finder->allFiles($this->modulePath . '/database/migrations');

        $fileName = $migrations[0]->getRelativePathname();
        $file = $this->finder->get($this->modulePath . '/database/migrations/' . $fileName);
        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_generates_correct_add_migration_file_content()
    {
        $this->artisan('module:make-migration', [
            'name'   => 'add_something_to_posts_table',
            'module' => 'Blog'
        ]);

        $migrations = $this->finder->allFiles($this->modulePath . '/database/migrations');
        $fileName = $migrations[0]->getRelativePathname();

        $file = $this->finder->get($this->modulePath . '/database/migrations/' . $fileName);

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_generates_correct_delete_migration_file_content()
    {
        $this->artisan('module:make-migration', [
            'name'   => 'delete_something_from_posts_table',
            'module' => 'Blog'
        ]);

        $migrations = $this->finder->allFiles($this->modulePath . '/database/migrations');
        $fileName = $migrations[0]->getRelativePathname();

        $file = $this->finder->get($this->modulePath . '/database/migrations/' . $fileName);

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_generates_correct_drop_migration_file_content()
    {
        $this->artisan('module:make-migration', [
            'name'   => 'drop_posts_table',
            'module' => 'Blog'
        ]);

        $migrations = $this->finder->allFiles($this->modulePath . '/database/migrations');
        $fileName = $migrations[0]->getRelativePathname();

        $file = $this->finder->get($this->modulePath . '/database/migrations/' . $fileName);

        $this->assertMatchesSnapshot($file);
    }

    /** @test */
    public function it_generates_correct_default_migration_file_content()
    {
        $this->artisan('module:make-migration', [
            'name'   => 'something_random_name',
            'module' => 'Blog'
        ]);

        $migrations = $this->finder->allFiles($this->modulePath . '/database/migrations');
        $fileName = $migrations[0]->getRelativePathname();

        $file = $this->finder->get($this->modulePath . '/database/migrations/' . $fileName);

        $this->assertMatchesSnapshot($file);
    }
}
