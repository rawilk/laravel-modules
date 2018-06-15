<?php

namespace Rawilk\LaravelModules\Commands\Generators;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Stub;
use Rawilk\LaravelModules\Traits\ModuleCommands;

class JobMakeCommand extends GeneratorCommand
{
	use ModuleCommands;

	/**
	 * The name of 'name' argument.
	 *
	 * @var string
	 */
	protected $argumentName = 'name';

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'module:make-job
							{name : The name of the job}
							{module? : The name of the module to create the job for}
							{--s|sync : Indicates the job should be synchronous}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new job for the specified module';

	/**
	 * Get the template contents.
	 *
	 * @return string
	 */
	protected function getTemplateContents()
	{
		$module = $this->laravel['modules']->findOrFail($this->getModuleName());

		return (new Stub($this->getStubName(), [
			'NAMESPACE' => $this->getClassNamespace($module),
			'CLASS'     => $this->getClass(),
		]))->render();
	}

	/**
	 * Get the destination file path.
	 *
	 * @return string
	 */
	protected function getDestinationFilePath()
	{
		$path = $this->laravel['modules']->getModulePath($this->getModuleName());

		$jobPath = GenerateConfigReader::read('jobs');

		return $path . $jobPath->getPath() . '/' . $this->getFileName() . '.php';
	}

	/**
	 * Get default namespace.
	 *
	 * @return string
	 */
	public function getDefaultNamespace() : string
	{
		return $this->laravel['modules']->config('paths.generator.jobs.path', 'Jobs');
	}

	/**
	 * Get the name of the stub file to use.
	 *
	 * @return string
	 */
	protected function getStubName()
	{
		if ($this->option('sync')) {
			return '/job.stub';
		}

		return '/job-queued.stub';
	}
}
