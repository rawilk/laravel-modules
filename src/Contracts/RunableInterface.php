<?php

namespace Rawilk\LaravelModules\Contracts;

interface RunableInterface
{
    public function run(string $command): void;
}
