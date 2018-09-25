<?php return '<?php

namespace Modules\\Blog\\OtherNamespace;

use Illuminate\\Auth\\Access\\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }
}
';
