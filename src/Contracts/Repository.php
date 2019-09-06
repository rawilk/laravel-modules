<?php

namespace Rawilk\LaravelModules\Contracts;

use Illuminate\Filesystem\Filesystem;
use Rawilk\LaravelModules\Collection;
use Rawilk\LaravelModules\Module;

interface Repository
{
    public function all(): array;

    public function allDisabled(): array;

    public function allEnabled(): array;

    public function assetPath(string $name): string;

    public function boot(): void;

    public function config(string $key, $default = null);

    public function count(): int;

    public function delete(string $name): bool;

    public function find(string $name): ?Module;

    public function findByAlias(string $alias): ?Module;

    public function findOrFail(string $name): Module;

    public function findRequirements(string $name): array;

    public function getByStatus(bool $active): array;

    public function getCached(): array;

    public function getFiles(): Filesystem;

    public function getModulePath(string $name): string;

    public function getOrdered(string $direction = 'asc'): array;

    public function getPath(): string;

    public function getScanPaths(): array;

    public function isDisabled(string $name): bool;

    public function isEnabled(string $name): bool;

    public function register(): void;

    public function scan(): array;

    public function toCollection(): Collection;
}
