<?php return '<?php

namespace Modules\\Blog\\CustomNamespace;

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
