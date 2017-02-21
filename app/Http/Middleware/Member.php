<?php

namespace App\Http\Middleware;

use Closure;
use Steam;
use App\SteamAPI;
use App\User;

class Member
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
            abort(404, "You are not logged in");
            return;
        }

        if (!auth()->user()->isMember()) {
            abort(404, "You are not a member");
            return;
        }

        return $next($request);
    }
}
