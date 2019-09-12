<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Illuminate\Console\Command;
use Rawilk\LaravelModules\Contracts\Activator;
use Rawilk\LaravelModules\Generators\ModuleGenerator;

class ModuleMakeCommand extends Command
{
    /** @var string */
    protected $signature = 'module:make
                            {name* : The name(s) of modules to create}
                            {--p|plain : Generate a plain module (without some resources)}
                            {--force : Force the module to be generated if the module already exists}
                            {--d|disabled : Do not enable the module at creation}';

    /** @var string */
    protected $description = 'Create a new module.';

    public function handle(): void
    {
        $names = $this->argument('name');

        foreach ($names as $name) {
            with(new ModuleGenerator($name))
                ->setFilesystem($this->laravel['files'])
                ->setModule($this->laravel['modules'])
                ->setConfig($this->laravel['config'])
                ->setActivator($this->laravel[Activator::class])
                ->setConsole($this)
                ->setForce($this->option('force'))
                ->setPlain($this->option('plain'))
                ->setActive(! $this->option('disabled'))
                ->generate();
        }
    }
}
