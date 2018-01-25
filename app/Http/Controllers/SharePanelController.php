<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Missions\Mission;

class SharePanelController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Mission $mission, string $panel)
    {
        if (!$request->ajax()) {
            return view('share.mission', compact('mission', 'panel'));
        } else {
            return view('share.' . strtolower($panel), compact('mission'));
        }
    }
}
