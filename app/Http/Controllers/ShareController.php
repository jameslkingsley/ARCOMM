<?php

namespace App\Http\Controllers;

use App\Models\Missions\Mission;

class ShareController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Mission $mission)
    {
        return view('share.mission', compact('mission'));
    }
}
