<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Operations\Operation;
use \Carbon\Carbon;

class OperationController extends Controller
{
    /**
     * The operation instance.
     *
     * @var App\Models\Operations\Operation
     */
    protected $operation;

    /**
     * Constructor method.
     *
     * @return any
     */
    public function __construct(Operation $operation)
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->operation->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Get latest operation date
        $latest = $this->operation->orderBy('starts_at', 'desc')->first();

        if ($latest) {
            return response()->json(
                $this->operation->create(['starts_at' => $latest->starts_at->addWeek()])
            );
        } else {
            return response()->json(
                $this->operation->create([
                    'starts_at' => Carbon::now()
                        ->endOfWeek()
                        ->subDay()
                        ->hour(config('operation.hour'))
                        ->minute(config('operation.minute'))
                        ->second(0)
                ])
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->operation->destroy($id);
    }
}
