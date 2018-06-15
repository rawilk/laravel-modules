<?php

namespace Rawilk\LaravelModules\Traits;

trait ModuleCommands
{
    /**
     * Get the module name.
     *
     * @return string
     */
    public function getModuleName()
    {
        $module = $this->argument('module') ?: app('modules')->getUsedNow();

        $module = app('modules')->findOrFail($module);

        return $module->getStudlyName();
    }
}
