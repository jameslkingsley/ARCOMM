<?php

namespace App\Http\Controllers\Join;

use DB;
use Validator;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Mail\JoinRequestStatus;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Input;
use App\Models\JoinRequests\JoinStatus;
use App\Models\JoinRequests\JoinRequest;
use App\Models\JoinRequests\EmailTemplate;
use App\Models\JoinRequests\EmailSubmission;

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
     * Email templates model.
     *
     * @var App\Models\JoinRequests\EmailTemplate
     */
    protected $emails;

    /**
     * Email submission model.
     *
     * @var App\Models\JoinRequests\EmailSubmission
     */
    protected $emailSubmission;

    /**
     * Constructor method.
     *
     * @return any
     */
    public function __construct(JoinRequest $joinRequests, JoinStatus $joinStatuses, EmailTemplate $emails, EmailSubmission $emailSubmission)
    {
        $this->joinRequests = $joinRequests;
        $this->joinStatuses = $joinStatuses;
        $this->emails = $emails;
        $this->emailSubmission = $emailSubmission;

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
        $emails = $this->emails->all();
        $emailSubmissions = $this->emailSubmission->where('join_request_id', $jr->id)->orderBy('created_at', 'asc')->get();

        return view('join.admin.show', compact('jr', 'joinStatuses', 'emails', 'emailSubmissions'));
    }

    /**
     * Shows join request items with the given status and order.
     *
     * @return view
     */
    public function items(Request $request)
    {
        $joinRequests = $this->joinRequests->items(
            $request->get('status', 'pending'),
            $request->get('order', 'desc')
        );

        return view('join.admin.items', compact('joinRequests'));
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

    /**
     * Sends the email.
     *
     * @return mixed
     */
    public function email(Request $request)
    {
        $jr = $this->joinRequests->findOrFail($request->jr_id);

        // Send email
        Mail::to($jr->email)
            ->send(new JoinRequestStatus(
                $jr,
                $request->subject,
                nl2br($request->body)
            ));

        // Store email submission
        $this->emailSubmission->create([
            'user_id' => auth()->user()->id,
            'join_request_id' => $jr->id,
            'subject' => $request->subject,
            'content' => $request->body
        ]);
    }

    /**
     * Gets the email submissions view.
     *
     * @return view
     */
    public function emailSubmissions(Request $request)
    {
        $emailSubmissions = $this->emailSubmission
            ->where('join_request_id', $request->jr_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('join.admin.email-submissions', compact('emailSubmissions'));
    }
}
