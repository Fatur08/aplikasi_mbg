<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;
    
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
            
                switch ($guard) {
                    case 'owner':
                        return redirect('/owner/dashboardowner');
                    case 'admin':
                        return redirect('/admin/dashboardadmin');
                    case 'kepala_dapur':
                        return redirect('/kepala_dapur/dashboardkepaladapur');
                    case 'distributor':
                        return redirect('/distributor/dashboarddistributor');
                }
            }
        }
    
        return $next($request);
    }
}
