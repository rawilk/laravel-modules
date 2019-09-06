<?php return '<?php

namespace Modules\\Blog\\Events\\Nested\\Namespace;

use Illuminate\\Queue\\SerializesModels;

class PostWasCreated
{
    use SerializesModels;

    public function __construct()
    {

    }

    public function broadcastOn(): array
    {
        return [];
    }
}
';
