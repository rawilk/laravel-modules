<?php

namespace Rawilk\LaravelModules\Commands\LaravelModules;

use Illuminate\Console\Command;
use Rawilk\LaravelModules\Module;
use Rawilk\LaravelModules\Publishing\AssetPublisher;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:publish
                            {module? : The name of the module to publish assets for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Publish a module's assets to the application";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($name = $this->argument('module')) {
            return $this->publish($name);
        }

        $this->publishAll();
    }

    /**
     * Publish assets for all enabled modules.
     */
    private function publishAll()
    {
        foreach ($this->laravel['modules']->allEnabled() as $module) {
            $this->publish($module);
        }
    }

    /**
     * Publish the given module's assets.
     *
     * @param string|\Rawilk\LaravelModules\Module $name
     */
    private function publish($name)
    {
        if ($name instanceof Module) {
            $module = $name;
        } else {
            $module = $this->laravel['modules']->findOrFail($name);
        }

        with(new AssetPublisher($module))
            ->setRepository($this->laravel['modules'])
            ->setConsole($this)
            ->publish();

        $this->info("Published assets for module [{$module->getStudlyName()}]");
    }
}
