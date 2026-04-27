<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CekDapurOwnerOperasional
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $owner = Auth::guard('owner')->user();

        $allowedOwner = 2;

        if (!($owner->id_owner == $allowedOwner)) {
            abort(403, 'Akses ditolak');
        }

        return $next($request);
    }
}
