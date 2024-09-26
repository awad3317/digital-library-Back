<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\validator;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Advertisements=Advertisement::orderBy('id','desc')->take(3)->get();
        return response()->json($Advertisements, 200);
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
            'title'=>['required','string','min:2'],
            'description'=>['nullable','max:1000'],
            'image'=>['required',File::image()->types(['jpeg','bmp','png','jpg'])->max(2048)]
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $File_path=save_files_Advertisement($request->image,$request->title);
        $Advertisement=Advertisement::create([
            'title'=>$request->title,
            'description'=>$request->description,
            'image'=>$File_path
        ]);
        return response()->json([
            'message'=>'Advertisement successfully stored',
            'data'=>$Advertisement,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $Advertisement = Advertisement::where('id', '=', $id)->get();
        return response()->json($Advertisement, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $Advertisement=Advertisement::findOrfail($id);
        return response()->json($Advertisement, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $id = Crypt::decrypt($id);
        $Advertisement = Advertisement::findOrfail($id);
        $validator = validator::make($request->all(),[
            'title'=>['required','string','min:2'],
            'description'=>['nullable','max:1000'],
<<<<<<< Updated upstream
            'image'=>['required',Rule::when($request->hasFile('image'),[file::types(['jpeg','bmp','png','jpg'])->max(2048)]),Rule::when(is_string($request->image),'string')]
=======
            'image'=>['required',Rule::when($request->hasFile('image'),[file::types(['jpeg','bmp','png','jpg'])->max(2048)]),Rule::when(is_string($request->image),'string')],
>>>>>>> Stashed changes
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $File_path = $Advertisement->image;

        if ($request->image != $Advertisement->image)
        {
        if(\File::exists($Advertisement->image)){
            \File::delete($Advertisement->image);
            }
        $File_path=save_files_Advertisement($request->image,$request->title);
        }

        $Advertisement->update([
            'title'=>$request->title,
            'description'=>$request->description,
            'image'=>$File_path
        ]);
        return response()->json([
            'message'=>'Advertisement successfully updated',
            'data'=>$Advertisement,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $Advertisement=Advertisement::findOrfail($id);
        if(\File::exists($Advertisement->image)){
            \File::delete($Advertisement->image);
        }
        $Advertisement->delete();
        return response()->json([
            'message'=>'Advertisement successfully deleted',
            'data'=>$Advertisement,
        ], 200);
    }
}
