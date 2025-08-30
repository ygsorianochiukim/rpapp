<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PositionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$positions): Response
    {
        $user = $request->user();

        $user->load('userAccess.position');
        $positionName = $user->userAccess?->position?->position;

        if (!in_array($positionName, $positions)) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        return $next($request);
    }
}
