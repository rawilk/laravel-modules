<?php return '<?php

namespace Modules\\Blog\\Transformers;

use Illuminate\\Http\\Resources\\Json\\Resource;

class PostTransformer extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \\Illuminate\\Http\\Request $request
     * @return array
     */
     public function toArray($request)
     {
        return parent::toArray($request);
     }
}
';
