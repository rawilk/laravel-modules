<?php

namespace Rawilk\LaravelModules\Process;

use Rawilk\LaravelModules\Contracts\Repository;
use Rawilk\LaravelModules\Contracts\RunableInterface;

class Runner implements RunableInterface
{
    /** @var \Rawilk\LaravelModules\Contracts\Repository */
    protected $module;

    /**
     * @param \Rawilk\LaravelModules\Contracts\Repository $module
     */
    public function __construct(Repository $module)
    {
        $this->module = $module;
    }

    public function run(string $command): void
    {
        passthru($command);
    }
}
