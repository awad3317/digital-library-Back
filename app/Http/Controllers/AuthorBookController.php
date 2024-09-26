<?php

namespace App\Http\Controllers;

use App\Http\Requests\author_book_Request;
use App\Models\author_book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\validator;
use Illuminate\Validation\Rule;

class AuthorBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
        $validator = validator::make($request->all(),[
            'book_id' => ['required',Rule::exists('books', 'id')],
            'author_id' => ['required',Rule::exists('authors', 'id')],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $data = author_book::firstOrCreate([
            'book_id' => $request->book_id,
            'author_id' => $request->author_id,
        ]);
        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(author_book $author_book)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(author_book $author_book)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, author_book $author_book)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(author_book $author_book)
    {
    }
}
