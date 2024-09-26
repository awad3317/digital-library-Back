<?php

namespace App\Http\Controllers;

use App\Models\lecture;
use App\Models\lecture_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\validator;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;


class LectureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() // https://example.com?like=lectures.name,[value] | https://example.com?lectures.subject_id=[value] | https://example.com?user_id=[value] | https://example.com?department_id=[value]
    {
        $lectures=lecture::join('subjects','lectures.subject_id','=','subjects.id')->join('users','lectures.user_id','=','users.id')
        ->join('subject_departments','subjects.id','=','subject_departments.subject_id')
        ->join('departments','subject_departments.department_id','=','departments.id')
        ->select('lectures.id','lectures.name','lectures.number','lectures.description','lectures.file_path','subjects.name AS subject_name','users.name AS teacher_name','departments.name AS department_name')
        ->filter()->inRandomOrder()->paginate(PAGINATION_COUNT);
        return response()->json($lectures, 200);
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
        $validator=Validator::make($request->all(),[
            'name' => ['required','string','min:2',],
            'number' => 'required',
            'user_id'=>['required', Rule::exists('users', 'id')],
            'subject_id'=>['required', Rule::exists('subjects', 'id')],
            'lectures_description'=>['nullable','max:1000'],
            'file_path.*'=>['required', File::types(['pdf', 'pptx','xlsx','docx'])],
            'description'=>['nullable','max:1000']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $lectures=lecture::create([
            'number'=>$request->number,
            'name'=>$request->name,
            'description'=>$request->description,
            'user_id'=>$request->user_id,
            'subject_id'=>$request->subject_id,
        ]);

        $detalis = save_lecutre($request,$lectures->id);
        $data_lecure = [
            $lectures,$detalis
        ];
            return response()->json([
            'message'=>'lecture successfully stored',
            'data'=>$data_lecure,
            ] ,200);
}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $lecture = lecture::findOrfail($id);
        $subject_id = $lecture->subject_id;
        $user_id = $lecture->user_id;
        $lectures = DB::table('lectures')
        ->where('subject_id', '=', $subject_id)
        ->where('name', '<>', $lecture->name)->paginate(PAGINATION_COUNT);

        $user = DB::table('users')
        ->select('users.name')
        ->where('users.id', '=', $user_id)->get();

        $subject = DB::table('subjects')
        ->select('subjects.name')
        ->where('subjects.id', '=', $subject_id)
        ->get();

        $lecture_details = DB::table('lecture_details')
        ->where('lecture_id', '=', $lecture->id)
        ->get();

        $lectures =[
            $lecture,$lectures,$user,$subject,$lecture_details
        ];
        return $lectures;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $lecture = lecture::findOrfail($id);
        return response()->json($lecture, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $lecture = lecture::findOrfail($id);
        $validator=Validator::make($request->all(),[
            'name' => ['required','string','min:2',],
            'user_id'=>['required', Rule::exists('users', 'id')],
            'subject_id'=>['required', Rule::exists('subjects', 'id')],
            'number' => 'required',
            'description'=>['nullable','max:1000']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->user()->id !== $lecture->user_id) {
            return response()->json([
                'message'=>'Unauthorized',], 403);
        }

        $lecture->update([
            'name' => $request->name,
            'number' =>$request->number,
            'user_id' => $request->user_id,
            'subject_id' => $request->subject_id,
            'description' => $request->description,
        ]);

        return response()->json([
            'message'=>'lecture successfully updated',
            'data'=>$lecture,
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $lecture = lecture::findOrfail($id);
        if ($request->user()->id !== $lecture->user_id) {
            return response()->json([
                'message'=>'Unauthorized',], 403);
        }
        $lecture_details = $lecture->lecture_details;
        foreach ($lecture_details as $detail) {
            \File::exists($detail->file_path);
            \File::delete($detail->file_path);
            }

        $lecture->delete();
        $lecture->lecture_details()->delete();
        return response()->json([
            'message'=>'lecture successfully deleted',
            'data'=>$lecture,
            ], 200);
    }
}
