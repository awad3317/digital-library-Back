<?php
/*
   PAGINATION_COUNT      => this CONSTANT in Helpers folder in formatBytes.php
   formatBytes()         => this Function in Helpers folder in formateByte.php
   save_files_programs() => this Function in Helpers folder in Programs.php
*/

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use App\Http\Requests\Porgram_Request;
use Illuminate\Support\Facades\Crypt;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() // https://example.com?like=programs.Name,[value] | https://example.com?category_id=[value] | https://example.com?accepted=[value]
    {
        if(auth()->check() and auth()->user()->user_type_id==1){
            $Programs = Program::join('categories', 'categories.id', '=', 'programs.category_id')
            ->Select('programs.id', 'programs.Name as name',
            'programs.File_path', 'programs.Description',
            'programs.image', 'programs.Accepted',
            'programs.Version', 'programs.size_Program',
            'programs.category_id', 'categories.name AS category_name')->filter()->inRandomOrder()->paginate(PAGINATION_COUNT);
            return response()->json($Programs, 200);
        }
        else{
            $Programs = Program::join('categories', 'categories.id', '=', 'programs.category_id')
            ->Select_Progeam_With_category_name()->where('Accepted','=',true)->filter()->inRandomOrder()->paginate(PAGINATION_COUNT);
            return response()->json($Programs, 200);
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
        $category_id=Crypt::decrypt($request->category_id);
        $validator = validator::make($request->all(),[
            'name' =>['required','max:30','string','min:2','unique:Programs,name'] ,
            'image'=>['required',File::image()->types(['jpeg','bmp','png','jpg'])->max(2048),],
            'file_path'=>['required',File::types(['zip','rar']),'max:307200'],
            'category_id'=>['required', function ($attribute, $value, $fail) use ($category_id) {
                $category = DB::table('categories')->where('id', $category_id)->exists();
                if (!$category) {
                    $fail($attribute.' غير صالح');
                }
            }],
            'description'=>['nullable','max:1000'],
            'Version'=>['nullable',]
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $size_Program = formatBytes($request->file_path->getSize());
        $Program_path = save_files_programs($request->file_path,$request->name);
        $image_path = save_files_programs($request->image,'image');
        if(auth()->check() and auth()->user()->user_type_id==1){
            $accepted=1;
        }
        else{
            $accepted=0;
        }
        $data = Program::create([
            'File_path' => $Program_path,
            'Name' => $request->name,
            'Description' => $request->Description,
            'image' => $image_path,
            'Accepted' => $accepted,
            'Version' => $request->Version,
            'size_Program' => $size_Program,
            'category_id' => $category_id,
        ]);
        return response()->json($data->id, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $program = Program::findOrfail($id);
        $category_id = $program->category_id;

        if(auth()->check() and auth()->user()->user_type_id==1)
        {
            $programs = DB::table('programs')
            ->where('category_id', '=', $category_id)
            ->where('Name', '<>', $program->Name)->get();
        }
        else
        {
            $programs = DB::table('programs')
            ->where('category_id', '=', $category_id)
            ->where('Name', '<>', $program->Name)
            ->where('Accepted','=',true)->get();
        }


        $categories = DB::table('categories')
        ->select('categories.name')
        ->where('categories.id', '=', $category_id)->get();

        $programs = [
            $program, $programs, $categories,
        ];
        return response()->json($programs, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $program = Program::findOrfail($id);
        return response()->json($program, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $category_id=Crypt::decrypt($request->category_id);
        $data=Program::findOrfail($id);
        $validator = validator::make($request->all(),[
            'name' =>['required','max:30','string','min:2',Rule::unique('programs')->ignore($id)] ,
            'image'=>['required',Rule::when($request->hasFile('image'),[file::types(['jpeg','bmp','png','jpg'])->max(2048)]),Rule::when(is_string($request->image),'string')],
            'file_path'=>['required',Rule::when($request->hasFile('file_path'),[file::types(['zip','rar'])]),Rule::when(is_string($request->file_path),'string')],
            'category_id'=>['required',Rule::exists('categories', 'id')],
            'description'=>['nullable','max:1000'],
            'version'=>['nullable'],
            'accepted'=>['required']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $Program_path=$data->File_path;
        $size_Program=$data->size_Program;
        $image_path=$data->image;
        if($data->File_path!=$request->file_path){
            if(\File::exists($Program_path)){
                \File::delete($Program_path);
            }
            $Program_path = save_files_programs($request->file_path,$request->name);
            $size_Program = formatBytes(filesize($Program_path));
        }
        if($data->image!=$request->image){
            if(\File::exists($image_path)){
            \File::delete($image_path);
            }
            $image_path = save_files_programs($request->image,'image');
        }
        $data->update([
            'File_path' => $Program_path,
            'Name' => $request->name,
            'Description' => $request->description,
            'image' => $image_path,
            'Accepted' => $request->accepted,
            'Version' => $request->version,
            'size_Program' => $size_Program,
            'category_id' => $request->category_id,
        ]);
        return response()->json([
            'message'=>'Program successfully updated',
            'data'=>$data,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $Program=Program::findOrfail($id);
        if(\File::exists($Program->image)){
            \File::delete($Program->image);
            }
        if(\File::exists($Program->File_path)){
            \File::delete($Program->File_path);
        }
        $Program->delete();
        return response()->json([
        'message'=>'Program successfully deleted',
        'data'=>$Program,
        ], 200);
    }
}
