<?php

namespace Rawilk\LaravelModules\Tests;

use Rawilk\LaravelModules\Support\Config\GenerateConfigReader;
use Rawilk\LaravelModules\Support\Config\GeneratorPath;

class GenerateConfigReaderTest extends BaseTestCase
{
	/** @test */
	public function it_can_read_a_configuration_value_with_new_format()
	{
	    $seedConfig = GenerateConfigReader::read('seeder');

	    $this->assertInstanceOf(GeneratorPath::class, $seedConfig);
	    $this->assertEquals('database/seeds', $seedConfig->getPath());
	    $this->assertTrue($seedConfig->generate());
	}

	/** @test */
	public function it_can_read_a_configuration_value_with_new_format_set_to_false()
	{
	    $this->app['config']->set('modules.paths.generator.seeder', ['path' => 'database/seeds', 'generate' => false]);

	    $seedConfig = GenerateConfigReader::read('seeder');

	    $this->assertInstanceOf(GeneratorPath::class, $seedConfig);
	    $this->assertEquals('database/seeds', $seedConfig->getPath());
	    $this->assertFalse($seedConfig->generate());
	}

	/** @test */
	public function it_can_read_a_configuration_value_with_old_format()
	{
	    $this->app['config']->set('modules.paths.generator.seeder', 'database/seeds');

	    $seedConfig = GenerateConfigReader::read('seeder');

	    $this->assertInstanceOf(GeneratorPath::class, $seedConfig);
	    $this->assertEquals('database/seeds', $seedConfig->getPath());
	    $this->assertTrue($seedConfig->generate());
	}

	/** @test */
	public function it_can_read_a_configuration_value_with_old_format_set_to_false()
	{
		$this->app['config']->set('modules.paths.generator.seeder', false);

		$seedConfig = GenerateConfigReader::read('seeder');

		$this->assertInstanceOf(GeneratorPath::class, $seedConfig);
		$this->assertFalse($seedConfig->getPath());
		$this->assertFalse($seedConfig->generate());
	}
}
