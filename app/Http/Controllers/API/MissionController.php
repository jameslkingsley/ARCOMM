<?php

namespace App\Http\Controllers\API;

use App\Models\Mission;
use App\Http\Controllers\Controller;
use App\Notifications\MissionUpdated;
use App\Notifications\MissionUploaded;
use App\Http\Requests\MissionUpdateRequest;
use App\Http\Requests\MissionUploadRequest;

class MissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Mission::orderBy('created_at', 'desc')
            ->with('user', 'map', 'verifiedBy');

        if (auth()->user()->hasRole('administrator') || auth()->user()->hasRole('mission-tester')) {
            return $query->get();
        }

        return $query->whereNotNull('verified_by')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\MissionUploadRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MissionUploadRequest $request)
    {
        $mission = $request->handle();

        $mission->user->notify(new MissionUploaded($mission));

        return $mission;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mission  $mission
     * @return \Illuminate\Http\Response
     */
    public function show(Mission $mission)
    {
        return $mission->load('user', 'map', 'afterActionReports', 'notes', 'verifiedBy');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\MissionUpdateRequest  $request
     * @param  \App\Models\Mission  $mission
     * @return \Illuminate\Http\Response
     */
    public function update(MissionUpdateRequest $request, Mission $mission)
    {
        $this->authorize('update', $mission);

        $response = $request->handle($mission);

        $mission->user->notify(new MissionUpdated($mission));

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mission  $mission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mission $mission)
    {
        $this->authorize('delete', $mission);

        $mission->delete();
    }
}
