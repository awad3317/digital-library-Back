<?php

namespace App\Http\Controllers;

use App\Models\academicyear_subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AcademicyearSubjectController extends Controller
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
            'academic_year_id'=>['required',Rule::exists('academic_years', 'id')]
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $data = academicyear_subject::create([
            'subject_id' => $request->subject_id,
            'academic_year_id' => $request->academic_year_id,
        ]);
        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(academicyear_subject $academicyear_subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(academicyear_subject $academicyear_subject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, academicyear_subject $academicyear_subject)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(academicyear_subject $academicyear_subject)
    {
        //
    }
}
