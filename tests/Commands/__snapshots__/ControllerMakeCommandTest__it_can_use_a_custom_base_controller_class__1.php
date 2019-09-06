<?php return '<?php

namespace Modules\\Blog\\Http\\Controllers;

use App\\Http\\Controllers\\BaseController;

class MyController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \\Illuminate\\Contracts\\View\\Factory|\\Illuminate\\View\\View
     */
     public function index()
     {
        return view(\'blog::index\');
     }
}
';
