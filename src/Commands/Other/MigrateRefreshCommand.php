<?php

namespace Rawilk\LaravelModules\Commands\Other;

use Illuminate\Console\Command;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class MigrateRefreshCommand extends Command
{
    use ModuleCommands;

    /** @var string */
    protected $signature = 'module:migrate-refresh
                            {module? : The name of the module to refresh migrations for}
                            {--database= : The database connection to use}
                            {--force : Force the operation to run when in production}
                            {--seed : Indicates if the seed task should be re-run}';

    /** @var string */
    protected $description = 'Rollback & re-migrate the migrations for the specified module.';

    public function handle(): void
    {
        $this->call('module:migrate-reset', [
            'module'     => $this->getModuleName(),
            '--database' => $this->option('database'),
            '--force'    => $this->option('force')
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

    private function getModuleName(): ?string
    {
        $module = $this->argument('module');

        /** @var \Rawilk\LaravelModules\Module $module */
        $module = $this->laravel['modules']->find($module);

        if ($module === null) {
            return $module;
        }

        return $module->getStudlyName();
    }
}
