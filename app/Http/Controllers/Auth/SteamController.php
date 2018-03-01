<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Invisnik\LaravelSteamAuth\SteamAuth;

class SteamController extends Controller
{
    /**
     * The SteamAuth instance.
     *
     * @var SteamAuth
     */
    protected $steam;

    /**
     * AuthController constructor.
     *
     * @param SteamAuth $steam
     */
    public function __construct(SteamAuth $steam)
    {
        $this->steam = $steam;
    }

    /**
     * Handles the registering through Steam.
     *
     * @return \Illuminate\Http\Response
     */
    public function handle()
    {
        if ($this->steam->validate()) {
            $info = $this->steam->getUserInfo();

            if (!is_null($info)) {
                $user = User::firstOrCreate([
                    'steam_id' => $info->steamID64,
                    'avatar' => $info->avatarfull,
                    'name' => $info->personaname,
                ]);

                auth()->login($user, true);

                Cache::forget('members');

                return redirect('/hub');
            }
        }

        return $this->steam->redirect();
    }
}
