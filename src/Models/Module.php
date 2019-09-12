<?php

namespace Rawilk\LaravelModules\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Rawilk\LaravelModules\Contracts\ModuleModel;

/**
 * Rawilk\LaravelModules\Models\Module
 *
 * @property int $id
 * @property string $name
 * @property string $path
 * @propperty string $description
 * @property array $keywords
 * @property boolean $is_active
 * @property int $order
 * @property array $providers
 * @property array $aliases
 * @property array $files
 * @property array $requires
 * @mixin \Eloquent
 */
class Module extends Model implements ModuleModel
{
    protected $fillable = [
        'name', 'path', 'alias', 'description', 'keywords', 'is_active',
        'order', 'providers', 'aliases', 'files', 'requires'
    ];

    protected $casts = [
        'keywords'  => 'array',
        'is_active' => 'boolean',
        'order'     => 'integer',
        'providers' => 'array',
        'aliases'   => 'array',
        'files'     => 'array',
        'requires'  => 'array'
    ];

    public static function allDisabled(array $columns = ['*']): Collection
    {
        return (new static)->newQuery()->where('is_active', false)->get($columns);
    }

    public static function allEnabled(array $columns = ['*']): Collection
    {
        return (new static)->newQuery()->where('is_active', true)->get($columns);
    }

    public static function allModules(array $columns = ['*']): Collection
    {
        return (new static)->newQuery()->get($columns);
    }

    public static function disable(string $name): void
    {
        (new static)
            ->newQuery()
            ->where('name', $name)
            ->update(['is_active' => false]);
    }

    public static function enable(string $name): void
    {
        (new static)
            ->newQuery()
            ->where('name', $name)
            ->update(['is_active' => true]);
    }

    public static function findByAlias(string $alias, array $columns = ['*']): ?ModuleModel
    {
        return (new static)->newQuery()
            ->where('alias', $alias)
            ->first($columns);
    }

    public static function findModule(string $name, array $columns = ['*']): ?ModuleModel
    {
        return (new static)->newQuery()
            ->where('name', $name)
            ->first($columns);
    }

    public static function getByStatus(bool $status, array $columns = ['*']): Collection
    {
        return (new static)->newQuery()
            ->where('is_active', $status)
            ->get($columns);
    }

    public static function getCount(): int
    {
        return (new static)->newQuery()->count();
    }

    public static function getOrdered(string $direction = 'asc', array $columns = ['*']): Collection
    {
        return (new static)->newQuery()
            ->where('is_active', true)
            ->orderBy('order', $direction)
            ->get($columns);
    }

    public static function moduleExists(string $name): bool
    {
        return (new static)
            ->newQuery()
            ->where('name', $name)
            ->count() > 0;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function hasStatus(bool $status): bool
    {
        return $this->is_active === $status;
    }

    public function isDisabled(): bool
    {
        return $this->hasStatus(false);
    }

    public function isEnabled(): bool
    {
        return $this->hasStatus(true);
    }
}
