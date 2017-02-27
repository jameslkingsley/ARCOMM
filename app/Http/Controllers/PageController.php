<?php

namespace App\Http\Controllers;

use App\JoinRequest;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Portal\SteamAPI;
use Steam;

class PageController extends Controller
{
    /**
     * Displays the homepage.
     *
     * @return view
     */
	public function index()
    {
		return view('home.index');
	}

    /**
     * Displays the Steam group roster.
     *
     * @return view
     */
    public function roster()
    {
        $members = SteamAPI::members();
        $summaries = Steam::user($members)->getPlayerSummaries();
        return view('roster.index', compact('summaries'));
    }
}
