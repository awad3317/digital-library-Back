<?php

namespace App\Http\Controllers;

use App\Models\lecture_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\validator;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;


class LectureDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()  //https://example.com?lecture_id=[value]
    {
        $lectureDetails=lecture_details::join('lectures','lectures.id','=','lecture_details.lecture_id')
        ->select('lecture_details.id','lecture_details.file_path','lecture_details.description','lectures.name AS lecture_name ','lectures.number AS lecture_number')
        ->filter()->inRandomOrder()->paginate(PAGINATION_COUNT);
        return response()->json($lectureDetails, 200);
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
      //
    }

    /**
     * Display the specified resource.
     */
    public function show(lecture_details $lecture_details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $lecture_details = lecture_details::findOrfail($id);
        return response()->json($lecture_details, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $lecture_details = lecture_details::findOrfail($id);
        $validator=Validator::make($request->all(),[
            'file_path'=>['required',Rule::when($request->hasFile('file_path'),[file::types(['pdf', 'pptx','xlsx','docx'])]),Rule::when(is_string($request->file_path),'string')],
            'description'=>['nullable','max:1000']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $File_path = $lecture_details->file_path;
        if ($request->file_path != $lecture_details->file_path)
        {
            if(\File::exists($lecture_details->file_path)){
                \File::delete($lecture_details->file_path);
                }
            $extions = $request->file_path->getclientoriginalextension();
            $name = $request->file_path->getClientOriginalName();
            $detailsname = uniqid(' ',true).'-Lecture_details-.'.$extions;
            $File_path = $request->file_path->move('Lecture_details',$detailsname);
        }
        $lecture_details->update([
            'file_path'=>$File_path,
            'description'=>$request->description,
            'lecture_id'=>$lecture_details->lecture_id,
        ]);
        return response()->json([
            'message'=>'Details successfully updated',
            'data'=>$lecture_details,
            ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $lecture_details = lecture_details::findOrfail($id);
        if(\File::exists($lecture_details->file_path)){
            \File::delete($lecture_details->file_path);
            }
        $lecture_details->delete();
        return response()->json([
            'message'=>'Details successfully deleted',
            'data'=>$lecture_details,
            ], 200);
    }
}
