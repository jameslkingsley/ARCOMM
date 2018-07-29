<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use RestCord\DiscordClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Laravel\Socialite\Facades\Socialite;

class DiscordController extends Controller
{
    /**
     * Discord role IDs that are considered member.
     *
     * @var array
     */
    protected $memberRoles = [
        240187193505742848,
        324227354329219072,
        373959158095020035,
    ];

    /**
     * The Discord client instance.
     *
     * @var \RestCord\DiscordClient
     */
    protected $discord;

    /**
     * Constructor method.
     *
     * @return void
     */
    public function __construct(DiscordClient $discord)
    {
        $this->discord = $discord;
    }

    /**
     * Redirect the user to the Discord login page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirect()
    {
        return Socialite::with('discord')->redirect();
    }

    /**
     * Handles the response from Discord.
     *
     * @return \Illuminate\Http\Response
     */
    public function callback(Request $request)
    {
        $user = Socialite::driver('discord')->user();

        if ($this->isMember($user)) {
            return $this->create($user);
        }

        return [
            'error' => 'You are not a member.'
        ];
    }

    /**
     * Creates the user account and redirects with the access token.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($data)
    {
        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'discord_id' => $data->id,
            'avatar' => $data->avatar,
        ]);

        event(new Registered($user));

        $token = $user->makeApiToken();

        auth()->login($user, true);

        return redirect('/hub')
            ->with('access_token', $token);
    }

    /**
     * Determines if the given Discord user is a member.
     *
     * @return boolean
     */
    public function isMember($user)
    {
        $member = $this->discord->guild->getGuildMember([
            'user.id' => (int) $user->id,
            'guild.id' => (int) config('services.discord.server_id'),
        ]);

        return $this->hasMemberRole($member);
    }

    /**
     * Determines if the given Discord guild member has a member role.
     *
     * @return boolean
     */
    public function hasMemberRole($member)
    {
        foreach ($member->roles as $role) {
            if (in_array($role, $this->memberRoles)) {
                return true;
            }
        }

        return false;
    }
}
