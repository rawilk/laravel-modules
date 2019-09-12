<?php

namespace Rawilk\LaravelModules\Publishing;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;

class LangPublisher extends Publisher
{
    /** @var bool */
    protected $showMessage = false;

    public function getDestinationPath(): string
    {
        $name = $this->module->getLowerName();

        return base_path("resources/lang/{$name}");
    }

    public function getSourcePath(): string
    {
        return $this->getModule()->getExtraPath(
            GenerateConfigReader::read('lang')->getPath()
        );
    }
}
