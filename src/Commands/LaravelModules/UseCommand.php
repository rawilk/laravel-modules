<?php

namespace Rawilk\LaravelModules\Commands\LaravelModules;

use Illuminate\Console\Command;

class UseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:use
                            {module : The name of the module to use}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use the specified module';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $module = studly_case($this->argument('module'));

        if (! $this->laravel['modules']->has($module)) {
            return $this->error("Module [{$module}] does not exist!");
        }

        $this->laravel['modules']->setUsed($module);

        $this->info("Module [{$module}] is now being used");
    }
}
