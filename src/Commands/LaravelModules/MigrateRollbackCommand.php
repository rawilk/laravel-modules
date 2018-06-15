<?php

namespace Rawilk\LaravelModules\Commands\LaravelModules;

use Illuminate\Console\Command;
use Rawilk\LaravelModules\Migrations\Migrator;
use Rawilk\LaravelModules\Traits\LoadsMigrations;

class MigrateRollbackCommand extends Command
{
    use LoadsMigrations;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:migrate-rollback
                            {module? : The name of the module to roll migrations back for}
                            {--d|direction=desc : The order to the modules in}
                            {--database= : The database connection to use}
                            {--f|force : Force the operation to run when in production}
                            {--p|pretend : Dump the SQL queries that would be run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback migrations for the specified module';

    /**
     * @var \Rawilk\LaravelModules\Repository
     */
    protected $module;

    /**
     * Execute the console command.
     *
     * @throws \Rawilk\LaravelModules\Exceptions\ModuleNotFoundException
     */
    public function handle()
    {
        $this->module = $this->laravel['modules'];

        $name = $this->argument('module');

        if (! empty($name)) {
            return $this->rollback($name);
        }

        $this->rollbackAll();
    }

    /**
     * Rollback migrations for all modules.
     *
     * @throws \Rawilk\LaravelModules\Exceptions\ModuleNotFoundException
     */
    private function rollbackAll()
    {
        foreach ($this->module->getOrdered($this->option('direction')) as $module) {
            $this->info("Rolling back migrations for module: {$module->getName()}");

            $this->rollback($module);
        }
    }

    /**
     * Rollback the given module's migrations.
     *
     * @param string|\Rawilk\LaravelModules\Module $module
     * @throws \Rawilk\LaravelModules\Exceptions\ModuleNotFoundException
     */
    private function rollback($module)
    {
        if (is_string($module)) {
            $module = $this->module->findOrFail($module);
        }

        $migrator = new Migrator($module);

        $database = $this->option('database');

        if (! empty($database)) {
            $migrator->setDatabase($database);
        }

        $migrated = $migrator->rollback();

        if (count($migrated)) {
            foreach ($migrated as $migration) {
                $this->info("Rolling back migration: {$migration}");
            }

            return;
        }

        $this->comment('Nothing to rollback.');
    }
}
