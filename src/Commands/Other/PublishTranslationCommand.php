<?php

namespace Rawilk\LaravelModules\Commands\Other;

use Illuminate\Console\Command;
use Rawilk\LaravelModules\Module;
use Rawilk\LaravelModules\Publishing\LangPublisher;

class PublishTranslationCommand extends Command
{
    /** @var string */
    protected $signature = 'module:publish-translation
                            {module? : The name of the module to publish translations for}';

    /** @var string */
    protected $description = 'Publish the translations for a module.';

    public function handle(): void
    {
        if ($name = $this->argument('module')) {
            $this->publish($name);

            return;
        }

        $this->publishAll();
    }

    private function publish($name): void
    {
        $module = $name instanceof Module
            ? $name
            : $this->laravel['modules']->findOrFail($name);

        with(new LangPublisher($module))
            ->setRepository($this->laravel['modules'])
            ->setConsole($this)
            ->publish();

        $this->line("<info>Published Translations</info>: {$module->getStudlyName()}");
    }

    private function publishAll(): void
    {
        /** @var \Rawilk\LaravelModules\Module $module */
        foreach ($this->laravel['modules']->allEnabled() as $module) {
            $this->publish($module);
        }
    }
}
