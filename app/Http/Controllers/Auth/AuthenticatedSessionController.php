<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Other;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): Response
    {
        $request->authenticate();

        $request->session()->regenerate();

        app(Other::class)->history("login", "AuthenticatedSessionController@" . __FUNCTION__, "Login", "login", "success");

        return response()->noContent();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        app(Other::class)->history("logout", "AuthenticatedSessionController@" . __FUNCTION__, "Logout", "logout", "success");
        
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();


        return response()->noContent();
    }
}
