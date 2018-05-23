<?php

namespace App\Models\Portal;

use App\Support\BelongsToUser;
use App\Models\Operations\Absence;
use App\Models\Operations\Operation;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use BelongsToUser;

    /**
     * Guarded attributes.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Gets the associated operation.
     *
     * @return \App\Models\Operations\Operation
     */
    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }

    /**
     * Determines if the user booked this day off.
     *
     * @return boolean
     */
    public function booked()
    {
        return Absence::whereUserId($this->user_id)
            ->whereOperationId($this->operation_id)
            ->exists();
    }
}
