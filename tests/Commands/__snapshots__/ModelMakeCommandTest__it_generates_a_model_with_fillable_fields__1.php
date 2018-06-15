<?php return '<?php

namespace Modules\\Blog\\Models;

use Illuminate\\Database\\Eloquent\\Model;

class Post extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = \'posts\';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [\'title\', \'slug\'];
}
';
