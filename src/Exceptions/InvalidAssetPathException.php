<?php

namespace Rawilk\LaravelModules\Exceptions;

use Exception;

class InvalidAssetPathException extends Exception
{
    public static function missingModuleName($asset)
    {
        return new static("Module name was not specified in asset [$asset].");
    }
}
