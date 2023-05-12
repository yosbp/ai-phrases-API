<?php

namespace App\Http\Controllers;

use App\Models\Phrase;
use Faker\Provider\ar_EG\Text;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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


    public function getPhrasePublic()
    {
        $phrase = Phrase::where('status', 'active')->where('language', 'en')->inRandomOrder()->limit(1)->get()->map(function ($phrase) {
            return collect($phrase->toArray())
                ->except(['user_id', 'created_at', 'updated_at', 'language', 'status', 'category']);
        });

        return response()->json(
            $phrase,
            200
        );
    }


    /**
     * Display a random listing of the resource based in fetch number.
     */
    public function getPhrasesPublic(int $num, ?string $lang)
    {
        $language = empty($lang) ? 'en' : $lang;
        $phrases = Phrase::where('status', 'active')->where('language', $language)->inRandomOrder()->limit($num)->get()->map(function ($phrase) {
            return collect($phrase->toArray())
                ->except(['user_id', 'created_at', 'updated_at', 'language', 'status', 'category'])
                ->all();
        });

        if ($phrases->count() < $num) {
            return response()->json([
                'status' => false,
                'message' => 'Not enough phrases',
            ], 404);
        };

        return response()->json([
            'phrases' => $phrases
        ], 200);
    }

    /**
     * Save New phrase from public users.
     */
    public function newPhrasePublic(Request $request)
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

        //Translate and save in another language


        $from = $request->language == 'en' ? 'en' : 'es';
        $to = $request->language == 'en' ? 'es' : 'en';

        $response = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => env('API_KEY_AZURE_TRANSLATE'),
            'Ocp-Apim-Subscription-Region' => 'eastus',
            'Content-Type' => 'application/json',
        ])->post('https://api.cognitive.microsofttranslator.com/translate?api-version=3.0&from=' . $from . '&to=' . $to, [
            ['Text' => $request->phrase]
        ]);

        if ($response->ok()) {
            $translatedText = $response->json()[0]['translations'][0]['text'];
            $translate = true;

            // Create Translate Phrase

            $translatePhrase = Phrase::create([
                'phrase' => $translatedText,
                'author' => $request->author,
                'source' => $request->source,
                'category' => $request->category,
                'status' => $request->status,
                'language' => $to,
                'user_id' => 0,
            ]);
        } else {
            $translate = false;
        }


        // Create Phrase

        $phrase = Phrase::create([
            'phrase' => $request->phrase,
            'author' => $request->author,
            'source' => $request->source,
            'category' => $request->category,
            'status' => $request->status,
            'language' => $request->language,
            'user_id' => 0,
        ]);

        // Return response
        return response()->json([
            'status' => true,
            'message' => 'Phrase Created Successfully',
            'translate' => $translate,
        ], 200);
    }

    /**
     * Get Basic datas to Dashboard.
     */

    public function dashboardData()
    {
        $phrases = Phrase::all()->count();
        $pending = Phrase::where('status', 'pending')->count();
        $actives = Phrase::where('status', 'active')->count();

        return response()->json([
            'status' => true,
            'message' => 'Dashboard Data Retrieved Successfully',
            'data' => [
                'phrases' => $phrases,
                'pending' => $pending,
                'actives' => $actives,
            ]
        ], 200);
    }
}
