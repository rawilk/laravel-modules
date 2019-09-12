<?php

namespace Rawilk\LaravelModules\Commands\Other;

use Illuminate\Console\Command;
use Rawilk\LaravelModules\Migrations\Migrator;
use Rawilk\LaravelModules\Module;

class MigrateCommand extends Command
{
    /** @var string */
    protected $signature = 'module:migrate
                            {module? : The name of the module to migrate}
                            {--d|direction=asc : The direction to fetch modules in (only applies when migrating all modules)}
                            {--database= : The database connection to use}
                            {--pretend : Dump the SQL queries that would be run}
                            {--force : Force the operation to run when in production}
                            {--seed : Indicates if the seed task should be re-run}
                            {--subpath= : Indicate a subpath to run your migrations from}';

    /** @var string */
    protected $description = 'Run the migrations from a specific module or all modules.';

    /** @var \Rawilk\LaravelModules\Contracts\Repository */
    protected $module;

    public function handle(): void
    {
        $this->module = $this->laravel['modules'];

        if ($name = $this->argument('module')) {
            $module = $this->module->findOrFail($name);

            $this->migrate($module);

            return;
        }

        /** @var \Rawilk\LaravelModules\Module $module */
        foreach ($this->module->getOrdered($this->option('direction')) as $module) {
            $this->line("Running migrations for module: <info>{$module->getName()}</info>");

            $this->migrate($module);
        }
    }

    private function migrate(Module $module): void
    {
        $path = str_replace(base_path(), '', (new Migrator($module, $this->getLaravel()))->getPath());

        if ($this->option('subpath')) {
            $path .= '/' . $this->option('subpath');
        }

        $this->call('migrate', [
            '--path'     => $path,
            '--database' => $this->option('database'),
            '--pretend'  => $this->option('pretend'),
            '--force'    => $this->option('force')
        ]);

        if ($this->option('seed')) {
            $this->call('module:seed', ['module' => $module->getName()]);
        }
    }
}
