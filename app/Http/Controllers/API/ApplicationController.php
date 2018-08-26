<?php

namespace App\Http\Controllers\API;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\ApplicationSubmitted;

class ApplicationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'name' => 'required|string',
            'age' => 'required|integer|min:18',
            'location' => 'required|string',
            'email' => 'required|email',
            'steam' => 'required|url',
            'available' => 'required|boolean',
            'owns_apex' => 'required|boolean',
            'other_groups' => 'required|boolean',
            'experience' => 'required|string',
            'about' => 'required|string',
            'source' => 'string',
            'source_data' => 'string',
        ]);

        return tap($application = Application::create($attributes))
            ->notify(new ApplicationSubmitted($application));
    }
}
