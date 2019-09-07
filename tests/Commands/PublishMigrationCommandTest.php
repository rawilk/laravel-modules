<?php

namespace Rawilk\LaravelModules\Tests\Commands;

use Rawilk\LaravelModules\Tests\BaseTestCase;
use Rawilk\LaravelModules\Tests\Concerns\TestsGenerators;

class PublishMigrationCommandTest extends BaseTestCase
{
    use TestsGenerators;

    /** @test */
    public function it_publishes_module_migrations()
    {
        $this->deleteMigrations();

        $this->artisan('module:make-migration', ['name' => 'create_posts_table', 'module' => 'Blog']);
        $this->artisan('module:publish-migration', ['module' => 'Blog']);

        $files = $this->finder->allFiles(base_path('database/migrations'));

        $this->assertCount(1, $files);

        $this->deleteMigrations();
    }

    private function deleteMigrations(): void
    {
        $this->finder->delete($this->finder->allFiles(base_path('database/migrations')));
    }
}
