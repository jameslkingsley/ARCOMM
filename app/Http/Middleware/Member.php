<?php

namespace App\Http\Middleware;

use Closure;
use Steam;
use App\Models\Portal\SteamAPI;
use App\Models\Portal\User;

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
            return redirect('/');
        }

        if (!auth()->user()->isMember()) {
            return redirect('/');
        }

        return $next($request);
    }
}
