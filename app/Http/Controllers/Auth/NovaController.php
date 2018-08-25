<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Laravel\Nova\Nova;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NovaController extends Controller
{
    /**
     * Login the user and redirect to Nova.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $user = User::whereRememberToken(cookie('user_token'))->first();

        auth()->login($user, true);

        return redirect(Nova::path());
    }

    /**
     * Handles the Nova logout route.
     *
     * @return void
     */
    public function logout()
    {
        return redirect(Nova::path());
    }
}
