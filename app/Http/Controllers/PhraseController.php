<?php

namespace App\Http\Controllers;

use App\Models\Phrase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PhraseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validate Phrase
        $validatePhrase = Validator::make(
            $request->all(),
            [
                'phrase' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'source' => 'required|string|max:100',
                'category' => 'required|string|max:100',
            ]
        );

        // Message if validation fails
        if ($validatePhrase->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validatePhrase->errors()
            ], 401);
        }

        // Create User
        $user = Phrase::create([
            'phrase' => $request->phrase,
            'author' => $request->author,
            'source' => $request->source,
            'category' => $request->category,
            'user_id' => $request->user()->id,
        ]);

        // Return response
        return response()->json([
            'status' => true,
            'message' => 'Phrase Created Successfully',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
