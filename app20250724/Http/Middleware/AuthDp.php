<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Log;

class AuthDp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $user = Auth::guard('dp')->user()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => '尚未登入',
                    'url' => '/login',
                ]);
            } else {
                return redirect('/login');
            }
        }

        return $next($request);
    }
}
