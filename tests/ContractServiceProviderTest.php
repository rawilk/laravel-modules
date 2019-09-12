<?php

namespace Rawilk\LaravelModules\Tests;

use Rawilk\LaravelModules\Contracts\Repository;
use Rawilk\LaravelModules\Laravel\LaravelFileRepository;

class ContractServiceProviderTest extends BaseTestCase
{
    /** @test */
    public function it_binds_the_repository_interface_with_an_implementation()
    {
        $this->assertInstanceOf(LaravelFileRepository::class, app(Repository::class));
    }
}
