<?php

if (! function_exists('config_path')) {
    function config_path(string $path = ''): string
    {
        return app()->basePath() . '/config' . ($path ? "/{$path}" : $path);
    }
}

if (! function_exists('module_path')) {
    function module_path(string $name): string
    {
        /** @var \Rawilk\LaravelModules\Module $module */
        $module = app('modules')->find($name);

        return $module->getPath();
    }
}

if (! function_exists('public_path')) {
    function public_path(string $path = ''): string
    {
        return app()->make('path.public') . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : $path);
    }
}
