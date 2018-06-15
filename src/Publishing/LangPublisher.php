<?php

namespace Rawilk\LaravelModules\Publishing;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;

class LangPublisher extends Publisher
{
    /**
     * Determine if the result message will shown in the console.
     *
     * @var bool
     */
    protected $showMessage = false;

    /**
     * Get the destination path.
     *
     * @return string
     */
    public function getDestinationPath()
    {
        $name = $this->module->getLowerName();

        return base_path("resources/lang/{$name}");
    }

    /**
     * Get the source path.
     *
     * @return string
     */
    public function getSourcePath()
    {
        return $this->getModule()->getExtraPath(
            GenerateConfigReader::read('lang')->getPath()
        );
    }
}
