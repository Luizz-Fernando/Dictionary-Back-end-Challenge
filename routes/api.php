<?php

use App\Http\Controllers\DictionaryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return response()->json([
        "message" => "Fullstack Challenge 🏅 - Dictionary"
    ]);
});

Route::middleware('auth:api')->group(function () {
    Route::get('/user/me', [UserController::class, 'show']);
    Route::get('/user/me/favorites', [UserController::class, 'getFavorites']);
    Route::get('/user/me/history', [UserController::class, 'history']);

    Route::get('/entries/en', [DictionaryController::class, 'index']);
    Route::get('/entries/en/{word}', [DictionaryController::class, 'show']);
    Route::post('/entries/en/{word}/favorite', [DictionaryController::class, 'favorite']);
    Route::delete('/entries/en/{word}/unfavorite', [DictionaryController::class, 'unfavorite']);

});

Route::post('/login', [AuthController::class, 'singin']);

Route::prefix('auth')->group(function () {
    Route::post('/singup', [AuthController::class, 'singup']);
});


