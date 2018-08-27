<?php

namespace App\Http\Controllers\API;

use App\Models\Absence;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AbsenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $request->user()->absences()
            ->where('date', '>=', now()->startOfDay())
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Absence::class);

        $attributes = $request->validate([
            'date' => 'required|date',
            'reason' => 'string'
        ]);

        $request->user()->absences()->save(
            new Absence($attributes)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Absence  $absence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Absence $absence)
    {
        $this->authorize('update', $absence);

        $attributes = $request->validate([
            'date' => 'date',
            'reason' => 'string'
        ]);

        $absence->update($attributes);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Absence  $absence
     * @return \Illuminate\Http\Response
     */
    public function destroy(Absence $absence)
    {
        $this->authorize('delete', $absence);

        $absence->delete();
    }
}
