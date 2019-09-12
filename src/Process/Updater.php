<?php

namespace Rawilk\LaravelModules\Process;

use Rawilk\LaravelModules\Module;

class Updater extends Runner
{
    public function update(string $name): void
    {
        $module = $this->module->findOrFail($name);

        chdir(base_path());

        $this->installRequires($module)
            ->installDevRequires($module)
            ->copyScriptsToMainComposerJson($module);
    }

    private function copyScriptsToMainComposerJson(Module $module): self
    {
        $scripts = $module->getComposerAttr('scripts', []);

        $composer = json_decode(file_get_contents(base_path('composer.json')), true);

        foreach ($scripts as $key => $script) {
            if (array_key_exists($key, $composer['scripts'])) {
                $composer['scripts'][$key] = array_unique(array_merge($composer['scripts'][$key], $script));

                continue;
            }

            $composer['scripts'] = array_merge($composer['scripts'], [$key => $script]);
        }
        
        file_put_contents(base_path('composer.json'), json_encode($composer, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        return $this;
    }

    private function installDevRequires(Module $module): self
    {
        $devPackages = $module->getComposerAttr('require-dev', []);

        $concatenatedPackages = '';
        foreach ($devPackages as $name => $version) {
            $concatenatedPackages .= "\"{$name}:{$version}\" ";
        }

        if (! empty($concatenatedPackages)) {
            $this->run("composer require --dev {$concatenatedPackages}");
        }

        return $this;
    }

    private function installRequires(Module $module): self
    {
        $packages = $module->getComposerAttr('require', []);

        $concatenatedPackages = '';
        foreach ($packages as $name => $version) {
            $concatenatedPackages .= "\"{$name}:{$version}\" ";
        }

        if (! empty($concatenatedPackages)) {
            $this->run("composer require {$concatenatedPackages}");
        }

        return $this;
    }
}
