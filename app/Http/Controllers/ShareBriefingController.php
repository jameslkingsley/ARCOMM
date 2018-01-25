<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Missions\Mission;

class ShareBriefingController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Mission $mission, string $faction)
    {
        return view('share.briefing-content', compact('mission', 'faction'));
    }
}
