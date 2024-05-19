<?php

use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VideoCallController;
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

Route::get('/register', [RegisterController::class, 'view']);
Route::post('/register', [RegisterController::class, 'store']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/home', [UserController::class, 'index'])->name('home');

    Route::get('/me', [ProfileController::class, 'index']);
    Route::get('/update', [ProfileController::class, 'update']);
    Route::get('/users', [UserController::class, 'index']);

    Route::get('/messages/{userId}', [ChatController::class, 'index']);
    Route::post('/messages', [ChatController::class, 'store']);

    Route::post('/chats', [ChatController::class, 'initChat']);
    Route::post('/chats/{chatId}/messages', [ChatController::class, 'sendMessage']);
    Route::get('/chats/{chatId}/messages', [ChatController::class, 'getChatHistory']);

    Route::post('/video-calls', [VideoCallController::class, 'initiateCall']);
    Route::post('/video-calls/{callId}/accept', [VideoCallController::class, 'acceptCall']);
    Route::post('/video-calls/{callId}/reject', [VideoCallController::class, 'rejectCall']);
    Route::post('/video-calls/{callId}/end', [VideoCallController::class, 'endCall']);
    Route::get('/video-calls/{callId}/status', [VideoCallController::class, 'getCallStatus']);

});
