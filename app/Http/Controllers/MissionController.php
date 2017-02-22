<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Mission;
use App\MissionComment;

class MissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($panel = 'library')
    {
        return view('missions.index', compact('panel'));
    }

    public function showPanel()
    {
        $panel = Input::get('panel');
        return view('missions.' . $panel);
    }

    public function showMission()
    {
        $mission = Mission::find(Input::get('id'));
        return view('missions.show', compact('mission'));
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

    public function library()
    {
        return 'Test';
    }

    public function saveComment(Request $request)
    {
        $form = $request->all();

        if ($form['id'] == -1) {
            $comment = new MissionComment();
            $comment->mission_id = $form['mission_id'];
            $comment->user_id = auth()->user()->id;
            $comment->text = $form['text'];
            $comment->published = false;
            $comment->save();
            return $comment->id;
        } else {
            $comment = MissionComment::find($form['id']);
            $comment->text = $form['text'];
            $comment->save();
            return $comment->id;
        }
    }
}
