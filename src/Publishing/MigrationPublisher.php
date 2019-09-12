<?php

namespace Rawilk\LaravelModules\Publishing;

use Rawilk\LaravelModules\Migrations\Migrator;

class MigrationPublisher extends Publisher
{
    /** @var \Rawilk\LaravelModules\Migrations\Migrator */
    private $migrator;

    /**
     * @param \Rawilk\LaravelModules\Migrations\Migrator $migrator
     */
    public function __construct(Migrator $migrator)
    {
        $this->migrator = $migrator;

        parent::__construct($migrator->getModule());
    }

    public function getDestinationPath(): string
    {
        return $this->repository->config('paths.migration');
    }

    public function getSourcePath(): string
    {
        return $this->migrator->getPath();
    }
}
