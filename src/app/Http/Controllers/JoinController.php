<?php

namespace App\Http\Controllers;

use Validator;
use App\JoinRequest;
use Illuminate\Http\Request;
use App\Http\Requests;

class JoinController extends Controller
{
    public function index()
    {
        return view('join');
    }

    public function submit(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255'
        ]);

        // Create the join request if there are no form errors
        JoinRequest::create($request->all());

        return back();
    }
}
