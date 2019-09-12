<?php

namespace Rawilk\LaravelModules\Exceptions;

use Exception;

class ModuleNotFound extends Exception
{
    public static function named(string $name): self
    {
        return new static("Module [{$name}] does not exist!");
    }
}
