<?php

namespace App\Observers;

use App\Models\Mission;
use Illuminate\Support\Facades\Storage;

class MissionObserver
{
    /**
     * Handle the mission "created" event.
     *
     * @param  \App\Models\Mission  $mission
     * @return void
     */
    public function created(Mission $mission)
    {
        //
    }

    /**
     * Handle the mission "updated" event.
     *
     * @param  \App\Models\Mission  $mission
     * @return void
     */
    public function updated(Mission $mission)
    {
        //
    }

    /**
     * Handle the mission "deleted" event.
     *
     * @param  \App\Models\Mission  $mission
     * @return void
     */
    public function deleted(Mission $mission)
    {
        Storage::deleteDirectory("missions/{$mission->ref}");
    }

    /**
     * Handle the mission "restored" event.
     *
     * @param  \App\Models\Mission  $mission
     * @return void
     */
    public function restored(Mission $mission)
    {
        //
    }

    /**
     * Handle the mission "force deleted" event.
     *
     * @param  \App\Models\Mission  $mission
     * @return void
     */
    public function forceDeleted(Mission $mission)
    {
        Storage::delete("missions/{$mission->ref}");
    }
}
