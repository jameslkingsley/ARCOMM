<?php

namespace App\Models\JoinRequests;

use App\Support\BelongsToUser;
use Illuminate\Database\Eloquent\Model;

class EmailSubmission extends Model
{
    use BelongsToUser;

    /**
     * Guarded attributes.
     *
     * @var array
     */
    protected $guarded = [];
}
