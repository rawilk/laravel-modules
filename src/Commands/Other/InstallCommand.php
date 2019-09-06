<?php

namespace Rawilk\LaravelModules\Commands\Other;

use Illuminate\Console\Command;
use Rawilk\LaravelModules\Json;
use Rawilk\LaravelModules\Process\Installer;

class InstallCommand extends Command
{
    /** @var string */
    protected $signature = 'module:install
                            {name? : The name of the module to install}
                            {version? : The version of the module to install}
                            {--timeout= : The process timeout}
                            {--path= : The installation path}
                            {--type= : The type of installation}
                            {--tree : Install the module as a git subtree}
                            {--no-update : Disables the automatic update of the dependencies}';

    /** @var string */
    protected $description = 'Install the specified module by a given package name (vendor/name).';

    public function handle(): void
    {
        if ($this->argument('name') === null) {
            $this->installFromFile();

            return;
        }

        $this->install(
            $this->argument('name'),
            $this->argument('version'),
            $this->option('type'),
            $this->option('tree')
        );
    }

    private function install(string $name, ?string $version = 'dev-master', ?string $type = 'composer', ?bool $tree = false): void
    {
        $installer = new Installer(
            $name,
            $version,
            $type ?: $this->option('type'),
            $tree ?: $this->option('tree')
        );

        $installer->setRepository($this->laravel['modules']);

        $installer->setConsole($this);

        if ($timeout = $this->option('timeout')) {
            $installer->setTimeout($timeout);
        }

        $installer->run();

        if (! $this->option('no-update')) {
            $this->call('module:update', [
                'module' => $installer->getModuleName()
            ]);
        }
    }

    private function installFromFile(): void
    {
        if (! file_exists($path = base_path('modules.json'))) {
            $this->error("File 'modules.json' does not exist in your project root.");

            return;
        }

        $modules = Json::make($path);

        $dependencies = $modules->get('require', []);

        foreach ($dependencies as $module) {
            $module = collect($module);

            $this->install(
                $module->get('name'),
                $module->get('version'),
                $module->get('type')
            );
        }
    }
}
