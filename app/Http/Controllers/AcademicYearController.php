<?php

namespace App\Http\Controllers;

use App\Models\academic_year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $years = academic_year::all();
        return response()->json($years, 200);
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
            'year'=>['required']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $data = academic_year::firstOrCreate([
            'year' => $request->year,
        ]);
        $year_id = $data->id;
        return response()->json($year_id, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(academic_year $academic_year)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(academic_year $academic_year)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $academic_year=academic_year::findOrfail($id);
        $validator = validator::make($request->all(),[
            'year'=>['required',Rule::unique('academic_years')->ignore($id)]
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $academic_year->update([
            'year' => $request->year,
        ]);
        return response()->json([
            'message'=>'academic_year successfully updated',
            'data'=>$academic_year,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $academic_year=academic_year::findOrfail($id);
        $academic_year->delete();
        return response()->json([
            'message'=>'academic_year successfully deleted',
            'data'=>$academic_year,
        ], 200);
    }
}
