<?php

namespace App\Http\Controllers;

use App\Models\project;
use Illuminate\Http\Request;
use App\Http\Requests\Project_Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\validator;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // https://example.com?like=projects.title,[value] | https://example.com?department_id=[value] | https://example.com?academic_year_id=[value]
    {
        $projects=project::join('departments','departments.id','=','projects.department_id')
        ->join('academic_years','academic_years.id','=','projects.academic_year_id')
        ->select('projects.number AS Project_number','projects.title','projects.file_path','projects.supervisor','projects.image','projects.description','projects.level','departments.name AS Department_name','academic_years.year')
        ->filter()->inRandomOrder()->paginate(PAGINATION_COUNT);
        return response()->json($projects, 200);
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
    public function store(Project_Request $request)
    {
        $File_path=save_files_Project($request->file_path,$request->title);
        $image_path=save_files_Project($request->image,$request->title.'_image');
        $project=project::create([
            'number'=>$request->number,
            'title'=>$request->title,
            'level'=>$request->level,
            'file_path'=>$File_path,
            'supervisor'=>$request->supervisor,
            'image'=>$image_path,
            'description'=>$request->description,
            'department_id'=>$request->department_id,
            'academic_year_id'=>$request->academic_year_id,
        ]);
        return response()->json($project, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($number)
    {
        $number = Crypt::decrypt($number);
        $project = project::findOrfail($number);
        $department_id = $project->department_id;
        $projects = DB::table('projects')
        ->where('department_id', '=', $department_id)
        ->where('title', '<>', $project->title)->paginate(PAGINATION_COUNT);

        $department = DB::table('departments')
        ->select('departments.name')
        ->where('departments.id', '=', $department_id)->get();


        $teams = DB::table('team_projects')
        ->join('teams', 'teams.id', '=', 'team_projects.team_id')
        ->select('teams.name')
        ->where('team_projects.project_number', '=', $number)
        ->get();

        $projects =[
            $project,$projects,$department,$teams
        ];
        return $projects;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($number)
    {
        $number = Crypt::decrypt($number);
        $project = project::findOrfail($number);
        return response()->json($project, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$number)
    {
        $number = Crypt::decrypt($number);
        $project=project::findOrfail($number);
        $validator = validator::make($request->all(),[
            'number'=>['required',Rule::unique('projects')->ignore($number,'number')],
            'title'=>['required','max:100','string','min:2',Rule::unique('projects')->ignore($number,'number')],
            'level'=>['required'],
            'image'=>['nullable',Rule::when($request->hasFile('image'),[file::types(['jpeg','bmp','png','jpg'])->max(2048)]),Rule::when(is_string($request->image),'string')],
            'file_path'=>['required',Rule::when($request->hasFile('file_path'),[file::types(['pdf'])]),Rule::when(is_string($request->file_path),'string')],
            'description'=>['nullable','max:1000'],
            'department_id'=>['required',Rule::exists('departments', 'id')],
            'academic_year_id'=>['required',Rule::exists('academic_years', 'id')]
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $project_path=$project->file_path;
        $image_path=$project->image;
        if($project->file_path!=$request->file_path){
            if(\File::exists($project_path)){
                \File::delete($project_path);
            }
        $project_path=save_files_Project($request->file_path,$request->title);
    }
    if(is_null($request->image)){
        if(\File::exists($image_path)){
            \File::delete($image_path);
        }
        $image_path=null;
    }
    elseif($project->image!=$request->image){
        if(\File::exists($image_path)){
        \File::delete($image_path);
        }
        $image_path = save_files_Project($request->image,$request->title.'_image');
    }
    $project->update([
        'number'=>$request->number,
        'title'=>$request->title,
        'level'=>$request->level,
        'file_path'=>$project_path,
        'image'=>$image_path,
        'description'=>$request->description,
        'department_id'=>$request->department_id,
        'academic_year_id'=>$request->academic_year_id,
    ]);
    return response()->json([
        'message'=>'project successfully updated',
        'data'=>$project,
    ], 200);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($number)
    {
        $number = Crypt::decrypt($number);
        $project=project::findOrfail($number);
        if(\File::exists($project->file_path)){
            \File::delete($project->file_path);
            }
        if(\File::exists($project->image)){
            \File::delete($project->image);
        }
        $project->delete();
        return response()->json([
        'message'=>'project successfully deleted',
        'data'=>$project,
        ], 200);
    }
}
