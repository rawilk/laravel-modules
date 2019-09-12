<?php

namespace Rawilk\LaravelModules\Tests;

use Modules\Recipe\Providers\DeferredServiceProvider;
use Modules\Recipe\Providers\RecipeServiceProvider;
use Rawilk\LaravelModules\Contracts\Activator;
use Rawilk\LaravelModules\Json;
use Rawilk\LaravelModules\Laravel\Module;

class LaravelModuleTest extends BaseTestCase
{
    /** @var \Rawilk\LaravelModules\Tests\TestingModule */
    private $module;

    /** @var \Rawilk\LaravelModules\Contracts\Activator */
    private $activator;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->module = new TestingModule($this->app, 'Recipe Name', __DIR__ . '/stubs/valid/Recipe');
        $this->activator = $this->app[Activator::class];
    }

    /**
     * Clean up the testing environment before the next test.
     */
    protected function tearDown(): void
    {
        $this->activator->reset();

        parent::tearDown();
    }

    /**
     * This method is called before the first test of this test class is run.
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        symlink(__DIR__ . '/stubs/valid', __DIR__ . '/stubs/valid_symlink');
    }

    /**
     * This method is called after the last test of this test class is run.
     */
    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        unlink(__DIR__ . '/stubs/valid_symlink');
    }

    /** @test */
    public function it_gets_the_module_name()
    {
        $this->assertEquals('Recipe Name', $this->module->getName());
    }

    /** @test */
    public function it_gets_the_lowercase_module_name()
    {
        $this->assertEquals('recipe name', $this->module->getLowerName());
    }

    /** @test */
    public function it_gets_the_studly_module_name()
    {
        $this->assertEquals('RecipeName', $this->module->getStudlyName());
    }

    /** @test */
    public function it_gets_the_snake_case_module_name()
    {
        $this->assertEquals('recipe_name', $this->module->getSnakeName());
    }

    /** @test */
    public function it_gets_the_module_description()
    {
        $this->assertEquals('recipe module', $this->module->getDescription());
    }

    /** @test */
    public function it_gets_the_module_alias()
    {
        $this->assertEquals('recipe', $this->module->getAlias());
    }

    /** @test */
    public function it_gets_the_module_path()
    {
        $this->assertEquals(__DIR__ . '/stubs/valid/Recipe', $this->module->getPath());
    }

    /** @test */
    public function it_gets_the_module_path_with_a_symlink()
    {
        $this->module = new TestingModule($this->app, 'Recipe Name', __DIR__ . '/stubs/valid_symlink/Recipe');

        $this->assertEquals(__DIR__ . '/stubs/valid_symlink/Recipe', $this->module->getPath());
    }

    /** @test */
    public function it_gets_required_modules()
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
    public function it_reads_a_key_from_module_json_file_via_a_helper_method()
    {
        $this->assertEquals('Recipe', $this->module->get('name'));
        $this->assertEquals('0.1', $this->module->get('version'));
        $this->assertEquals('my default', $this->module->get('not_exists', 'my default'));
        $this->assertEquals(['required_module'], $this->module->get('requires'));
    }

    /** @test */
    public function it_reads_keys_from_composer_json_files_via_a_helper_method()
    {
        $this->assertEquals('rawilk/recipe', $this->module->getComposerAttr('name'));
    }

    /** @test */
    public function it_casts_a_module_to_a_string()
    {
        $this->assertEquals('RecipeName', (string) $this->module);
    }

    /** @test */
    public function it_checks_the_status_of_a_module()
    {
        $this->assertFalse($this->module->isStatus(true));
        $this->assertTrue($this->module->isStatus(false));
    }

    /** @test */
    public function it_checks_if_a_module_is_enabled()
    {
        $this->assertFalse($this->module->isEnabled());
        $this->assertTrue($this->module->isDisabled());
    }

    /** @test */
    public function it_fires_events_when_a_module_is_created()
    {
        $this->expectsEvents([
            sprintf('modules.%s.enabling', $this->module->getLowerName()),
            sprintf('modules.%s.enabled', $this->module->getLowerName())
        ]);

        $this->module->enable();
    }

    /** @test */
    public function it_fires_events_when_a_module_is_disabled()
    {
        $this->expectsEvents([
            sprintf('modules.%s.disabling', $this->module->getLowerName()),
            sprintf('modules.%s.disabled', $this->module->getLowerName())
        ]);

        $this->module->disable();
    }

    /** @test */
    public function it_has_a_good_providers_manifest_path()
    {
        $this->assertEquals(
            $this->app->bootstrapPath("cache/{$this->module->getSnakeName()}_module.php"),
            $this->module->getCachedServicesPath()
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
                DeferredServiceProvider::class
            ],
            'eager'    => [RecipeServiceProvider::class],
            'deferred' => ['deferred' => DeferredServiceProvider::class],
            'when'     => [DeferredServiceProvider::class => []]
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
            $this->assertEquals('Target class [foo] does not exist.', $e->getMessage());
        }

        app('deferred');

        $this->assertEquals('bar', app('foo'));
    }

    /** @test */
    public function it_reads_asset_mappings()
    {
        $assets = $this->module->getAssets();

        $this->assertCount(2, $assets);
        $this->assertArrayHasKey('js', $assets);
        $this->assertArrayHasKey('sass', $assets);
    }

    /** @test */
    public function it_gets_specific_asset_mappings()
    {
        $js = $this->module->getAssetAttr('js');
        $sass = $this->module->getAssetAttr('sass');

        $this->assertCount(1, $js);
        $this->assertCount(1, $sass);
    }
}

class TestingModule extends Module
{
}
