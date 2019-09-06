<?php

namespace Rawilk\LaravelModules\Migrations;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Rawilk\LaravelModules\Module;
use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;

class Migrator
{
    /** @var string */
    protected $database = '';

    /** @var \Illuminate\Contracts\Foundation\Application */
    protected $laravel;

    /** @var \Rawilk\LaravelModules\Module */
    protected $module;

    /**
     * @param \Rawilk\LaravelModules\Module $module
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct(Module $module, Application $app)
    {
        $this->module = $module;
        $this->laravel = $app;
    }

    public function down(string $migration): void
    {
        $this->resolve($migration)->down();
    }

    public function find(string $migration): object
    {
        return $this->table()->whereMigration($migration);
    }

    public function getMigrations(bool $reverse = false): array
    {
        $files = $this->laravel['files']->glob($this->getPath() . '/*_*.php');

        // Once we have the array of files in the directory we will just remove the
        // extension and take the basename of the file which is all we need when
        // finding the migrations that haven't been run against the database.
        if ($files === false) {
            return [];
        }

        $files = array_map(static function ($file) {
            return str_replace('.php', '', basename($file));
        }, $files);

        // Once we have all of the formatted file names we will sort them and since
        // they all start with a timestamp this should give us the migrations in
        // the order they were actually created by the application developers.
        sort($files);

        if ($reverse) {
            return array_reverse($files);
        }

        return $files;
    }

    public function getLast(?array $migrations): Collection
    {
        $rows = $this->table()
            ->where('batch', $this->getLastBatchNumber($migrations))
            ->whereIn('migration', $migrations)
            ->orderBy('migration', 'desc')
            ->get();

        return collect($rows)->map(static function ($row) {
            return (array) $row;
        })->pluck('migration');
    }

    public function getLastBatchNumber(?array $migrations = null): int
    {
        $table = $this->table();

        if (is_array($migrations)) {
            $table = $table->whereIn('migration', $migrations);
        }

        return $table->max('batch');
    }

    public function getModule(): Module
    {
        return $this->module;
    }

    public function getNextBatchNumber(): int
    {
        return $this->getLastBatchNumber() + 1;
    }

    public function getPath(): string
    {
        $config = $this->module->get('migration');

        $migrationPath = GenerateConfigReader::read('migration');
        $path = (is_array($config) && array_key_exists('path', $config))
            ? $config['path']
            : $migrationPath->getPath();

        return $this->module->getExtraPath($path);
    }

    public function getRan(): Collection
    {
        return $this->table()->pluck('migration');
    }

    public function log(string $migration): bool
    {
        return $this->table()->insert([
            'migration' => $migration,
            'batch'     => $this->getNextBatchNumber()
        ]);
    }

    public function requireFiles(array $files): void
    {
        $path = $this->getPath();

        foreach ($files as $file) {
            $this->laravel['files']->requireOnce("{$path}/{$file}.php");
        }
    }

    public function reset(): array
    {
        $migrations = $this->getMigrations(true);

        $this->requireFiles($migrations);

        $migrated = [];

        foreach ($migrations as $migration) {
            $data = $this->find($migration);

            if ($data->count()) {
                $migrated[] = $migration;

                $this->down($migration);

                $data->delete();
            }
        }

        return $migrated;
    }

    public function resolve(string $file): object
    {
        $file = implode('_', array_slice(explode('_', $file), 4));

        $class = Str::studly($file);

        return new $class;
    }

    public function rollback(): array
    {
        $migrations = $this->getLast($this->getMigrations(true));

        $this->requireFiles($migrations->toArray());

        $migrated = [];

        foreach ($migrations as $migration) {
            $data = $this->find($migration);

            if ($data->count()) {
                $migrated[] = $migration;

                $this->down($migration);

                $data->delete();
            }
        }

        return $migrated;
    }

    public function setDatabase($database): self
    {
        if (is_string($database) && $database) {
            $this->database = $database;
        }

        return $this;
    }

    public function table(): Builder
    {
        return $this->laravel['db']->connection($this->database ?: null)->table(config('database.migrations'));
    }

    public function up(string $migration): void
    {
        $this->resolve($migration)->up();
    }
}
