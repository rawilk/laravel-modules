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
\t\t\t\$table->integer('password');\n
TEXT;

        $this->assertEquals($this->normalizeString($expected), $parser->render());
    }

    /** @test */
    public function it_generates_migration_methods_for_the_up_method()
    {
        $parser = new SchemaParser('username:string, password:integer');

        $expected = <<<TEXT
\t\t\t\$table->string('username');
\t\t\t\$table->integer('password');\n
TEXT;

        $this->assertEquals($this->normalizeString($expected), $parser->up());
    }

    /** @test */
    public function it_generates_migration_methods_for_the_down_method()
    {
        $parser = new SchemaParser('username:string, password:integer');

        $expected = <<<TEXT
\t\t\t\$table->dropColumn('username');
\t\t\t\$table->dropColumn('password');\n
TEXT;

        $this->assertEquals($this->normalizeString($expected), $parser->down());
    }

    private function normalizeString(string $str): string
    {
        return str_replace("\r", '', $str);
    }
}
