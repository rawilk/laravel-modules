<?php

namespace Rawilk\LaravelModules\Tests;

use Rawilk\LaravelModules\Support\Migrations\NameParser;

class NameParserTest extends BaseTestCase
{
    /** @test */
    public function it_gets_the_original_name()
    {
        $parser = new NameParser('create_users_table');

        $this->assertEquals('create_users_table', $parser->getOriginalName());
    }

    /** @test */
    public function it_gets_the_table_name()
    {
        $parser = new NameParser('create_users_table');

        $this->assertEquals('users', $parser->getTableName());
    }

    /** @test */
    public function it_gets_the_action_name()
    {
        $this->assertEquals('create', (new NameParser('create_users_table'))->getAction());
        $this->assertEquals('update', (new NameParser('update_users_table'))->getAction());
        $this->assertEquals('delete', (new NameParser('delete_users_table'))->getAction());
        $this->assertEquals('remove', (new NameParser('remove_users_table'))->getAction());
    }

    /** @test */
    public function it_gets_the_first_part_of_name_if_no_action_was_guessed()
    {
        $this->assertEquals('something', (new NameParser('something_random'))->getAction());
    }

    /** @test */
    public function it_gets_the_correct_matched_results()
    {
        $matches = (new NameParser('create_users_table'))->getMatches();

        $expected = [
            'create_users_table',
            'users'
        ];

        $this->assertEquals($expected, $matches);
    }

    /** @test */
    public function it_gets_the_exploded_parts_of_a_migration_name()
    {
        $parser = new NameParser('create_users_table');

        $expected = ['create', 'users', 'table'];

        $this->assertEquals($expected, $parser->getData());
    }

    /** @test */
    public function it_can_check_if_the_current_migration_type_matches_a_given_type()
    {
        $parser = new NameParser('create_users_table');

        $this->assertTrue($parser->is('create'));
    }

    /** @test */
    public function it_can_check_if_the_current_migration_is_about_adding()
    {
        $this->assertTrue((new NameParser('add_users_table'))->isAdd());
    }

    /** @test */
    public function it_can_check_if_the_current_migration_is_about_deleting()
    {
        $this->assertTrue((new NameParser('delete_users_table'))->isDelete());
    }

    /** @test */
    public function it_can_check_if_the_current_migration_is_about_creating()
    {
        $this->assertTrue((new NameParser('create_users_table'))->isCreate());
    }

    /** @test */
    public function it_can_check_if_the_current_migration_is_about_dropping()
    {
        $this->assertTrue((new NameParser('drop_users_table'))->isDrop());
    }

    /** @test */
    public function it_makes_a_regex_pattern()
    {
        $this->assertEquals('/create_(.*)_table/', (new NameParser('create_users_table'))->getPattern());
        $this->assertEquals('/add_(.*)_to_(.*)_table/', (new NameParser('add_column_to_users_table'))->getPattern());
        $this->assertEquals('/delete_(.*)_from_(.*)_table/', (new NameParser('delete_users_table'))->getPattern());
    }
}
