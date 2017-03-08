<?php

namespace App\Http\Controllers\Missions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Missions\Mission;
use App\Helpers\ArmaConfigParser;
use Storage;

class MissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->exists('panel')) {
            return view('missions.' . $request->panel);
        }

        return view('missions.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('file')) {
            $details = Mission::getDetailsFromName($request->file->getClientOriginalName());

            $mission = new Mission();
            $mission->user_id = auth()->user()->id;
            $mission->file_name = $request->file->getClientOriginalName();
            $mission->display_name = $request->file->getClientOriginalName();
            $mission->summary = '';
            $mission->mode = $details->mode;
            $mission->map_id = $details->map->id;
            $mission->pbo_path = '';
            $mission->save();

            $path = $request->file->storeAs(
                'missions/' . auth()->user()->id,
                $mission->id . '.pbo'
            );

            $mission->pbo_path = $path;
            $mission->save();

            $unpacked = $mission->unpack();
            $ext = ArmaConfigParser::convert($unpacked . '/description.ext');
            $mission->deleteUnpacked();

            $mission->display_name = $ext->onloadname;
            $mission->summary = $ext->onloadmission;
            $mission->save();

            return $mission->id;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Mission $mission)
    {
        if (!$request->ajax()) {
            return view('missions.index', compact('mission'));
        } else {
            $mission->storeConfigs();
            return view('missions.show', compact('mission'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mission $mission)
    {
        if ($request->hasFile('file')) {
            $details = Mission::getDetailsFromName($request->file->getClientOriginalName());

            if ($mission) {
                $download = $mission->exportedName();

                if (file_exists(public_path('downloads/' . $download))) {
                    Storage::disk('downloads')->delete($download);
                }

                $mission->file_name = $request->file->getClientOriginalName();
                $mission->display_name = $request->file->getClientOriginalName();
                $mission->mode = $details->mode;
                $mission->map_id = $details->map->id;
                $mission->save();

                $updatedPath = $request->file->storeAs(
                    'missions/' . auth()->user()->id,
                    $mission->id . '_updated.pbo'
                );

                $publishedPath = 'missions/' . auth()->user()->id . '/' . $mission->id . '.pbo';
                Storage::delete($mission->pbo_path);
                Storage::move($updatedPath, $publishedPath);

                $mission->pbo_path = $publishedPath;
                $mission->save();

                $mission->storeConfigs();

                $mission->display_name = $mission->ext()->onloadname;
                $mission->summary = $mission->ext()->onloadmission;
                $mission->save();

                return view('missions.show', compact('mission'));
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return void
     */
    public function destroy(Mission $mission)
    {
        $mission->delete();

        return redirect('/hub/missions');
    }

    /**
     * Shows the given mission's briefing.
     *
     * @return \Illuminate\Http\Response
     */
    public function briefing(Request $request)
    {
        $mission = Mission::find($request->mission_id);
        $faction = $request->faction;
        return view('missions.briefing', compact('mission', 'faction'));
    }

    /**
     * Locks/unlocks the given briefing faction for the given mission.
     *
     * @return void
     */
    public function setBriefingLock(Request $request)
    {
        $mission = Mission::find($request->mission_id);

        if (!$mission->isMine() && !auth()->user()->isAdmin()) {
            abort(403, 'You are not authorised to edit this mission');
            return;
        }

        $mission->lockBriefing($request->faction, $request->locked);
    }
}
