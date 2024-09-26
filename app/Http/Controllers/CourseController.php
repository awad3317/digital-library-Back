<?php

namespace App\Http\Controllers;

use App\Models\course;
use App\Models\video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\validator;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;
use ZipArchive;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() // https://example.com?like=courses.name,[value] | https://example.com?category_id=[value] | https://example.com?accepted=[value]
    {
        if(auth()->check() and auth()->user()->user_type_id==1){
        $course = course::join('videos', 'courses.id', '=', 'videos.course_id')->join('categories', 'categories.id', '=', 'courses.category_id')
        ->select('courses.id', 'courses.name', 'courses.size_course', 'courses.image', 'courses.description', 'courses.accepted', DB::raw('COUNT(*) AS Number_of_Videos'), 'categories.name AS category_name')
        ->groupBy('courses.id')->filter()->inRandomOrder()->paginate(PAGINATION_COUNT);
        return response()->json($course, 200);
        }
        else{
        $course = course::join('videos', 'courses.id', '=', 'videos.course_id')->join('categories', 'categories.id', '=', 'courses.category_id')
        ->select('courses.id', 'courses.name', 'courses.size_course', 'courses.image', 'courses.description', 'courses.accepted', DB::raw('COUNT(*) AS Number_of_Videos'), 'categories.name AS category_name')
        ->groupBy('courses.id')->where('accepted','=',true)->filter()->inRandomOrder()->paginate(PAGINATION_COUNT);
        return response()->json($course, 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = validator::make($request->all(), validtaor_course());
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $image_path = save_files_course($request->image);
        if(auth()->check() and auth()->user()->user_type_id==1){
            $accepted=1;
        }
        else{
            $accepted=0;
        }
        $data_course = course::create([
            'name' => $request->course_name,
            'description' => $request->course_description,
            'image' => $image_path,
            'accepted' => $accepted,
            'category_id' => $request->category_id,
        ]);
        $data_video = save_video($request,$data_course->id);
        $data = [
            $data_course,$data_video
        ];
        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $course = course::findOrfail($id);
        $category_id = $course->category_id;
        $course_id = $course->id;
        //
        $file_path=video::where('course_id','=',$course_id)->select('file_path')->get();
        $zip_file=$course->name.uniqid('',true).'.zip';
        touch($zip_file);
        $zip=new ZipArchive;
        $this_zip=$zip->open($zip_file);
       if($this_zip)
    {
        foreach($file_path as $file)
        {
            $file_with_path=$file['file_path'];
            $video=substr($file_with_path,7);
            $zip->addfile($file_with_path,$video);
       }
    }
    else{
        $zip_file='error';
    }
    //
        if(auth()->check() and auth()->user()->user_type_id==1)
        {
            $courses = DB::table('courses')
            ->where('category_id', '=', $category_id)
            ->where('name', '<>', $course->name)->paginate(PAGINATION_COUNT);
        }
        else
        {
            $courses = DB::table('courses')
            ->where('category_id', '=', $category_id)
            ->where('name', '<>', $course->name)
            ->where('accepted','=',true)->paginate(PAGINATION_COUNT);
        }

        $videos = DB::table('videos')
        ->where('course_id', '=', $course_id)->get();

        $categories = DB::table('categories')
        ->select('categories.name')
        ->where('categories.id', '=', $category_id)->get();

        $courses = [
            $course, $videos, $courses,$categories,$zip_file
        ];

        return $courses;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(course $course)
    {
        $id = Crypt::decrypt($id);
        $course = course::findOrfail($id);
        return response()->json($course, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $course = course::findOrfail($id);
        $validator=Validator::make($request->all(),[
        'name'=>['required','max:30','string','min:2',],
        'image'=>['required',Rule::when($request->hasFile('image'),[file::types(['jpeg','bmp','png','jpg'])->max(2048)]),Rule::when(is_string($request->image),'string')],
        'course_description'=>['nullable','max:1000'],
        'category_id'=>['required', Rule::exists('categories', 'id')],
        'accepted'=>['required']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image_path = $course->image;
        if ($request->image != $course->image)
        {
            if(\File::exists($course->image)){
                \File::delete($course->image);
                }
            $image_path = save_files_course($request->image);
        }

        $course->update([
            'name' => $request->name,
            'description' => $request->course_description,
            'image' => $image_path,
            'accepted' => $request->accepted,
            'category_id' => $request->category_id,
        ]);
        return response()->json([
            'message'=>'course successfully updated',
            'data'=>$course,
            ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $course = course::findOrfail($id);
        if(\File::exists($course->image)){
            \File::delete($course->image);
            }

        $videos = $course->video;
        foreach ($videos as $video) {
            // Delete the video file from public
            \File::exists($video->file_path);
            \File::delete($video->file_path);
            }

        $course->delete();
        $course->video()->delete();
        return response()->json([
            'message'=>'course successfully deleted',
            'data'=>$course,
            ], 200);

}


    public function Delete_zip_file($zip_file)
    {
        unlink($zip_file);
        return 'Done';
    }
}
