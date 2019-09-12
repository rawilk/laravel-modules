<?php

namespace Rawilk\LaravelModules\Commands\Other;

use Illuminate\Console\Command;
use Rawilk\LaravelModules\Migrations\Migrator;

class MigrateRollbackCommand extends Command
{
    /** @var string */
    protected $signature = 'module:migrate-rollback
                            {module? : The name of the module to rollback migrations for}
                            {--d|direction=desc : The direction to sort modules}
                            {--force : Force the operation to run when in production}
                            {--pretend : Dump the SQL queries that would be run}';

    /** @var string */
    protected $description = 'Rollback migrations for a module.';

    /** @var \Rawilk\LaravelModules\Contracts\Repository */
    protected $module;

    public function handle(): void
    {
        $this->module = $this->laravel['modules'];

        if ($name = $this->argument('module')) {
            $this->rollback($name);

            return;
        }

        /** @var \Rawilk\LaravelModules\Module $module */
        foreach ($this->module->getOrdered($this->option('direction')) as $module) {
            $this->line("Rolling back migrations for module: <info>{$module->getName()}</info>");

            $this->rollback($module);
        }
    }

    private function rollback($module): void
    {
        if (is_string($module)) {
            $module = $this->module->findOrFail($module);
        }

        $migrator = new Migrator($module, $this->getLaravel());

        if ($database = $this->option('database')) {
            $migrator->setDatabase($database);
        }

        $migrated = $migrator->rollback();

        if (count($migrated) > 0) {
            foreach ($migrated as $migration) {
                $this->line("Migration rolled back: <info>{$migration}</info>");
            }

            return;
        }

        $this->comment('Nothing to rollback.');
    }
}
