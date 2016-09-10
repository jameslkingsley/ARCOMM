<?php

namespace App\Http\Controllers;

use DB;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\JoinRequest;
use App\JoinStatus;

class JoinController extends Controller
{
    public function __construct()
    {
        // Middleware
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status = 'pending')
    {
        $joinRequests = JoinRequest::items($status);
        $joinStatuses = JoinStatus::orderBy('position', 'asc')->get();
        return view('join.admin.index', compact('joinRequests', 'joinStatuses'));
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
    public function show(JoinRequest $jr)
    {
        return view('join.admin.show', compact('jr'));
    }

    public function showByInput()
    {
        $id = Input::get('id');
        $jr = JoinRequest::find($id);
        $joinStatuses = JoinStatus::orderBy('position', 'asc')->get();
        return view('join.admin.show', compact('jr', 'joinStatuses'));
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

    public function viewItems($s = '', $o = 'desc')
    {
        $status = Input::get('status', $s);
        $order = Input::get('order', $o);
        $joinRequests = JoinRequest::items($status, $order);
        return view('join.admin.items', compact('joinRequests'));
    }

    public function createStatus()
    {
        $text = Input::get('text', '');

        if (empty($text) || strlen($text) > 12) return;

        $permalink = str_slug($text, '-');

        if (JoinStatus::where('permalink', '=', $permalink)->first() == null) {
            $status = new JoinStatus();
            $status->text = $text;
            $status->permalink = $permalink;
            $status->save();
            return $status->id;
        }
    }

    public function setStatus()
    {
        $joinRequestID = Input::get('join_request_id', 0);
        $newStatusID = Input::get('new_status_id', 0);

        if ($joinRequestID == 0 || $newStatusID == 0) return;

        $jr = JoinRequest::findOrFail($joinRequestID);
        $jr->status_id = $newStatusID;
        $jr->save();
    }

    public function getStatusView()
    {
        $id = Input::get('id');
        $jr = JoinRequest::find($id);
        $joinStatuses = JoinStatus::orderBy('position', 'asc')->get();
        return view('join.admin.status', compact('jr', 'joinStatuses'));
    }

    public function transferOldRecords()
    {
        $apps = DB::table('apps')->get();

        foreach ($apps as $item) {
            $jr = new JoinRequest();
            $jr->created_at = $item->appTimestamp;
            $jr->updated_at = $item->appTimestamp;
            $jr->name = $item->appName;
            $jr->age = (int)$item->appAge;
            $jr->location = $item->appCountry;
            $jr->email = $item->appEmail;
            $jr->steam = $item->appSteam;
            $jr->available = (str_contains(strtolower($item->appAvailable), 'yes')) ? true : false;
            $jr->apex = (str_contains(strtolower($item->appApex), 'yes')) ? true : false;
            $jr->groups = (str_contains(strtolower($item->appGroups), 'yes')) ? true : false;
            $jr->experience = $item->appExperience;
            $jr->bio = $item->appBio;
            $jr->status_id = JoinStatus::where('text', '=', $item->appStatus)->first()->id;
            $jr->save();
        }

        return $apps;
    }
}
