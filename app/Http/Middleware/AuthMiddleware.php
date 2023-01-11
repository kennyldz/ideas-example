<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try{
            $token=request()->bearerToken()??false;
            if ($token==false){ //if there is no token
                return response()->json(['success'=>false,'error_message'=>'Unauthorized access'],401);
            }
            return $next($request);
        }catch (ExpiredException $e){ //if there is expired
            return response()->json(['success'=>false,'error_message'=>'Your access period has expired, please login again.'],401);
        }catch (\Exception $e){ //if there is error
            return response()->json(['success'=>false,'error_message'=>'Unauthorized access'],401);
        }
    }
}
