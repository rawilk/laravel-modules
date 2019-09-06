<?php

namespace Rawilk\LaravelModules\Commands\Other;

use Illuminate\Console\Command;

class DisableCommand extends Command
{
    /** @var string */
    protected $signature = 'module:disable
                            {module : The module to disable.}';

    /** @var string */
    protected $description = 'Disable the specified module.';

    public function handle(): void
    {
        /** @var \Rawilk\LaravelModules\Module $module */
        $module = $this->laravel['modules']->findOrFail($this->argument('module'));

        if ($module->isEnabled()) {
            $module->disable();

            $this->info("Module [{$module}] was disabled successfully.");
        } else {
            $this->comment("Module [{$module}] is already disabled.");
        }
    }
}
