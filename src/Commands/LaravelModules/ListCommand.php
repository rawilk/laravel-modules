<?php

namespace Rawilk\LaravelModules\Commands\LaravelModules;

use Illuminate\Console\Command;

class ListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:list
                            {--only= : The types of modules to list (enabled, disabled, or ordered)}
                            {--d|direction=asc : The direction to order the modules (only applies to --only=ordered)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show a list of all modules';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->table(['Name', 'Status', 'Order', 'Path'], $this->getRows());
    }

    /**
     * Get the table rows.
     *
     * @return array
     */
    private function getRows()
    {
        $rows = [];

        foreach ($this->getModules() as $module) {
            $rows[] = [
                $module->getName(),
                $module->enabled() ? 'Enabled' : 'Disabled',
                $module->get('order'),
                str_replace('/', '\\', $module->getPath())
            ];
        }

        return $rows;
    }

    /**
     * Get all modules.
     *
     * @return array
     */
    private function getModules()
    {
        switch ($this->option('only')) {
            case 'enabled':
                return $this->laravel['modules']->getByStatus(1);
            case 'disabled':
                return $this->laravel['modules']->getByStatus(0);
            case 'ordered':
                return $this->laravel['modules']->getOrdered($this->option('direction'));
            default:
                return $this->laravel['modules']->all();
        }
    }
}
