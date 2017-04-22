<?php

namespace App\Http\Controllers\Missions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Missions\MissionNote;
use App\Models\Missions\Mission;
use App\Notifications\MissionNoteAdded;
use App\Models\Portal\User;
use Notification;

class NoteController extends Controller
{
    /**
     * The note instance.
     *
     * @var App\Models\Missions\MissionNote
     */
    protected $note;

    /**
     * The mission instance.
     *
     * @var App\Models\Missions\Mission
     */
    protected $mission;

    /**
     * Constructor method.
     *
     * @return any
     */
    public function __construct(Mission $mission, MissionNote $note)
    {
        $this->mission = $mission;
        $this->note = $note;

        return $this;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Mission $mission)
    {
        $this->readNotifications($request, $mission);

        if (!$request->ajax()) {
            $panel = 'notes';
            return view('missions.show', compact('mission', 'panel'));
        } else {
            return view('missions.show.notes', compact('mission'));
        }
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
    public function store(Request $request, Mission $mission)
    {
        $note = $this->note->create([
            'user_id' => auth()->user()->id,
            'mission_id' => $mission->id,
            'text' => $request->text
        ]);

        $users = User::all()->filter(function($user) use($mission) {
            return
                $user->id != auth()->user()->id &&
                (
                    $user->hasPermission('mission:notes') ||
                    $user->id == $mission->user->id
                );
        });

        Notification::send($users, new MissionNoteAdded($note));

        return view('missions.notes.item', compact('note'));
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
    public function update(Request $request, Mission $mission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mission $mission, MissionNote $note)
    {
        if (!$note->isMine()) return;

        $note->delete();
    }

    /**
     * Marks all note notifications for the mission as read.
     *
     * @return any
     */
    public function readNotifications(Request $request, Mission $mission)
    {
        foreach ($mission->noteNotifications() as $notification) {
            $notification->markAsRead();
        }
    }
}