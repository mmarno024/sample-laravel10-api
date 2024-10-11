<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    protected function authenticate($request, array $guards)
    {
        \Log::info('Attempting to authenticate request', ['token' => $request->bearerToken()]);

        if ($this->auth->guard('sanctum')->check()) {
            \Log::info('Authentication successful');
            return $this->auth->shouldUse('sanctum');
        }

        \Log::error('Authentication failed');
        $this->unauthenticated($request, $guards);
    }

    public function handle($request, Closure $next, ...$guards)
    {
        // Jika metode permintaan adalah OPTIONS, kembalikan respons 200
        if ($request->isMethod('OPTIONS')) {
            return response()->json([], 200);
        }

        // Pengecekan otentikasi
        $this->authenticate($request, $guards);

        return $next($request);
    }
}
