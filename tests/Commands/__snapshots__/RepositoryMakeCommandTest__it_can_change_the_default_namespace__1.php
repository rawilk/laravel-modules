<?php return '<?php

namespace Modules\\Blog\\SomeOtherNamespace;

use BaseRepository;
use Illuminate\\Database\\Eloquent\\Model;

class MyRepository extends BaseRepository
{
    /**
     * Define the Eloquent model to query.
     *
     * @return string
     */
     public function model()
     {
        return Model::class;
     }
}
';
