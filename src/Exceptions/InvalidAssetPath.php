<?php

namespace Rawilk\LaravelModules\Exceptions;

use Exception;

class InvalidAssetPath extends Exception
{
    public static function missingModuleName(string $asset): self
    {
        return new static("Module name was not specified in asset [{$asset}].");
    }
}
