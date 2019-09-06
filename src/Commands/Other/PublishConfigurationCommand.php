<?php

namespace Rawilk\LaravelModules\Commands\Other;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class PublishConfigurationCommand extends Command
{
    /** @var string */
    protected $signature = 'module:publish-config
                            {module? : The module to publish the config files for}
                            {--f|force : Force the publishing of config files}';

    /** @var string */
    protected $description = 'Publish the config files for a module.';

    public function handle(): void
    {
        if ($module = $this->argument('module')) {
            $this->publishConfiguration($module);

            return;
        }

        /** @var \Rawilk\LaravelModules\Module $module */
        foreach ($this->laravel['modules']->allEnabled() as $module) {
            $this->publishConfiguration($module->getName());
        }
    }

    private function getServiceProviderForModule(string $name): string
    {
        $namespace = $this->laravel['config']->get('modules.namespace');
        $studlyName = Str::studly($name);

        return "{$namespace}\\$studlyName\\Providers\\{$studlyName}ServiceProvider";
    }

    private function publishConfiguration(string $module): void
    {
        $this->call('vendor:publish', [
            '--provider' => $this->getServiceProviderForModule($module),
            '--force'    => $this->option('force'),
            '--tag'      => ['config']
        ]);
    }
}
