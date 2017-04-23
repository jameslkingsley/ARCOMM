<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Portal\User;
use App\Models\Missions\Mission;
use App\Models\Portal\Attendance;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Attendance::create([
            'dump' => json_encode($request->all())
        ]);

        return;

        $user = User::where('steam_id', $request->steam_id)->first();

        if (!$user) return;

        $mission_id = $request->mission;

        if (ends_with($mission_id, '.pbo')) {
            $mission_id = rtrim($mission_id, '.pbo');
        }

        $mission_id = last(explode('_', $mission_id));

        if (str_contains($mission_id, ['1','2','3','4','5','6','7','8','9','0'])) {
            $mission_id = (int)$mission_id;
        }

        $mission = Mission::find($mission_id);

        if (!$mission) return;

        Attendance::create([
            'user_id' => $user->id,
            'mission_id' => $mission->id,
            'inbound' => $request->direction == 'in'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
