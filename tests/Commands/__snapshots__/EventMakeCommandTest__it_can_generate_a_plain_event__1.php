<?php return '<?php

namespace Modules\\Blog\\Events;

use Illuminate\\Queue\\SerializesModels;

class PostWasCreated
{
    use SerializesModels;

    public function __construct()
    {

    }
}
';
