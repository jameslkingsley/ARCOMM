<?php

namespace App\Http\Controllers;

use DB;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\JoinRequests\JoinRequest;
use App\Models\JoinRequests\JoinStatus;

class JoinController extends Controller
{
    /**
     * Constructor method for JoinController.
     *
     * @return void
     */
    public function __construct()
    {
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
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(JoinRequest $jr)
    {
        return view('join.admin.show', compact('jr'));
    }

    /**
     * Shows the join requests by the given ID.
     *
     * @return view
     */
    public function showByInput()
    {
        $id = Input::get('id');
        $jr = JoinRequest::find($id);
        $joinStatuses = JoinStatus::orderBy('position', 'asc')->get();
        return view('join.admin.show', compact('jr', 'joinStatuses'));
    }

    /**
     * Shows join request items with the given status and order.
     *
     * @return view
     */
    public function viewItems($s = '', $o = 'desc')
    {
        $status = Input::get('status', $s);
        $order = Input::get('order', $o);
        $joinRequests = JoinRequest::items($status, $order);
        return view('join.admin.items', compact('joinRequests'));
    }

    /**
     * Creates the given status.
     *
     * @return integer
     */
    public function createStatus()
    {
        $text = Input::get('text', '');

        if (empty($text) || strlen($text) > 12) return;

        $permalink = str_slug($text, '-');

        if (JoinStatus::where('permalink', $permalink)->first() == null) {
            $status = new JoinStatus();

            $status->text = $text;
            $status->permalink = $permalink;
            $status->save();

            return $status->id;
        }
    }

    /**
     * Sets the status of the given join request.
     *
     * @return void
     */
    public function setStatus()
    {
        $joinRequestID = Input::get('join_request_id', 0);
        $newStatusID = Input::get('new_status_id', 0);

        if ($joinRequestID == 0 || $newStatusID == 0) return;

        $jr = JoinRequest::findOrFail($joinRequestID);
        $jr->status_id = $newStatusID;
        $jr->save();
    }

    /**
     * Gets the status list view.
     *
     * @return view
     */
    public function getStatusView()
    {
        $id = Input::get('id');
        $jr = JoinRequest::find($id);
        $joinStatuses = JoinStatus::orderBy('position', 'asc')->get();
        return view('join.admin.status', compact('jr', 'joinStatuses'));
    }

    /**
     * Transfers old database records into the new database.
     *
     * @return void
     */
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
    }
}
