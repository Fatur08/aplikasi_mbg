<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CekDapurOperasional
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
        $maker = Auth::guard('maker')->user();

        $allowedDapur = 6;
        $allowedOwner = 2;

        if (!($maker->nomor_dapur_maker == $allowedDapur && $maker->id_owner == $allowedOwner)) {
            abort(403, 'Akses ditolak');
        }

        return $next($request);
    }
}
