<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Log;

class AuthDc
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
        if (! $user = Auth::guard('dc')->user()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => '尚未登入',
                    'url' => '/login#dc',
                ]);
            } else {
                return redirect('/login#dc');
            }
        }

        return $next($request);
    }
}
