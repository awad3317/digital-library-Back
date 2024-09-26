<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $Payload=auth()->getPayload();
        $user=User::find($Payload['sub']);
        if(is_null($user)){
            return response()->json([
                'message'=>'الحساب غير موجود'
            ], 401);
        }
        if($Payload['token_valid']!=$user['token_valid']){
            return response()->json([
                'message'=>'يرجى اعادة تسجيل الدخول'
            ], 401);
        }
        return $next($request);
    }
}
