<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Symfony\Component\HttpFoundation\Response;

class CheckIfIsOwnerOrAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, $next): Response
    {
        if (!$request->user() || !$request->user()->currentAccessToken()) {
            throw new AuthenticationException;
        }

        if ($request->user()->id === $request->route('user')->id) {
            return $next($request);
        }

        return app(CheckAbilities::class)->handle($request, $next, 'admin');
    }
}
