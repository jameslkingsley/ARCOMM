<?php

namespace App\Http\Controllers;

use App\JoinRequest;
use Illuminate\Http\Request;
use App\Http\Requests;

class PageController extends Controller
{
	public function index()
    {
		return view('home.index');
	}
}
