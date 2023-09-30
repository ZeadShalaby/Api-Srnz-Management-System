<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Checkvalidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->checkvalidation != env("API_VALIDATION",'uBLKewvnVormIpCi1XjynZ0')){
            return response()->json(['message'=> 'Unauthenticated .']);
        }
        return $next($request);
    }
}
