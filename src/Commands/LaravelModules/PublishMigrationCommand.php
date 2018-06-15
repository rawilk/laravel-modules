<?php

namespace Rawilk\LaravelModules\Commands\LaravelModules;

use Illuminate\Console\Command;
use Rawilk\LaravelModules\Migrations\Migrator;
use Rawilk\LaravelModules\Publishing\MigrationPublisher;

class PublishMigrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:publish-migration
                            {module? : The name of the module to publish migrations for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Publish a module's migrations to the application";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($name = $this->argument('module')) {
            $module = $this->laravel['modules']->findOrFail($name);

            return $this->publish($module);
        }

        $this->publishAll();
    }

    /**
     * Publish migrations for all enabled modules.
     */
    private function publishAll()
    {
        foreach ($this->laravel['modules']->allEnabled() as $module) {
            $this->publish($module);
        }
    }

    /**
     * Publish migrations for the given module.
     *
     * @param \Rawilk\LaravelModules\Module $module
     */
    private function publish($module)
    {
        with(new MigrationPublisher(new Migrator($module)))
            ->setRepository($this->laravel['modules'])
            ->setConsole($this)
            ->publish();
    }
}
