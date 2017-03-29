<?php

namespace App\Http\Middleware;

use Closure;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (auth()->guest()) {
            abort(404, 'Not logged in');
            return;
        }

        if (!auth()->user()->hasPermission($role)) {
            abort(404, 'You are not authorized');
            return;
        }

        return $next($request);
    }
}
