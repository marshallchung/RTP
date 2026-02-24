<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class AuthAdmin extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $url = request()->path();
        if (Auth::check() && !str_contains($url, 'admin/users/password')) {
            $user = Auth::user();
            if ($user->change_default || ($user->next_change < Date('Y-m-d H:i:s'))) {
                return redirect('/admin/users/password');
            }
        } elseif (!Auth::check()) {
            return response()->redirectTo('/admin/login');
        }
        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('admin.auth.login');
        }
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->change_default || ($user->next_change < Date('Y-m-d H:i:s'))) {
                return redirect('/admin/users/password');
            }
        }
    }
}
