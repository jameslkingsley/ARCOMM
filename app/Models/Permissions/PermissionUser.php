<?php

namespace App\Models\Permissions;

use Illuminate\Database\Eloquent\Model;
use App\Models\Permissions\Permission;

class PermissionUser extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['permission_id', 'user_id'];

    /**
     * Gets the permission header.
     *
     * @return App\Models\Permissions\Permission
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
