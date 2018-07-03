<?php

namespace App\Models\Portal;

use App\Support\BelongsToUser;
use App\Models\Missions\Mission;
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
     * Gets the associated mission.
     *
     * @return \App\Models\Missions\Mission
     */
    public function mission()
    {
        return $this->belongsTo(Mission::class);
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

    /**
     * Gets all users that are 4 weeks in the red.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function inactive()
    {
        return static::wherePresent(false)->with('user')->get()->reject(function ($attendance) {
            return $attendance->booked();
        })->groupBy('user.username')->map(function ($items) {
            $prev = $items->first();
            $consecutives = 1;

            foreach ($items as $item) {
                if ($item->operation->starts_at->diffInWeeks($prev->operation->starts_at) < 1) {
                    $consecutives++;
                    $prev = $item;
                }
            }

            return (object) [
                'missed' => $items->groupBy('operation_id')->count(),
                'consecutives' => $consecutives
            ];
        })->reject(function ($item) {
            return $item->missed < 2;
        })->sort()->reverse();
    }
}
