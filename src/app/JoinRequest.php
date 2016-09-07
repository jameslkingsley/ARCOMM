<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JoinRequest extends Model {
    const StatusList = [
        'Pending' => 0,
        'Approved' => 1,
        'Declined' => 2
    ];

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

    public static function getConstants() {
        $class = new \ReflectionClass(__CLASS__);
        return $class->getConstants();
    }

    /**
     * Sets the status of a join request
     * @param [integer] $status [New status]
     */
    public function setStatus($status) {
        $this->status = $status;
        $this->update();
    }

    /**
     * Set the join request status to approved
     * Sends an approved email to the recipient
     * @return void
     */
    public function approve() {
        setStatus(StatusList['Approved']);
    }

    /**
     * Set the join request status to declined
     * Sends a declined email to the recipient
     * @return void
     */
    public function decline() {
        setStatus(StatusList['Declined']);
    }
}
