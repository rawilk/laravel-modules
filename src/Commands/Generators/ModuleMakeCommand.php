<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Illuminate\Console\Command;
use Rawilk\LaravelModules\Generators\ModuleGenerator;

class ModuleMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make
                            {name* : The names of modules to create}
                            {--p|plain : Generate a plain module (without some resources)}
                            {--force : Force the module to be generated if the module already exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $names = $this->argument('name');

        foreach ($names as $name) {
            with(new ModuleGenerator($name))
                ->setFilesystem($this->laravel['files'])
                ->setModule($this->laravel['modules'])
                ->setConfig($this->laravel['config'])
                ->setConsole($this)
                ->setForce($this->option('force'))
                ->setPlain($this->option('plain'))
                ->generate();
        }
    }
}
