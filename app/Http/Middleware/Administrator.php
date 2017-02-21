<?php

namespace App\Http\Middleware;

use Closure;

class Administrator
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
        if (auth()->guest()) {
            abort(404, 'Not logged in');
            return;
        }

        if (!auth()->user()->isAdmin()) {
            abort(404, 'Not an administrator');
            return;
        }

        return $next($request);
    }
}
