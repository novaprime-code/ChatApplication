<?php

use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/home', [UserController::class, 'index']);

Route::get('/register', [RegisterController::class, 'view']);
Route::post('/register', [RegisterController::class, 'store']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/update', [ProfileController::class, 'update']);

Route::get('/messages/{userId}', [ChatController::class, 'index']);
Route::post('/messages', [ChatController::class, 'store']);

Route::post('/chats', [ChatController::class, 'initChat'])->middleware('auth:sanctum');
Route::post('/chats/{chatId}/messages', [ChatController::class, 'sendMessage'])->middleware('auth:sanctum');
Route::get('/chats/{chatId}/messages', [ChatController::class, 'getChatHistory'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

    //user profile

});
