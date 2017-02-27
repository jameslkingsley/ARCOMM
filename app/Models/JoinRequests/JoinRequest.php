<?php

namespace App\Models\JoinRequests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use App\Models\JoinRequests\JoinStatus;
use Validator;

class JoinRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'age',
        'location',
        'email',
        'steam',
        'available',
        'apex',
        'groups',
        'experience',
        'bio'
    ];

    /**
     * Gets the status of the join request.
     *
     * @return App\Models\JoinRequests\JoinStatus
     */
    public function status()
    {
        return $this->hasOne(JoinStatus::class, 'id', 'status_id');
    }

    /**
     * Gets all join requests with the given status ordered by the given order.
     *
     * @return Collection App\Models\JoinRequests\JoinRequest
     */
    public static function items($s = '', $o = 'desc')
    {
        $order = Input::get('order', $o);

        if (empty($s)) {
            return JoinRequest::orderBy('created_at', $o)->get();
        } else {
            $status_str = Input::get('status', $s);
            $status = JoinStatus::where('permalink', strtolower($status_str))->first();
            return JoinRequest::where('status_id', $status->id)->orderBy('created_at', $order)->get();
        }
    }
}
