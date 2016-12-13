<?php

namespace App\Http\Controllers;

use App\JoinRequest;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\SteamAPI;
use Steam;

class PageController extends Controller
{
	public function index()
    {
		return view('home.index');
	}

    public function roster()
    {
        $members = SteamAPI::members();
        $summaries = Steam::user($members)->getPlayerSummaries();
        return view('roster.index', compact('summaries'));
    }
}
