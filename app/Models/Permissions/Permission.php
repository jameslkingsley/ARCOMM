<?php

namespace App\Models\Permissions;

use Illuminate\Database\Eloquent\Model;
use App\Models\Permissions\PermissionUser;

class Permission extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Gets the permission users.
     *
     * @return Collection App\Models\Permissions\PermissionUser
     */
    public function users()
    {
        return $this->hasMany(PermissionUser::class);
    }
}
