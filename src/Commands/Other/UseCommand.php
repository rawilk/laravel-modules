<?php

namespace Rawilk\LaravelModules\Commands\Other;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class UseCommand extends Command
{
    /** @var string */
    protected $signature = 'module:use
                            {module : The name of the module to use in the cli session}';

    /** @var string */
    protected $description = 'Use the specified module in the cli session.';

    public function handle(): void
    {
        $module = Str::studly($this->argument('module'));

        if (! $this->laravel['modules']->has($module)) {
            $this->error("Module [{$module}] does not exist!");

            return;
        }

        $this->laravel['modules']->setUsed($module);

        $this->info("Module [{$module}] is now being used in the cli session!");
    }
}
