<?php

namespace Rawilk\LaravelModules\Commands\Other;

use Illuminate\Console\Command;
use Rawilk\LaravelModules\Migrations\Migrator;
use Rawilk\LaravelModules\Module;
use Rawilk\LaravelModules\Publishing\MigrationPublisher;

class PublishMigrationCommand extends Command
{
    /** @var string */
    protected $signature = 'module:publish-migration
                            {module? : The name of the module to publish migrations for}';

    /** @var string */
    protected $description = 'Publish the migrations for a module.';

    public function handle(): void
    {
        if ($name = $this->argument('module')) {
            $module = $this->laravel['modules']->findOrFail($name);

            $this->publish($module);

            return;
        }

        /** @var \Rawilk\LaravelModules\Module $module */
        foreach ($this->laravel['modules']->allEnabled() as $module) {
            $this->publish($module);
        }
    }

    private function publish(Module $module): void
    {
        with(new MigrationPublisher(new Migrator($module, $this->getLaravel())))
            ->setRepository($this->laravel['modules'])
            ->setConsole($this)
            ->publish();
    }
}
