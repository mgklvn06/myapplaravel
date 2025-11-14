<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Configurable role-based redirect. Uses config/login_redirects.php.
        // If an intended URL exists (user tried to access a protected page), that will be honored.
        $user = $request->user();

        // Determine target from config. Config keys should be lowercased role names.
        $roleKey = $user && isset($user->role) ? strtolower($user->role) : null;
        $rolesMap = config('login_redirects.roles', []);

        if ($roleKey && array_key_exists($roleKey, $rolesMap)) {
            $target = $rolesMap[$roleKey];
        } else {
            $target = config('login_redirects.default', RouteServiceProvider::HOME);
        }

        // Interpret 'route:' prefix as a named route, otherwise treat as path/url.
        if (is_string($target) && str_starts_with($target, 'route:')) {
            $routeName = substr($target, strlen('route:'));
            try {
                $targetUrl = route($routeName);
            } catch (\Throwable $e) {
                // If the named route does not exist, fall back to default
                $targetUrl = config('login_redirects.default', RouteServiceProvider::HOME);
            }
        } else {
            $targetUrl = $target;
        }

        return redirect()->intended($targetUrl);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
