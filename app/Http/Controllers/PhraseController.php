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
        $phrases = Phrase::all()->map(function ($phrase) {
            return collect($phrase->toArray())
                ->except(['user_id', 'created_at', 'updated_at'])
                ->all();
        });

        return response()->json([
            'status' => true,
            'message' => 'Phrases Retrieved Successfully',
            'data' => $phrases
        ], 200);
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
                'author' => 'string|max:255',
                'source' => 'required|string|max:100',
                'category' => 'required|string|max:100',
                'status' => 'string|max:100',
                'language' => 'string|max:20',
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

        // Create Phrase
        $user = Phrase::create([
            'phrase' => $request->phrase,
            'author' => $request->author,
            'source' => $request->source,
            'category' => $request->category,
            'status' => $request->status,
            'language' => $request->language,
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
        $phrase = Phrase::find($id)->only(['id', 'phrase', 'author', 'source', 'category', 'status', 'language']);

        return response()->json([
            'status' => true,
            'message' => 'Phrase Retrieved Successfully',
            'data' => $phrase
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //Validate Phrase
        $validatePhrase = Validator::make(
            $request->all(),
            [
                'phrase' => 'required|string|max:255',
                'author' => 'string|max:255',
                'source' => 'required|string|max:100',
                'category' => 'required|string|max:100',
                'status' => 'string|max:100',
                'language' => 'string|max:20',
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

        // Find Phrase
        $phrase = Phrase::find($id);

        // Message if phrase not found
        if (!$phrase) {
            return response()->json([
                'status' => false,
                'message' => 'Phrase Not Found',
                'data' => null
            ], 404);
        }

        // Update Phrase

        $phrase->phrase = $request->phrase;
        $phrase->author = $request->author;
        $phrase->source = $request->source;
        $phrase->category = $request->category;
        $phrase->status = $request->status;
        $phrase->language = $request->language;

        $phrase->save();

        // Return response
        return response()->json([
            'status' => true,
            'message' => 'Phrase Updated Successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $phrase = Phrase::find($id);

        // Message if phrase not found
        if (!$phrase) {
            return response()->json([
                'status' => false,
                'message' => 'Phrase Not Found',
            ], 404);
        }

        $phrase->delete();

        return response()->json([
            'status' => true,
            'message' => 'Phrase Deleted Successfully',
        ], 200);
    }
}
