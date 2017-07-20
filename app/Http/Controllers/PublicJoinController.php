<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\JoinRequests\JoinRequest;

class PublicJoinController extends Controller
{
    /**
     * Constructor method for PublicJoinController.
     *
     * @return type
     */
    public function __construct()
    {
        // TODO Middleware
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->create();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('join.public.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $form = $request->all();

        $this->validate($request, [
            'name' => 'required|max:255',
            'age' => 'required|integer|between:'.env('JR_MIN_AGE', 16).',100',
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
        JoinRequest::create($form);

        return view('join.public.confirmation', compact('form'));
    }
}
