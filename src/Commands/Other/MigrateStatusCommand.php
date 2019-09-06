<?php

namespace Rawilk\LaravelModules\Commands\Other;

use Illuminate\Console\Command;
use Rawilk\LaravelModules\Migrations\Migrator;
use Rawilk\LaravelModules\Module;

class MigrateStatusCommand extends Command
{
    /** @var string */
    protected $signature = 'module:migrate-status
                            {module? : Show migration status for a specific module}
                            {--d|direction=asc : The direction to order modules in}
                            {--database= : The database connection to use}';

    /** @var string */
    protected $description = 'See the status of module migrations.';

    /** @var \Rawilk\LaravelModules\Contracts\Repository */
    protected $module;

    public function handle(): void
    {
        $this->module = $this->laravel['modules'];

        if ($name = $this->option('module')) {
            $module = $this->module->findOrFail($name);

            $this->migrateStatus($module);

            return;
        }

        /** @var \Rawilk\LaravelModules\Module $module */
        foreach ($this->module->getOrdered($this->option('direction')) as $module) {
            $this->line("Getting migration status for module: <info>{$module->getName()}</info>");

            $this->migrateStatus($module);
        }
    }

    private function migrateStatus(Module $module): void
    {
        $path = str_replace(base_path(), '', (new Migrator($module, $this->getLaravel()))->getPath());

        $this->call('migrate:status', [
            '--path'     => $path,
            '--database' => $this->option('database')
        ]);
    }
}
