<?php

namespace App\Http\Controllers;

use App\Models\subject;
use App\Models\lecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()  // https://example.com?like=subjects.name,[value] | https://example.com?department_id=[value] | https://example.com?academic_year_id=[value] | https://example.com?semester=[value]
    {
        $subjects = subject::join('subject_departments','subjects.id','=','subject_departments.subject_id')
        ->join('departments','subject_departments.department_id','=','departments.id')
        ->join('academicyear_subjects','academicyear_subjects.subject_id','=','subjects.id')
        ->join('academic_years','academicyear_subjects.academic_year_id','=','academic_years.id')
        ->select('subjects.id','subjects.name','subjects.semester','departments.name AS department_name','academic_years.year')
        ->with('Lecture')->orderBy('academic_years.year','desc')->filter()->inRandomOrder()->paginate(PAGINATION_COUNT);
        return response()->json($subjects, 200);
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
            'name'=>['required','min:2'],
            'semester'=>['required','integer','between:1,10'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $data = subject::where('name', '=', $request->name)->first();
        if (is_null($data)) {
            $data_id = subject::create([
                'name' => $request->name,
                'semester'=>$request->semester,
            ]);
            $data_id = $data_id->id;
            return response()->json($data_id, 200);
        } else {
            $data_id = $data->id;
            return response()->json($data_id, 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $subject = subject::findOrfail($id);
        $lectures = DB::table('lectures')
        ->where('subject_id', '=', $subject->id)
        ->paginate(PAGINATION_COUNT);

        $department = DB::table('subject_departments')
        ->join('departments', 'departments.id', '=', 'subject_departments.department_id')
        ->select('departments.name')
        ->where('subject_departments.subject_id', '=', $subject->id)
        ->get();

       $academic_years = DB::table('academicyear_subjects')
       ->join('academic_years', 'academic_years.id', '=', 'academicyear_subjects.academic_year_id')
       ->select('academic_years.year')
       ->where('academicyear_subjects.subject_id', '=', $subject->id)
       ->get();

        $subjects =[
            $subject, $lectures, $department,$academic_years
        ];

        return response()->json($subjects, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $subject = subject::findOrfail($id);
        return response()->json($subject, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $id = Crypt::decrypt($id);
        $subject = subject::findOrfail($id);
        $validator = validator::make($request->all(),[
            'name'=>['required','min:2'],
            'semester'=>['required','integer','between:1,10'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $subject->update([
            'name' => $request->name,
            'semester'=>$request->semester,
            ]);
        return response()->json([
            'message'=>'subject successfully updated',
            'data'=>$subject,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $subject = subject::findOrfail($id);
        $subject->delete();
        $subject->Lecture()->delete();
        return response()->json([
            'message'=>'subject successfully deleted',
            'data'=>$subject,
        ], 200);
    }
}
