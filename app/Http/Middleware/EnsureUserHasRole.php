<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * Usage in routes: ->middleware(['auth','role:admin']) or ->middleware(['auth','role:admin,customer'])
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $roles  comma-separated roles (e.g. "admin,customer")
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $roles = null)
    {
        // Ensure user is authenticated first (use 'auth' middleware before this)
        $user = $request->user();

        if (! $user) {
            // If you want JSON responses for API, check wantsJson(); otherwise redirect to login
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->guest(route('login'));
        }

        // If no roles provided, allow all authenticated users
        if (! $roles) {
            return $next($request);
        }

        // roles come as a single string "admin,customer" — split and normalize
        $allowed = array_map(fn($r) => Str::lower(trim($r)), explode(',', $roles));

        $userRole = Str::lower(trim($user->role ?? ''));

        if (in_array($userRole, $allowed, true)) {
            return $next($request);
        }

        // Forbidden for authenticated users without the required role
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        abort(403, 'Unauthorized.');
    }
}
