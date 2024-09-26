<?php

namespace App\Http\Controllers;

use App\Models\suggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\validator;
use Illuminate\Support\Facades\Crypt;

class SuggestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suggestions=suggestion::orderBy('id','desc')->paginate(PAGINATION_COUNT);
        return response()->json($suggestions, 200);
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
            'suggestion'=>['required','min:2','string','max:1000']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $suggestion=suggestion::create([
            'suggestion'=>$request->suggestion
        ]);
        return response()->json([
            'message'=>'suggestion successfully stored',
            'data'=>$suggestion,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(suggestion $suggestion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $suggestion = suggestion::findOrfail($id);
        return response()->json($suggestion, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $suggestion = suggestion::findOrfail($id);
        $validator = validator::make($request->all(),[
            'suggestion'=>['required','min:2','string','max:1000']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $suggestion->update([
            'suggestion'=>$request->suggestion
        ]);
        return response()->json([
            'message'=>'suggestion successfully updated',
            'data'=>$suggestion,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $suggestion=suggestion::findOrfail($id);
        $suggestion->delete();
        return response()->json([
        'message'=>'suggestion successfully deleted',
        'data'=>$suggestion,
        ], 200);
    }
}
