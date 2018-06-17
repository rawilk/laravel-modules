<?php

namespace Rawilk\LaravelModules\Tests;

use Rawilk\LaravelModules\Support\Migrations\SchemaParser;

class SchemaParserTest extends BaseTestCase
{
    /** @test */
    public function it_generates_migration_method_calls()
    {
	    $parser = new SchemaParser('username:string, password:integer');

        $expected = <<<TEXT
\t\t\t\$table->string('username');
\t\t\t\$table->integer('password');\r\n
TEXT;

        $this->assertEquals($expected, $parser->render());
    }

    /** @test */
    public function it_generates_migration_methods_for_the_up_method()
    {
        $parser = new SchemaParser('username:string, password:integer');

        $expected = <<<TEXT
\t\t\t\$table->string('username');
\t\t\t\$table->integer('password');\r\n
TEXT;

        $this->assertEquals($expected, $parser->up());
    }

    /** @test */
    public function it_generates_migration_methods_for_the_down_method()
    {
        $parser = new SchemaParser('username:string, password:integer');

        $expected = <<<TEXT
\t\t\t\$table->dropColumn('username');
\t\t\t\$table->dropColumn('password');\r\n
TEXT;

        $this->assertEquals($expected, $parser->down());
    }
}
