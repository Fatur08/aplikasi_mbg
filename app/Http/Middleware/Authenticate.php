<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('loginadmin');
            }

            if ($request->is('owner') || $request->is('owner/*')) {
                return route('loginowner');
            }

            if ($request->is('kepala_dapur') || $request->is('kepala_dapur/*')) {
                return route('loginkepaladapur');
            }

            if ($request->is('distributor') || $request->is('distributor/*')) {
                return route('logindistributor');
            }

            // fallback default
            return route('login');
        }
    }
}
