<?php

namespace Rawilk\LaravelModules\Tests\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Rawilk\LaravelModules\Contracts\ModuleModel;

class CustomModule extends Model implements ModuleModel
{
    protected $table = 'modules';

    public static function allDisabled(array $columns = ['*']): Collection
    {

    }

    public static function allEnabled(array $columns = ['*']): Collection
    {

    }

    public static function allModules(array $columns = ['*']): Collection
    {

    }

    public static function findByAlias(string $alias, array $columns = ['*']): ?ModuleModel
    {

    }

    public static function findModule(string $name, array $columns = ['*']): ?ModuleModel
    {

    }

    public static function getByStatus(bool $status, array $columns = ['*']): Collection
    {

    }

    public static function getCount(): int
    {

    }

    public static function getOrdered(string $direction = 'asc', array $columns = ['*']): Collection
    {

    }
}
