<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->check() && auth()->user()->role === 'admin'){
            return $next($request);
        }
         abort(403, 'You do not have permission to access this page.');
        
    }

    // public function handle(Request $request, Closure $next)
    // {
    //     if (!Auth::check() || Auth::user()->role !== 'admin') {
    //         abort(401, 'Unauthorized'); // Return 401 Unauthorized for non-admin users
    //     }

    //     return $next($request);
    // }
}
