<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PhraseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/phrases', [PhraseController::class, 'index']);
    Route::post('/new', [PhraseController::class, 'store']);
    Route::get('/phrase/{id}', [PhraseController::class, 'show']);
    Route::put('/phrase/{id}', [PhraseController::class, 'update']);
    Route::delete('/phrase/{id}', [PhraseController::class, 'destroy']);
    Route::get('/dashboard', [PhraseController::class, 'dashboardData']);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('/newphrase', [PhraseController::class, 'newPhrasePublic']);
Route::get('/phrase', [PhraseController::class, 'getPhrasePublic']);
Route::get('/phrases/{num}/{lang?}', [PhraseController::class, 'getPhrasesPublic'])
    ->where('num', '[0-9]+')
    ->where('lang', '(en|es)?')
    ->defaults('lang', 'en');

