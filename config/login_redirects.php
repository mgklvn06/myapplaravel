<?php

use App\Providers\RouteServiceProvider;

return [
    /**
     * Default fallback after login. You can change this to any path or to
     * RouteServiceProvider::HOME to keep the previous behavior.
     */
    'default' => RouteServiceProvider::HOME,

    /**
     * Role-based redirects. Keys are role values (lowercased). Values can be:
     * - 'route:route.name' to redirect using a named route
     * - an absolute or relative path (e.g. '/dashboard' or '/account')
     */
    'roles' => [
        'admin' => 'route:admin.dashboard',
        'customer' => '/dashboard',
    ],
];
