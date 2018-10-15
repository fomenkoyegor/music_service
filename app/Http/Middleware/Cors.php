<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Contracts\Routing\ResponseFactory;
class Cors
{
//    /**
//     * Handle an incoming request.
//     *
//     * @param  \Illuminate\Http\Request $request
//     * @param  \Closure $next
//     * @return mixed
//     */
//    public function handle($request, Closure $next)
//    {
//        return $next($request)
//            ->header('Access-Control-Allow-Origin', '*')
//            ->header('Access-Control-Allow-Methods', 'GET, POST, DELETE, PUT, PATCH, OPTIONS')
//            ->header('Access-Control-Allow-Headers', '*')
//            ->header('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application');
//    }
    public function handle($request, Closure $next)
    {
        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, DELETE, PUT, PATCH, OPTIONS')
            ->header('Access-Control-Allow-Headers', '*')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application');
    }

}
