<?php

namespace App\Observers;

use App\Models\Missions\Mission;
use Storage;

class MissionObserver
{
    /**
     * Listen to the mission deleting event.
     *
     * @param  App\Models\Missions\Mission  $mission
     * @return void
     */
    public function deleting(Mission $mission)
    {
        Storage::delete($mission->pbo_path);

        $download = $mission->exportedName();

        if (file_exists(public_path('downloads/' . $download))) {
            Storage::disk('downloads')->delete($download);
        }
    }
}
