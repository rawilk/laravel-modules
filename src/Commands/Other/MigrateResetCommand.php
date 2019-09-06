<?php

namespace Rawilk\LaravelModules\Commands\Other;

use Illuminate\Console\Command;
use Rawilk\LaravelModules\Migrations\Migrator;

class MigrateResetCommand extends Command
{
    /** @var string */
    protected $signature = 'module:migrate-reset
                            {module? : The module to reset migrations for}
                            {--d|direction=desc : The direction to sort modules in}
                            {--force : Force the operation to run when in production}
                            {--pretend : Dump the SQL queries that would be run}';

    /** @var string */
    protected $description = 'Reset migrations for the specified module.';

    /** @var \Rawilk\LaravelModules\Contracts\Repository */
    protected $module;

    public function handle(): void
    {
        $this->module = $this->laravel['modules'];

        if ($name = $this->argument('module')) {
            $this->reset($name);

            return;
        }

        /** @var \Rawilk\LaravelModules\Module $module */
        foreach ($this->module->getOrdered($this->option('direction')) as $module) {
            $this->line("Resetting migrations for module: <info>{$module->getName()}</info>");

            $this->reset($module);
        }
    }

    private function reset($module): void
    {
        if (is_string($module)) {
            $module = $this->module->findOrFail($module);
        }

        $migrator = new Migrator($module, $this->getLaravel());

        if ($database = $this->option('database')) {
            $migrator->setDatabase($database);
        }

        $migrated = $migrator->reset();

        if (count($migrated) > 0) {
            foreach ($migrated as $migration) {
                $this->line("Migration rolled back: <info>{$migration}</info>");
            }

            return;
        }

        $this->comment('Nothing to rollback.');
    }
}
