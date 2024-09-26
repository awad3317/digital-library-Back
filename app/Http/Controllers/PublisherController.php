<?php
/*
   validtaor_Publisher() => this Function in Helpers folder in Publisher.php
*/

namespace App\Http\Controllers;

use App\Http\Requests\Publisher_Request;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\validator;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Publisher = Publisher::all();
        return response()->json($Publisher, 200);


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
    public function store(Publisher_Request $request)
    {
            $data = Publisher::firstOrCreate([
                'name' => $request->Name,
            ]);
            $data_id = $data->id;
            return response()->json($data_id, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Publisher $publisher)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publisher $publisher)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publisher $publisher)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publisher $publisher)
    {
    }
}
