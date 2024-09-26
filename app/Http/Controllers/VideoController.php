<?php

namespace App\Http\Controllers;

use App\Models\video;
use App\Models\course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\validator;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;



class VideoController extends Controller
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
    public function store(Request $request,$id)
    {
        $id = Crypt::decrypt($id);
        $validator=Validator::make($request->all(),[
            'file_path.*' => ['required',File::types(['mp4', 'mov', 'mkv','MP4'])],
            'description'=>['nullable','max:1000']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $counter=0;
    foreach($request->file_path as $video) {
        $extions = $video->getclientoriginalextension();
        $name = $video->getClientOriginalName();
        $videoname = uniqid(' ',true).'-video-.'.$extions;
        $counter = $counter + $video->getSize();
        $size_video = formatBytes($video->getSize());
        $File_path = $video->move('videos',$videoname);
        $data_video = video::create([
        'file_path' => $File_path,
        'name' => $name,
        'size_video' => $size_video,
        'course_id' => $id,
        ]);
        }
        $size = course::where('id', '=', $id)->first();
        $vaule = unformatBytes($size->size_course);
        $vaule = $vaule + $counter;
        $size_course = formatBytes($vaule);
        $size->update(['size_course' => $size->size_course = $size_course]);
        return response()->json($data_video, 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(video $video)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $video = video::findOrfail($id);
        return response()->json($video, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $id = Crypt::decrypt($id);
        $video = video::findOrfail($id);
        $course_id = $video->course_id;
        $validator=Validator::make($request->all(),[
            'file_path' => ['required',Rule::when($request->hasFile('file_path'),[file::types(['mp4', 'mov', 'mkv','MP4'])]),Rule::when(is_string($request->file_path),'string')],
            'description'=>['nullable','max:1000']

        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $File_path = $video->file_path;
        $size_video = $video->size_video;
        if ($request->file_path != $video->image)
        {
            if(\File::exists($video->file_path)){
                \File::delete($video->file_path);
                }
                $extions = $request->file_path->getclientoriginalextension();
                $name = $request->file_path->getClientOriginalName();
                $videoname = uniqid(' ',true).'-video-.'.$extions;
                $size_video = formatBytes($request->file_path->getSize());
                $File_path = $request->file_path->move('videos',$videoname);
                $size = course::where('id', '=', $course_id)->first();
                $vaule = unformatBytes($size->size_course);
                $size_video = unformatBytes($size_video);
                $oldvideo =  unformatBytes($video->size_video);
                $vaule = $vaule + $size_video;
                $vaule = $vaule - $oldvideo;
                $size_course = formatBytes($vaule);
                $size_video = formatBytes($size_video);
                $size->update(['size_course' => $size->size_course = $size_course]);
                }
        $video->update([
            'file_path' => $File_path,
            'name' => $name,
            'size_video' => $size_video,
            'course_id' => $course_id,
        ]);
        return response()->json([
            'message'=>'video successfully updated',
            'data'=>$video,
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $video = video::findOrfail($id);
        $course_id = $video->course_id;
        if(\File::exists($video->file_path)){
            \File::delete($video->file_path);
            }
        //Delete Video size from course
        $size = course::where('id', '=', $course_id)->first();
        $vaule = $size->size_course;
        $vaule = unformatBytes($vaule);
        $deltesize = unformatBytes($video->size_video);
        $vaule = $vaule - $deltesize;
        $vaule = formatBytes($vaule);
        $size->update(['size_course' => $size->size_course = $vaule]);
        $video->delete();
        return response()->json([
            'message'=>'Video successfully deleted',
            'data'=>$video,
            ], 200);
    }
}
