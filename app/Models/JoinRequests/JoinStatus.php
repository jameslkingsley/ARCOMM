<?php

namespace App\Models\JoinRequests;

use Illuminate\Database\Eloquent\Model;

class JoinStatus extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text',
        'permalink',
        'position'
    ];

    /**
     * Gets the count of apps in the status.
     *
     * @return integer
     */
    public function count()
    {
        return JoinRequest::where('status_id', $this->id)->count();
    }
}
