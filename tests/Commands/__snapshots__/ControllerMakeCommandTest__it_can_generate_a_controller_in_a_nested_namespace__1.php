<?php return '<?php

namespace Modules\\Blog\\Http\\Controllers\\My\\Nested;

use Illuminate\\Routing\\Controller;

class NestedController extends Controller
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
