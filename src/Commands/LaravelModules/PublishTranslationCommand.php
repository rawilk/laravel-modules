<?php

namespace Rawilk\LaravelModules\Commands\LaravelModules;

use Illuminate\Console\Command;
use Rawilk\LaravelModules\Module;
use Rawilk\LaravelModules\Publishing\LangPublisher;

class PublishTranslationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:publish-translation
                            {module? : The name of the module to publish translations for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Publish a module's translations to the application";

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
     * Publish translations for all modules.
     */
    private function publishAll()
    {
        foreach ($this->laravel['modules']->allEnabled() as $module) {
            $this->publish($module);
        }
    }

    /**
     * Publish translations for the given module.
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

        with(new LangPublisher($module))
            ->setRepository($this->laravel['modules'])
            ->setConsole($this)
            ->publish();

        $this->info("Published translations for module [{$module->getStudlyName()}]");
    }
}
