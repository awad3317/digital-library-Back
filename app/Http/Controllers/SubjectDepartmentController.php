<?php

namespace App\Http\Controllers;

use App\Models\subject_department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SubjectDepartmentController extends Controller
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
            'subject_id'=>['required',Rule::exists('subjects', 'id')],
            'department_id'=>['required',Rule::exists('departments', 'id')],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $data = subject_department::firstOrCreate([
            'subject_id' => $request->subject_id,
            'department_id' => $request->department_id,
        ]);
        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(subject_department $subject_department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(subject_department $subject_department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, subject_department $subject_department)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(subject_department $subject_department)
    {
        //
    }
}
