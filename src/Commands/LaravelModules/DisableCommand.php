<?php

namespace Rawilk\LaravelModules\Commands\LaravelModules;

use Illuminate\Console\Command;

class DisableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:disable
                            {module : The module to disable}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable the specified module';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $module = $this->laravel['modules']->findOrFail($this->argument('module'));

        if ($module->enabled()) {
            $module->disable();

            $this->info("Module [{$module->getName()}] was disabled");
        } else {
            $this->comment("Module [{$module->getName()}] is already disabled");
        }
    }
}
