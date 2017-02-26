<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Mission;
use App\MissionComment;
use App\ArmaLexer;
use App\OperationMission;
use Storage;

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
        $mission->storeConfigs();
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

    /**
     * Removes the given operation item.
     *
     * @return void
     */
    public function removeOperationItem(Request $request)
    {
        $form = $request->all();
        $id = $form['id'];

        if ($id == -1) {
            return;
        }

        OperationMission::destroy($id);
    }

    /**
     * Adds the given mission as an operation item to the given operation.
     *
     * @return void
     */
    public function addOperationItem(Request $request)
    {
        $form = $request->all();
        $mission_id = $form['mission_id'];
        $operation_id = $form['operation_id'];
        $play_order = $form['play_order'];

        $item = new OperationMission();
        $item->operation_id = $operation_id;
        $item->mission_id = $mission_id;
        $item->play_order = $play_order;
        $item->save();

        return $item->id;
    }

    public function saveComment(Request $request)
    {
        $form = $request->all();

        if (strlen(trim($form['text'])) == 0) {
            abort(403, 'No comment text provided');
            return;
        }

        if ($form['id'] == -1) {
            $comment = new MissionComment();
            $comment->mission_id = $form['mission_id'];
            $comment->user_id = auth()->user()->id;
            $comment->text = $form['text'];
            $comment->published = $form['published'];
            $comment->save();
        } else {
            $comment = MissionComment::find($form['id']);
            $comment->text = $form['text'];
            $comment->published = $form['published'];
            $comment->save();
        }

        if ($comment->published) {
            return view('missions.comment', compact('comment'));
        } else {
            return $comment->id;
        }
    }

    public function deleteComment(Request $request)
    {
        $form = $request->all();
        MissionComment::destroy($form['comment_id']);
    }

    public function showBriefing(Request $request)
    {
        $form = $request->all();
        $mission = Mission::find($form['mission_id']);
        $faction = $form['faction'];
        return view('missions.briefing', compact('mission', 'faction'));
    }

    public function lockBriefing(Request $request)
    {
        $form = $request->all();
        $mission = Mission::find($form['mission_id']);

        if (!$mission->isMine() && !auth()->user()->isAdmin()) {
            abort(403, 'You are not authorised to edit this mission');
            return;
        }

        $mission->{'locked_' . $form['faction'] . '_briefing'} = $form['locked'];
        $mission->save();
    }

    public function showComments(Request $request)
    {
        $form = $request->all();
        $comments = Mission::find($form['mission_id'])->comments;
        return view('missions.comments', compact('comments'));
    }

    public function upload(Request $request)
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
            $ext = ArmaLexer::convert($unpacked . '/description.ext');
            $mission->deleteUnpacked();

            $mission->display_name = $ext->onloadname;
            $mission->summary = $ext->onloadmission;
            $mission->save();

            return $mission->id;
        }
    }

    public function uploadMedia(Request $request)
    {
        $mission = Mission::find($request->mission_id);
        $mission->addMedia($request->file('file'))->toCollection('images');
        $media = $mission->getMedia('images')->last();
        return view('missions.media-item', compact('media'));
    }
}
