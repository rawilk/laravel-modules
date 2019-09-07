<?php return '<?php

namespace Modules\\Blog\\Http\\Resources;

use Illuminate\\Http\\Resources\\Json\\Resource;

class PostsTransformer extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \\Illuminate\\Http\\Request $request
     * @return array
     */
    public function toArray(): array
    {
        return parent::toArray($request);
    }
}
';
