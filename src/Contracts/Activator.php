<?php

namespace Rawilk\LaravelModules\Contracts;

use Rawilk\LaravelModules\Module;

interface Activator
{
    public function delete(Module $module): void;

    public function disable(Module $module): void;

    public function enable(Module $module): void;

    public function hasStatus(Module $module, bool $status): bool;

    public function reset(): void;

    public function setActive(Module $module, bool $active): void;

    public function setActiveByName(string $name, bool $active): void;
}
