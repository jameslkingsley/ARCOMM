<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

class Operation extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'starts_at'
    ];

    /**
     * Gets the number of seconds away from the operation start time.
     *
     * @return numeric
     */
    public function startsIn()
    {
        $now = Carbon::now();
        return $this->starts_at->diffForHumans();
    }

    /**
     * Gets the latest operation.
     * Will be the operation to next run.
     *
     * @return App\Models\Operations\Operation
     */
    public static function latest()
    {
        $target = Carbon::now()->addDay();
        return self::whereRaw('DATE_ADD(starts_at, INTERVAL 2 DAY) > "' . $target->toDateTimeString() . '"')->first();
    }

    /**
     * Gets next week's operation.
     *
     * @return App\Models\Operations\Operation
     */
    public static function nextWeek()
    {
        return self::where('starts_at', '>', Carbon::now()->toDateTimeString())->first();
    }

    /**
     * Gets last week's operation.
     *
     * @return App\Models\Operations\Operation
     */
    public static function lastWeek()
    {
        return self::where('starts_at', '<', Carbon::now()->toDateTimeString())->first();
    }

    /**
     * Gets all missions for the operation in ascending order.
     *
     * @return Collection App\Models\Missions\Mission
     */
    public function missions()
    {
        return $this->hasMany('App\Models\Operations\OperationMission')->orderBy('play_order');
    }
}
