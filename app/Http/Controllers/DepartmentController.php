<?php

namespace App\Http\Controllers;

use App\Models\department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = department::all();
        return response()->json($departments, 200);
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
            'name'=>['required','min:2']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $data = department::firstOrCreate([
            'name' => $request->name,
        ]);
        $department_id = $data->id;
        return response()->json($department_id, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $department = department::findOrfail($id);
        return response()->json($department, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $department = department::findOrfail($id);
        $validator = validator::make($request->all(),[
            'name'=>['required','min:2',Rule::unique('departments')->ignore($id)]
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $department->update([
            'name' => $request->name
        ]);
        return response()->json([
            'message'=>'department successfully updated',
            'data'=>$department,
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $department = department::findOrfail($id);
        $department->delete();
        $department->project()->delete();
        return response()->json([
            'message'=>'department successfully deleted',
            'data'=>$department,
        ], 200);
    }
}
