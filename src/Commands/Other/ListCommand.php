<?php

namespace Rawilk\LaravelModules\Commands\Other;

use Illuminate\Console\Command;

class ListCommand extends Command
{
    /** @var string */
    protected $signature = 'module:list
                            {--o|only= : The types of modules to list (enabled, disabled, or ordered)}
                            {--d|direction=asc : he direction to order the modules (only applies to --only=ordered)}';

    /** @var string */
    protected $description = 'Show a list of all modules.';

    public function handle(): void
    {
        $this->table(['Name', 'Status', 'Order', 'Path'], $this->getRows());
    }

    private function getModules(): array
    {
        switch ($this->option('only')) {
            case 'disabled':
                return $this->laravel['modules']->getByStatus(false);
            case 'enabled':
                return $this->laravel['modules']->getByStatus(true);
            case 'ordered':
                return $this->laravel['modules']->getOrdered($this->option('direction'));
            default:
                return $this->laravel['modules']->all();
        }
    }

    private function getRows(): array
    {
        $rows = [];

        /** @var \Rawilk\LaravelModules\Module $module */
        foreach ($this->getModules() as $module) {
            $rows[] = [
                $module->getName(),
                $module->isEnabled() ? 'Enabled' : 'Disabled',
                $module->get('order'),
                $module->getPath()
            ];
        }

        return $rows;
    }
}
