<?php

use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\MessageController;
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

Route::post('/register', [RegisterController::class, 'store']);
Route::get('/users', [UserController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/home', [UserController::class, 'index'])->name('home');


    Route::get('/me', [ProfileController::class, 'index']);
    Route::get('/update', [ProfileController::class, 'update']);

    Route::apiResource('/chats', ChatController::class)->only('index', 'store', 'destroy');
    Route::apiResource('/chat/{chatId}/messages', MessageController::class)->only('index', 'store');

    Route::post('/video-calls', [VideoCallController::class, 'initiateCall']);
    Route::post('/video-calls/{callId}/accept', [VideoCallController::class, 'acceptCall']);
    Route::post('/video-calls/{callId}/reject', [VideoCallController::class, 'rejectCall']);
    Route::post('/video-calls/{callId}/end', [VideoCallController::class, 'endCall']);
    Route::get('/video-calls/{callId}/status', [VideoCallController::class, 'getCallStatus']);

});
