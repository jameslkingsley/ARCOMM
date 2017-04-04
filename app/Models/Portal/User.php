<?php

namespace App\Models\Portal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Models\Permissions\PermissionUser;
use App\Models\Permissions\Permission;
use App\Models\Missions\Mission;
use App\Notifications\MissionCommentAdded;
use App\Models\Portal\SteamAPI;
use Steam;
use Auth;

class User extends Authenticatable implements HasMediaConversions
{
    use Notifiable;
    use HasMediaTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'steam_id',
        'username',
        'avatar',
        'email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Media library image conversions.
     *
     * @return void
     */
    public function registerMediaConversions() {}

    /**
     * Checks whether the user is a member of the Steam group.
     *
     * @return boolean
     */
    public function isMember()
    {
        if (auth()->guest()) {
            return false;
        }

        return in_array(
            Steam::convertId($this->steam_id, 'id64'),
            SteamAPI::members()
        );
    }

    /**
     * Checks whether the user is an admin.
     * Access level must be greater than 1.
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->isMember() && $this->access_level > 1;
    }


    /**
     * Gets the user's missions.
     * Ordered from latest to oldest.
     *
     * @return Collection App\Models\Missions\Mission
     */
    public function missions()
    {
        return Mission::where('user_id', $this->id)->orderBy('created_at', 'desc')->get();
    }

    /**
     * Checks if the user has the given permissions.
     * All permissions must pass true.
     *
     * @return boolean
     */
    public function hasPermissions($names, $only_one = false)
    {
        foreach ($names as $name) {
            $has_permission = $this->hasPermission($name);

            if ($has_permission && $only_one) {
                return true;
            } else if (!$has_permission) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if the user has the given permission.
     *
     * @return boolean
     */
    public function hasPermission($name)
    {
        $permission = Permission::where('name', $name)->first();

        if (!$permission) {
            $permission = Permission::create(['name' => $name]);
            return false;
        }

        return !is_null(
            PermissionUser::where('user_id', $this->id)
                ->where('permission_id', $permission->id)
                ->first()
        );
    }

    /**
     * Gets unread mission comment notifications for the user.
     *
     * @return Collection
     */
    public function missionNotifications()
    {
        $filtered = auth()->user()->unreadNotifications->filter(function($item) {
            return $item->type == MissionCommentAdded::class;
        });

        return $filtered;
    }
}
