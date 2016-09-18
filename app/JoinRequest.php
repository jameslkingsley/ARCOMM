<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use Validator;
use App\JoinStatus;

class JoinRequest extends Model
{
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

    public function status()
    {
        return $this->hasOne('App\JoinStatus', 'id', 'status_id');
    }

    /**
     * Sets the status of a join request
     * @param [integer] $status [New status]
     */
    public function setStatus($status)
    {
        // $this->status = $status;
        // $this->update();
    }

    /**
     * Set the join request status to approved
     * Sends an approved email to the recipient
     * @return void
     */
    public function approve()
    {
        setStatus(StatusList['approved']);
    }

    /**
     * Set the join request status to declined
     * Sends a declined email to the recipient
     * @return void
     */
    public function decline()
    {
        setStatus(StatusList['declined']);
    }

    public static function items($s = '', $o = 'desc')
    {
        $order = Input::get('order', $o);

        if (empty($s)) {
            return JoinRequest::orderBy('created_at', $o)->get();
        } else {
            $status = Input::get('status', $s);
            $statusID = JoinStatus::where('permalink', strtolower($status))->first();
            return JoinRequest::where('status_id', $statusID->id)->orderBy('created_at', $order)->get();
        }
    }
}
