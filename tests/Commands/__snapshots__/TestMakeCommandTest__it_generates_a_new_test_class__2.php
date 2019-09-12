<?php return '<?php

namespace Modules\\Blog\\tests\\Feature;

use Tests\\TestCase;

class PostRepositoryTest extends TestCase
{
    /** @test */
    public function it_()
    {
        $response = $this->get(\'/\');

        $response->assertStatus(200);
    }
}
';
