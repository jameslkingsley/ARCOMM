<?php

namespace App\Http\Controllers;

use Invisnik\LaravelSteamAuth\SteamAuth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Portal\User;
use Auth;
use Log;

class AuthController extends Controller
{
    /**
     * The SteamAuth instance.
     *
     * @var SteamAuth
     */
    private $steam;

    /**
     * Constructor method for AuthController.
     *
     * @return void
     */
    public function __construct(SteamAuth $steam)
    {
        $this->steam = $steam;
    }

    /**
     * Attempts to login the user.
     *
     * @return void
     */
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

                if ($user->isMember()) {
                    return redirect('/hub');
                }

                return redirect('/');
            }
        }

        return $this->steam->redirect();
    }
}
