<?php

namespace App\Models\Operations;

use App\Support\BelongsToUser;
use App\Models\Operations\Operation;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    use BelongsToUser;

    /**
     * Guarded attributes.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Gets the operation model.
     *
     * @return App\Models\Operations\Operation
     */
    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }
}
