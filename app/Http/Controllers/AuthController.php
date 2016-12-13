<?php

namespace App\Http\Controllers;

use Invisnik\LaravelSteamAuth\SteamAuth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Auth;

class AuthController extends Controller
{
    private $steam;

    public function __construct(SteamAuth $steam)
    {
        $this->steam = $steam;
    }

    public function login()
    {
        if ($this->steam->validate()) {
            $info = $this->steam->getUserInfo();

            if (!is_null($info)) {
                $user = User::where('steam_id', $info->steamID64)->first();

                if (is_null($user)) {
                    $user = User::create([
                        'username' => $info->personaname,
                        'avatar' => $info->avatarfull,
                        'steam_id' => $info->steamID64
                    ]);
                }

                Auth::login($user, true);
                return redirect('/');
            }
        }
        
        return $this->steam->redirect();
    }
}
