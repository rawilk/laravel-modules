<?php

namespace Rawilk\LaravelModules\Publishing;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;

class AssetPublisher extends Publisher
{
    /** @var bool */
    protected $showMessage = false;

    public function getDestinationPath(): string
    {
        return $this->repository->assetPath($this->module->getLowerName());
    }

    public function getSourcePath(): string
    {
        return $this->getModule()->getExtraPath(
            GenerateConfigReader::read('assets')->getPath()
        );
    }
}
