<?php return '<?php

namespace Modules\\Blog\\SomeOtherNamespace;

use Illuminate\\Routing\\Controller;

class MyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \\Illuminate\\View\\View
     */
    public function index()
    {
        return view(\'blog::index\');
    }
}
';
