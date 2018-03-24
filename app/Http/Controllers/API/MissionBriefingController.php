<?php

namespace App\Http\Controllers\API;

use App\Models\Mission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MissionBriefingController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mission $mission)
    {
        $factions = [];

        foreach ($request->factions as $faction) {
            if ($faction['locked']) {
                $factions[] = strtolower($faction['name']);
            }
        }

        $mission->update([
            'locked_briefings' => $factions
        ]);
    }
}
