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

        // Check if there's an intended URL and if the user has access to it
        if ($request->session()->has('url.intended')) {
            $intendedUrl = $request->session()->get('url.intended');
            // Check if the intended URL is for a role-restricted area
            if ($this->userCanAccessIntendedUrl($user, $intendedUrl)) {
                return redirect()->intended($targetUrl);
            } else {
                // Clear the intended URL if user can't access it
                $request->session()->forget('url.intended');
            }
        }

        return redirect($targetUrl);
    }

    /**
     * Check if the user can access the intended URL based on their role
     */
    private function userCanAccessIntendedUrl($user, $intendedUrl)
    {
        // Parse the intended URL to get the path
        $path = parse_url($intendedUrl, PHP_URL_PATH);

        // Check role-restricted areas
        if (str_starts_with($path, '/admin')) {
            return strtolower($user->role ?? '') === 'admin';
        }

        if (str_starts_with($path, '/account')) {
            return strtolower($user->role ?? '') === 'customer';
        }

        // For other paths, allow access
        return true;
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
