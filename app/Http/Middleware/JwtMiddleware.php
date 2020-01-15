<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Response;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // return $next($request);
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return Response::json(['status' => 'Token is Invalid'], 403);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return Response::json(['status' => 'Token is Expired'], 403);
            }else{
                return Response::json(['status' => 'Authorization Token not found'], 403);
            }
        }
        return $next($request);
    }
}
