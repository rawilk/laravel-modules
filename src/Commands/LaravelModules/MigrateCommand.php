<?php

namespace Rawilk\LaravelModules\Commands\LaravelModules;

use Illuminate\Console\Command;
use Rawilk\LaravelModules\Migrations\Migrator;
use Rawilk\LaravelModules\Module;

class MigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:migrate
                            {module? : The name of the module to migrate}
                            {--d|direction=asc : The order to load the modules (only applies when migrating all modules)}
                            {--database= : The database connection to use}
                            {--p|pretend : Dump the SQL queries that would be run in the migrations}
                            {--f|force : Force the migrations to run in production}
                            {--s|seed : Indicates seeders should be run}
                            {--subpath= : Indicate a subpath to run your migrations from}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform migrations for the specified module or for all modules';

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

        if ($name = $this->argument('module')) {
            $module = $this->module->findOrFail($name);

            return $this->migrate($module);
        }

        $this->migrateAll();
    }

    /**
     * Run all module's migrations.
     */
    private function migrateAll()
    {
        foreach ($this->module->getOrdered($this->option('direction')) as $module) {
            $this->info("Running migrations for module [{$module->getName()}]");

            $this->migrate($module);
        }
    }

    /**
     * Run the given module's migrations.
     *
     * @param \Rawilk\LaravelModules\Module $module
     */
    private function migrate(Module $module)
    {
        $path = str_replace(base_path(), '', (new Migrator($module))->getPath());

        if ($this->option('subpath')) {
            $path .= '/' . $this->option('subpath');
        }

        $this->call('migrate', [
            '--path'     => $path,
            '--database' => $this->option('database'),
            '--pretend'  => $this->option('pretend'),
            '--force'    => $this->option('force'),
        ]);

        if ($this->option('seed')) {
            $this->call('module:seed', [
                'module' => $module->getName()
            ]);
        }
    }
}
