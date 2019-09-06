<?php

namespace Rawilk\LaravelModules\Commands\Other;

use Illuminate\Console\Command;

class UpdateCommand extends Command
{
    /** @var string */
    protected $signature = 'module:update
                            {module? : The name of the module to update}';

    /** @var string */
    protected $description = 'Update dependencies for a specific module or all modules.';

    public function handle(): void
    {
        if ($name = $this->argument('module')) {
            $this->updateModule($name);

            return;
        }

        /** @var \Rawilk\LaravelModules\Module $module */
        foreach ($this->laravel['modules']->getOrdered() as $module) {
            $this->updateModule($module->getName());
        }
    }

    private function updateModule(string $name): void
    {
        $this->line("Updating module: <info>{$name}</info>");

        $this->laravel['modules']->update($name);

        $this->info("Module [{$name}] updated successfully.");
    }
}
