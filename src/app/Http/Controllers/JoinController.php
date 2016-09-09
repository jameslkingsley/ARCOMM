<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\JoinRequest;

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
    public function index($status = 'Pending')
    {
        $joinRequests = $this->getRequests($status);
        return view('join.admin.index', compact('joinRequests'));
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
        return view('join.admin.show', compact('jr'));
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

    public function items()
    {
        $status = Input::get('statusKey');
        $joinRequests = $this->getRequests($status);
        return view('join.admin.items', compact('joinRequests'));
    }

    public function getRequests($status = '')
    {
        return (empty($status)) ? JoinRequest::all() : JoinRequest::where('status', '=', JoinRequest::StatusList[$status])->get();
    }
}
