<?php

namespace Rawilk\LaravelModules\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array all()
 * @method static array allDisabled()
 * @method static array allEnabled()
 * @method static string assetPath(string $name)
 * @method static null boot()
 * @method static mixed config(string $key, $default = null)
 * @method static int count()
 * @method static bool delete(string $name)
 * @method static \Rawilk\LaravelModules\Module|null find(string $name)
 * @method static \Rawilk\LaravelModules\Module findByAlias(string $name)
 * @method static \Rawilk\LaravelModules\Module findOrFail(string $name)
 * @method static array findRequirements(string $name)
 * @method static array getByStatus(bool $active)
 * @method static array getCached()
 * @method static \Illuminate\Filesystem\Filesystem getFiles()
 * @method static string getModulePath(string $name)
 * @method static array getOrdered(string $direction = 'asc')
 * @method static string getPath()
 * @method static array getScanPaths()
 * @method static bool isDisabled()
 * @method static bool isEnabled()
 * @method static null register()
 * @method static array scan()
 * @method static \Rawilk\LaravelModules\Collection toCollection()
 * @mixin \Illuminate\Support\Traits\Macroable
 */
class Module extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'modules';
    }
}
