<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Operations\Absence;
use App\Models\Operations\Operation;

class AbsenceController extends Controller
{
    /**
     * Absence model.
     *
     * @var App\Models\Operations\Absence
     */
    protected $absence;

    /**
     * Constructor method.
     *
     * @return void
     */
    public function __construct(Absence $absence)
    {
        $this->absence = $absence;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $operations = Operation::future()->reject(function($operation) {
            return $operation->absences->isEmpty();
        });

        return view('absence.index', compact('operations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $operations = Operation::future();

        return view('absence.modal', compact('operations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $absence = $this->absence->create([
            'user_id' => auth()->user()->id,
            'operation_id' => $request->operation_id,
            'reason' => $request->reason
        ]);

        return view('absence.complete', compact('absence'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Operations\Absence  $absence
     * @return \Illuminate\Http\Response
     */
    public function show(Absence $absence)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Operations\Absence  $absence
     * @return \Illuminate\Http\Response
     */
    public function edit(Absence $absence)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Operations\Absence  $absence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Absence $absence)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Operations\Absence  $absence
     * @return \Illuminate\Http\Response
     */
    public function destroy(Absence $absence)
    {
        $absence->delete();
    }
}
