<?php

namespace App\Http\Controllers;

use App\Models\team_project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TeamProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = validator::make($request->all(),[
            'team_id'=>['required',Rule::exists('teams', 'id')],
            'project_number'=>['required',Rule::exists('projects', 'number')]
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $data = team_project::firstOrCreate([
            'team_id'=> $request->team_id,
            'project_number'=> $request->project_number
        ]);
        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(team_project $team_project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(team_project $team_project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, team_project $team_project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(team_project $team_project)
    {
        //
    }
}
