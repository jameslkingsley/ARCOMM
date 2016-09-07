<?php

namespace App\Http\Controllers;

use Validator;
use App\JoinRequest;
use Illuminate\Http\Request;
use App\Http\Requests;

class JoinController extends Controller {
    public function __construct() {
        $this->middleware('admin', ['only' => [
            'show',
            'listRequests',
            'approve'
        ]]);
    }

    public function index() {
        return view('join.form');
    }

    public function show(JoinRequest $jr) {
        return view('join.show', compact('jr'));
    }

    public function listRequests() {
        $joinRequests = JoinRequest::all();
        return view('join.list.list', compact('joinRequests'));
    }

    public function submit(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:255',
            'age' => 'required|integer|between:16,100',
            'location' => 'required',
            'email' => 'required|email',
            'steam' => 'required|url',
            'available' => 'required',
            'apex' => 'required|integer',
            'groups' => 'required',
            'experience' => 'required',
            'bio' => 'required'
        ]);

        // Create the join request if there are no form errors
        JoinRequest::create($request->all());

        return back();
    }

    public function approve(JoinRequest $joinRequest) {
        $joinRequest->approve();
        return back();
    }
}
