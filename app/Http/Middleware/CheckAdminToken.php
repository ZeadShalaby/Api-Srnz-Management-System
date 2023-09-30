<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;


class CheckAdminToken
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       
   $user = null ;
try {
    //  $user = $this->auth->authenticate($request);  //check authenticted user
      $user = JWTAuth::parseToken()->authenticate();
  }
catch (JWTException $e) {

   if($e instanceof TokenInvalidException)
    return  $this -> returnError('E3001','INVALID_TOCKEN');
   elseif($e instanceof TokenExpiredException)
    return  $this -> returnError('E3001','Expired_TOCKEN');
   else
    return  $this -> returnError('E3001', 'TOCKEN_NOTFOUND');

}
catch (Throwable $e) {
    if($e instanceof TokenInvalidException)
    return  $this -> returnError('E3001','INVALID_TOCKEN');
   elseif($e instanceof TokenExpiredException)
    return  $this -> returnError('E3001','Expired_TOCKEN');
   else
    return  $this -> returnError('E3001', 'TOCKEN_NOTFOUND');

}

if(!$user)
return  $this -> returnError(trans('Unauthenticated'));


return $next($request);

    }  
}
