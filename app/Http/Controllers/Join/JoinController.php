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
     * Join request model.
     *
     * @var App\Models\JoinRequests\JoinRequest
     */
    protected $joinRequests;

    /**
     * Join status model.
     *
     * @var App\Models\JoinRequests\JoinStatus
     */
    protected $joinStatuses;

    /**
     * Constructor method.
     *
     * @return any
     */
    public function __construct(JoinRequest $joinRequests, JoinStatus $joinStatuses)
    {
        $this->joinRequests = $joinRequests;
        $this->joinStatuses = $joinStatuses;

        return $this;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(string $status = '')
    {
        if ($status == '') {
            return redirect('/hub/applications/pending');
        }

        $joinRequests = $this->joinRequests->items($status);
        $joinStatuses = $this->joinStatuses->orderBy('position', 'asc')->get();

        return view('join.admin.index', compact('joinRequests', 'joinStatuses'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, JoinRequest $jr)
    {
        $joinStatuses = $this->joinStatuses->orderBy('position', 'asc')->get();

        return view('join.admin.show', compact('jr', 'joinStatuses'));
    }

    /**
     * Shows join request items with the given status and order.
     *
     * @return view
     */
    public function items(string $status = '', string $order = 'desc')
    {
        $joinRequests = $this->joinRequests->items($status, $order);

        return view('join.admin.items', compact('joinRequests'));
    }

    /**
     * Shows the preset email view.
     *
     * @return any
     */
    public function emails(Request $request)
    {
        $emails = [];

        return view('join.admin.emails', compact('emails'));
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