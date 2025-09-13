<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class EnsureTokenIsValid
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token || !PersonalAccessToken::findToken($token)) {
            return response()->json([
                'message' => 'Logged out: another device has logged in'
            ], 401);
        }

        return $next($request);
    }
}