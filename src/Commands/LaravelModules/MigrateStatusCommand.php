<?php

namespace Rawilk\LaravelModules\Commands\LaravelModules;

use Illuminate\Console\Command;
use Rawilk\LaravelModules\Migrations\Migrator;
use Rawilk\LaravelModules\Module;

class MigrateStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:migrate-status
                            {module? : The name of the module to get the migration status for}
                            {--d|direction=asc : The direction to load the modules in}
                            {--database= : The database connection to use}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Get the status of a module's migrations";

    /**
     * @var \Rawilk\LaravelModules\Repository
     */
    protected $module;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->module = $this->laravel['modules'];

        $name = $this->argument('module');

        if (! empty($name)) {
            $module = $this->module->findOrFail($name);

            return $this->migrateStatus($module);
        }

        $this->migrateStatusAll();
    }

    /**
     * Get the migration status for all modules.
     */
    private function migrateStatusAll()
    {
        foreach ($this->module->getOrdered($this->option('direction')) as $module) {
            $this->info("Getting status for module: {$module->getName()}");

            $this->migrateStatus($module);
        }
    }

    /**
     * Get the migration status for the given module.
     *
     * @param \Rawilk\LaravelModules\Module $module
     */
    private function migrateStatus(Module $module)
    {
        $path = str_replace(base_path(), '', (new Migrator($module))->getPath());

        $this->call('migrate:status', [
            '--path'     => $path,
            '--database' => $this->option('database'),
        ]);
    }
}
