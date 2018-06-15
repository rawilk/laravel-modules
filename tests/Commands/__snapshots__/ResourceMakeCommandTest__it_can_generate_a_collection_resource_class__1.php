<?php return '<?php

namespace Modules\\Blog\\Transformers;

use Illuminate\\Http\\Resources\\Json\\ResourceCollection;

class PostTransformer extends ResourceCollection
{
    /**
     * Transforms the resource collection into an array.
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
