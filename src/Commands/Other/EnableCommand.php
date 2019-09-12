<?php

namespace Rawilk\LaravelModules\Commands\Other;

use Illuminate\Console\Command;

class EnableCommand extends Command
{
    /** @var string */
    protected $signature = 'module:enable
                            {module : The module to enable.}';

    /** @var string */
    protected $description = 'Enable the specified module.';

    public function handle(): void
    {
        /** @var \Rawilk\LaravelModules\Module $module */
        $module = $this->laravel['modules']->findOrFail($this->argument('module'));

        if ($module->isDisabled()) {
            $module->enable();

            $this->info("Module [{$module}] was enabled successfully.");
        } else {
            $this->comment("Module [{$module}] is already enabled.");
        }
    }
}
