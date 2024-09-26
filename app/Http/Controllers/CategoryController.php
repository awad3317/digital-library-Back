<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data=category::get();
        return response()->json($data, 200);
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
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $data_id = category::firstOrCreate([
            'name' => $request->name,
        ]);
        $data_id = $data_id->id;
        return response()->json($data_id, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $category=category::findOrfail($id);
        $validator = validator::make($request->all(),[
            'name'=>['required','min:2',Rule::unique('categories')->ignore($id)],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $category->update([
            'name' => $request->name
        ]);
        return response()->json([
            'message'=>'category successfully updated',
            'data'=>$category,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $category=category::findOrfail($id);
        $category->delete();
        $category->Book()->delete();
        $category->Program()->delete();
        $category->course()->delete();
        return response()->json([
            'message'=>'category successfully deleted',
            'data'=>$category,
            ], 200);
    }
}
