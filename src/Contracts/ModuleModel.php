<?php

namespace Rawilk\LaravelModules\Contracts;

use Illuminate\Database\Eloquent\Collection;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface ModuleModel
{
    public static function allDisabled(array $columns = ['*']): Collection;

    public static function allEnabled(array $columns = ['*']): Collection;

    public static function allModules(array $columns = ['*']): Collection;

    public static function disable(string $name): void;

    public static function enable(string $name): void;

    public static function findByAlias(string $alias, array $columns = ['*']): ?ModuleModel;

    public static function findModule(string $name, array $columns = ['*']): ?ModuleModel;

    public static function getByStatus(bool $status, array $columns = ['*']): Collection;

    public static function getCount(): int;

    public static function getOrdered(string $direction = 'asc', array $columns = ['*']): Collection;

    public static function moduleExists(string $name): bool;

    public function getName(): string;

    public function hasStatus(bool $status): bool;

    public function isDisabled(): bool;

    public function isEnabled(): bool;
}
