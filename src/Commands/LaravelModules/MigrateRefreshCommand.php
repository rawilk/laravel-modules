<?php

namespace Rawilk\LaravelModules\Commands\LaravelModules;

use Illuminate\Console\Command;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class MigrateRefreshCommand extends Command
{
    use ModuleCommands;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:migrate-refresh
                            {module? : The name of the module to re-run migrations for}
                            {--database= : The database connection to use}
                            {--f|force : Force the operation to run when in production}
                            {--s|seed : Indicates seeders should be run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback & re-run migrations for the specified module';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('module:migrate-reset', [
            'module'     => $this->getModuleName(),
            '--database' => $this->option('database'),
            '--force'    => $this->option('force'),
        ]);

        $this->call('module:migrate', [
            'module'     => $this->getModuleName(),
            '--database' => $this->option('database'),
            '--force'    => $this->option('force')
        ]);

        if ($this->option('seed')) {
            $this->call('module:seed', [
                'module' => $this->getModuleName()
            ]);
        }
    }

    /**
     * Get the module name.
     *
     * @return string
     */
    public function getModuleName()
    {
        $module = $this->argument('module');

        $module = app('modules')->find($module);

        return is_null($module) ? $module : $module->getStudlyName();
    }
}
