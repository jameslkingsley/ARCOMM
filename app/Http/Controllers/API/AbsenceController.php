<?php

namespace App\Http\Controllers\API;

use App\Models\Operations\Absence;
use App\Http\Controllers\Controller;
use App\Http\Resources\AbsenceResource;

class AbsenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AbsenceResource::collection(
            Absence::all()
        );
    }
}
