<?php

namespace Rawilk\LaravelModules\Exceptions;

use Exception;

class InvalidJson extends Exception
{
    public static function invalidString(string $path): self
    {
        return new static("Error processing file: {$path}. Error: " . json_last_error_msg());
    }
}
