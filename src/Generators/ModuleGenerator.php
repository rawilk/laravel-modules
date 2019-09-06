<?php

namespace Rawilk\LaravelModules\Generators;

use Illuminate\Console\Command as Console;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Rawilk\LaravelModules\Contracts\Activator;
use Rawilk\LaravelModules\FileRepository;
use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;

class ModuleGenerator extends Generator
{
    /** @var \Rawilk\LaravelModules\Contracts\Activator */
    protected $activator;

    /** @var \Illuminate\Contracts\Config\Repository */
    protected $config;

    /** @var \Illuminate\Console\Command */
    protected $console;

    /** @var \Illuminate\Filesystem\Filesystem */
    protected $filesystem;

    /** @var bool */
    protected $force = false;

    /** @var bool */
    protected $isActive = false;

    /** @var \Rawilk\LaravelModules\Module */
    protected $module;

    /** @var string */
    protected $name;

    /** @var bool */
    protected $plain = false;

    /**
     * @param string $name
     * @param \Rawilk\LaravelModules\FileRepository|null $module
     * @param \Illuminate\Contracts\Config\Repository|null $config
     * @param \Illuminate\Filesystem\Filesystem|null $filesystem
     * @param \Illuminate\Console\Command|null $console
     * @param \Rawilk\LaravelModules\Contracts\Activator|null $activator
     */
    public function __construct(
        string $name,
        FileRepository $module = null,
        Config $config = null,
        Filesystem $filesystem = null,
        Console $console = null,
        Activator $activator = null
    )
    {
        $this->name = $name;
        $this->config = $config;
        $this->filesystem = $filesystem;
        $this->console = $console;
        $this->module = $module;
        $this->activator = $activator;
    }

    public function generate(): void
    {
        $name = $this->getName();

        if ($this->module->has($name)) {
            if ($this->force) {
                $this->module->delete($name);
            } else {
                $this->console->error("Module [{$name}] already exists!");

                return;
            }
        }

        $this->generateFolders();

        $this->generateModuleJsonFile();

        if ($this->plain) {
            $this->cleanModuleJsonFile();
        } else {
            $this->generateFiles();
            $this->generateResources();
        }

        $this->activator->setActiveByName($name, $this->isActive);

        $this->console->info("Module [{$name}] was created successfully!");
    }

    public function generateFiles(): void
    {
        foreach ($this->getFiles() as $stub => $file) {
            $path = $this->module->getModulePath($this->getName()) . $file;

            if (! $this->filesystem->isDirectory($dir = dirname($path))) {
                $this->filesystem->makeDirectory($dir, 0775, true);
            }

            $this->filesystem->put($path, $this->getStubContents($stub));

            $this->console->info("Created: {$path}");
        }
    }

    public function generateFolders(): void
    {
        foreach ($this->getFolders() as $key => $folder) {
            $folder = GenerateConfigReader::read($key);

            if ($folder->generate() === false) {
                continue;
            }

            $path = $this->module->getModulePath($this->getName()) . '/' . $folder->getPath();

            $this->filesystem->makeDirectory($path, 0755, true);
            if (config('modules.stubs.gitkeep')) {
                $this->generateGitKeep($path);
            }
        }
    }

    public function generateGitKeep(string $path): void
    {
        $this->filesystem->put("{$path}/.gitkeep", '');
    }

    public function generateResources(): void
    {
        $this->console->call('module:make-seed', [
            'name'     => $this->getName(),
            'module'   => $this->getName(),
            '--master' => true
        ]);

        $this->console->call('module:make-provider', [
            'name'     => $this->getName() . 'ServiceProvider',
            'module'   => $this->getName(),
            '--master' => true
        ]);

        $this->console->call('module:route-provider', [
            'module' => $this->getName()
        ]);
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function getConsole(): Console
    {
        return $this->console;
    }

    public function getFiles(): array
    {
        return $this->module->config('stubs.files');
    }

    public function getFilesystem(): Filesystem
    {
        return $this->filesystem;
    }

    public function getFolders(): array
    {
        return $this->module->config('paths.generator');
    }

    public function getModule(): FileRepository
    {
        return $this->module;
    }

    public function getName(): string
    {
        return Str::studly($this->name);
    }

    public function getReplacements(): array
    {
        return $this->module->config('stubs.replacements');
    }

    public function setActivator(Activator $activator): self
    {
        $this->activator = $activator;

        return $this;
    }

    public function setActive(bool $active): self
    {
        $this->isActive = $active;

        return $this;
    }

    public function setConfig(Config $config): self
    {
        $this->config = $config;

        return $this;
    }

    public function setConsole(Console $console): self
    {
        $this->console = $console;

        return $this;
    }

    public function setForce(bool $force): self
    {
        $this->force = $force;

        return $this;
    }

    public function setPlain(bool $plain): self
    {
        $this->plain = $plain;

        return $this;
    }

    public function setFilesystem(Filesystem $filesystem): self
    {
        $this->filesystem = $filesystem;

        return $this;
    }

    public function setModule(FileRepository $module): self
    {
        $this->module = $module;

        return $this;
    }

    protected function getAuthorEmailReplacement(): string
    {
        return $this->module->config('composer.author.email');
    }

    protected function getAuthorNameReplacement(): string
    {
        return $this->module->config('composer.author.name');
    }

    protected function getLowerNameReplacement(): string
    {
        return strtolower($this->getName());
    }

    protected function getModuleNamespaceReplacement(): string
    {
        return str_replace('\\', '\\\\', $this->module->config('namespace'));
    }

    protected function getReplacement(string $stub): array
    {
        $replacements = $this->getReplacements();

        if (! isset($replacements[$stub])) {
            return [];
        }

        $keys = $replacements[$stub];

        $replaces = [];

        foreach ($keys as $key) {
            if (method_exists($this, $method = 'get' . ucfirst(Str::studly(strtolower($key))) . 'Replacement')) {
                $replaces[$key] = $this->$method();
            } else {
                $replaces[$key] = null;
            }
        }

        return $replaces;
    }

    protected function getStubContents(string $stub): string
    {
        return (new Stub(
            "/{$stub}.stub",
            $this->getReplacement($stub)
        ))->render();
    }

    protected function getStudlyNameReplacement(): string
    {
        return $this->getName();
    }

    protected function getVendorReplacement(): string
    {
        return $this->module->config('composer.vendor');
    }

    /**
     * Remove the default service provider that was added in the module.json file.
     * This is needed when a --plain module is created.
     */
    private function cleanModuleJsonFile(): void
    {
        $path = $this->module->getModulePath($this->getName()) . 'module.json';

        $content = $this->filesystem->get($path);
        $namespace = $this->getModuleNamespaceReplacement();
        $studlyName = $this->getStudlyNameReplacement();

        $provider = '"' . $namespace . '\\\\' . $studlyName . '\\\\Providers\\\\' . $studlyName . 'ServiceProvider"';

        $content = str_replace($provider, '', $content);

        $this->filesystem->put($path, $content);
    }

    private function generateModuleJsonFile(): void
    {
        $path = $this->module->getModulePath($this->getName()) . 'module.json';

        if (! $this->filesystem->isDirectory($dir = dirname($path))) {
            $this->filesystem->makeDirectory($dir, 0775, true);
        }

        $this->filesystem->put($path, $this->getStubContents('json'));

        $this->console->info("Created: {$path}");
    }
}
