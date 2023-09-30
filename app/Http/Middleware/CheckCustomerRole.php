<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Symfony\Component\HttpFoundation\Response;

class CheckCustomerRole
{
    use ResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      
        $role = auth()->user()->role;
        if($role)
        if($role!= Role::CUSTOMER) {
            return $this->returnError('403','UnAuthorization .');
        
    }

        return $next($request);
    }
}
