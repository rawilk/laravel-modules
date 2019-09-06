<?php

namespace Rawilk\LaravelModules\Traits;

/**
 * @mixin \Illuminate\Console\Command
 */
trait ModuleCommands
{
    public function getModuleName(): string
    {
        $moduleName = $this->argument('module') ?: app('modules')->getUsedNow();

        /** @var \Rawilk\LaravelModules\Module $module */
        $module = app('modules')->findOrFail($moduleName);

        return $module->getStudlyName();
    }
}
