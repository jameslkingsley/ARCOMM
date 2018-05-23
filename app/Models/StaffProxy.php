<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class StaffProxy extends Model
{
    use Notifiable;

    /**
     * Guarded attributes.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Discord notification channel.
     *
     * @return any
     */
    public function routeNotificationForDiscord()
    {
        return config('services.discord.staff_channel_id');
    }
}
