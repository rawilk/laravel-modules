<?php return '<?php

namespace Modules\\Blog\\Http\\Middleware;

use Closure;
use Illuminate\\Http\\Request;

class SomeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \\Illuminate\\Http\\Request $request
     * @param \\Closure $closure
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
';
