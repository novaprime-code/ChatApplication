<?php

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\MessageController;

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

// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/messages', [MessageController::class, 'index']);
//     Route::post('/messages', [MessageController::class, 'store']);
// });
