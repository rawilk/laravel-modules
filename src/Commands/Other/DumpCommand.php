<?php

namespace Rawilk\LaravelModules\Commands\Other;

use Illuminate\Console\Command;

class DumpCommand extends Command
{
    /** @var string */
    protected $signature = 'module:dump
                            {module? : The module to dump-autoload for}';

    /** @var string */
    protected $description = 'Dump-autoload for the specified module or all modules.';

    public function handle(): void
    {
        $this->info('Generating optimized autoload modules.');

        if ($module = $this->argument('module')) {
            $this->dump($module);
        } else {
            /** @var \Rawilk\LaravelModules\Module $module */
            foreach ($this->laravel['modules']->all() as $module) {
                $this->dump($module->getStudlyName());
            }
        }
    }

    private function dump(string $name): void
    {
        /** @var \Rawilk\LaravelModules\Module $module */
        $module = $this->laravel['modules']->findOrFail($name);

        $this->line("<comment>Running for module</comment>: {$module}");

        chdir($module->getPath());

        passthru('composer dump -o -n -q');
    }
}
