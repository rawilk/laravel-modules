<?php

namespace Rawilk\LaravelModules\Tests;

use Modules\Recipe\Providers\DeferredServiceProvider;
use Modules\Recipe\Providers\RecipeServiceProvider;
use Rawilk\LaravelModules\Json;
use Rawilk\LaravelModules\Laravel\Module;

class LaravelModuleTest extends BaseTestCase
{
	/**
	 * @var \Rawilk\LaravelModules\Tests\TestingModule
	 */
	private $module;

	/**
	 * Setup the test environment.
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();

		$this->module = new TestingModule($this->app, 'Recipe Name', __DIR__ . '/stubs/valid/Recipe');
	}

	/** @test */
	public function it_gets_module_name()
	{
	    $this->assertEquals('Recipe Name', $this->module->getName());
	}

	/** @test */
	public function it_gets_lowercase_module_name()
	{
	    $this->assertEquals('recipe name', $this->module->getLowerName());
	}

	/** @test */
	public function it_gets_studly_name()
	{
	    $this->assertEquals('RecipeName', $this->module->getStudlyName());
	}

	/** @test */
	public function it_gets_snake_case_name()
	{
	    $this->assertEquals('recipe_name', $this->module->getSnakeName());
	}

	/** @test */
	public function it_gets_module_alias()
	{
	    $this->assertEquals('recipe', $this->module->getAlias());
	}

	/** @test */
	public function it_gets_module_description()
	{
	    $this->assertEquals('recipe module', $this->module->getDescription());
	}

	/** @test */
	public function it_get_module_path()
	{
	    $this->assertEquals(['required_module'], $this->module->getRequires());
	}

	/** @test */
	public function it_loads_module_translations()
	{
		(new TestingModule($this->app, 'Recipe', __DIR__ . '/stubs/valid/Recipe'))->boot();

		$this->assertEquals('Recipe', trans('recipe::recipes.title.recipes'));
	}

	/** @test */
	public function it_reads_module_json_files()
	{
	    $jsonModule = $this->module->json();
	    $composerJson = $this->module->json('composer.json');

	    $this->assertInstanceOf(Json::class, $jsonModule);
	    $this->assertEquals('0.1', $jsonModule->get('version'));
	    $this->assertInstanceOf(Json::class, $composerJson);
	    $this->assertEquals('rawilk/recipe', $composerJson->get('name'));
	}

	/** @test */
	public function it_reads_keys_from_module_json_file_via_helper_methods()
	{
	    $this->assertEquals('Recipe', $this->module->get('name'));
	    $this->assertEquals('0.1', $this->module->get('version'));
	    $this->assertEquals('my default', $this->module->get('something-not-there', 'my default'));
	    $this->assertEquals(['required_module'], $this->module->get('requires'));
	}

	/** @test */
	public function it_reads_keys_from_composer_json_file_via_helper_method()
	{
	    $this->assertEquals('rawilk/recipe', $this->module->getComposerAttr('name'));
	}

	/** @test */
	public function it_casts_modules_to_string()
	{
	    $this->assertEquals('RecipeName', (string) $this->module);
	}

	/** @test */
	public function it_checks_module_statuses()
	{
	    $this->assertTrue($this->module->isStatus(1));
	    $this->assertFalse($this->module->isStatus(0));
	}

	/** @test */
	public function it_checks_if_a_module_is_enabled()
	{
	    $this->assertTrue($this->module->enabled());
	    $this->assertFalse($this->module->disabled());
	}

	/** @test */
	public function it_fires_disabled_events_when_a_module_gets_disabled()
	{
	    $this->expectsEvents([
	    	sprintf('modules.%s.disabling', $this->module->getLowerName()),
		    sprintf('modules.%s.disabled', $this->module->getLowerName())
	    ]);

	    $this->module->disable();
	    $this->module->enable();
	}

	/** @test */
	public function it_fires_enabled_events_when_a_module_gets_enabled()
	{
		$this->expectsEvents([
			sprintf('modules.%s.enabling', $this->module->getLowerName()),
			sprintf('modules.%s.enabled', $this->module->getLowerName())
		]);

		$this->module->disable();
		$this->module->enable();
	}

	/** @test */
	public function it_has_a_good_providers_manifest_path()
	{
	    $this->assertEquals(
	        str_replace('/', '\\', $this->app->bootstrapPath("cache/{$this->module->getSnakeName()}_module.php")),
	        str_replace('/', '\\', $this->module->getCachedServicesPath())
	    );
	}

	/** @test */
	public function it_makes_a_manifest_file_when_providers_are_loaded()
	{
	    $cachedServicesPath = $this->module->getCachedServicesPath();

	    @unlink($cachedServicesPath);
	    $this->assertFileNotExists($cachedServicesPath);

	    $this->module->registerProviders();

	    $this->assertFileExists($cachedServicesPath);
	    $manifest = require $cachedServicesPath;

	    $this->assertEquals([
		    'providers' => [
			    RecipeServiceProvider::class,
			    DeferredServiceProvider::class,
		    ],
		    'eager'     => [RecipeServiceProvider::class],
		    'deferred'  => ['deferred' => DeferredServiceProvider::class],
		    'when'      => [DeferredServiceProvider::class => []]
	    ], $manifest);
	}

	/** @test */
	public function it_can_load_a_deferred_provider()
	{
	    @unlink($this->module->getCachedServicesPath());

	    $this->module->registerProviders();

	    try {
	        app('foo');
	        $this->assertTrue(false, "app('foo') should throw an exception.");
	    } catch (\Exception $e) {
	    	$this->assertEquals('Class foo does not exist', $e->getMessage());
	    }

	    app('deferred');

	    $this->assertEquals('bar', app('foo'));
	}
}

class TestingModule extends Module
{
	public function registerProviders()
	{
		parent::registerProviders();
	}
}