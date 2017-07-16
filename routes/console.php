<?php

use App\Models\Missions\Mission;
use App\Models\Operations\Operation;
use App\Models\Operations\OperationMission;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('check-missions', function () {
    $missions = Mission::whereRaw('last_played is null')->get();

    foreach ($missions as $mission) {
        $this->comment("Checking [{$mission->id}] {$mission->display_name}...");
        $item = OperationMission::where('mission_id', $mission->id)->first();

        if (!$item) {
            $this->comment("Not selected [{$mission->id}] {$mission->display_name}");
            continue;
        }

        $header = $item->operation;
        $now = Carbon::now();

        if ($header->starts_at->lt($now)) {
            $mission->last_played = $header->starts_at;
            $mission->save();
            $this->comment("Updated [{$mission->id}] {$mission->display_name} to {$mission->last_played->toDateTimeString()}");
            continue;
        } else {
            $this->comment("Not played yet [{$mission->id}] {$mission->display_name}");
        }
    }
})->describe('Handles the last played timestamp for all missions');
