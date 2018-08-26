<?php

namespace App\Http\Controllers\API;

use App\Models\Mission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\MissionVerified;

class MissionVerificationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Mission $mission)
    {
        $this->authorize('verify', $mission);

        $mission->update([
            'verified_at' => now(),
            'verified_by' => $request->user()->id,
        ]);

        $mission->user->notify(new MissionVerified($mission));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mission  $mission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mission $mission)
    {
        $this->authorize('verify', $mission);

        $mission->update([
            'verified_by' => null,
            'verified_at' => null,
        ]);
    }
}
