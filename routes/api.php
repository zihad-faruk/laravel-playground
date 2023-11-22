<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\SocialController;

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

// Google Auth
Route::post('/provider/login', [SocialiteController::class, 'loginWithGoogle']);

// Dummy Purpose
Route::get('/token/{provider}', [SocialiteController::class, 'redirectToProvider']);
Route::get('/login/{provider}/callback', [SocialiteController::class, 'handleProviderCallback'])

Route::post('register', [AuthController::class,'register'] );
Route::post('login', [AuthController::class,'login'] );
Route::get('todo', [TodoController::class,'index'] );
Route::get('test', function (){
    return response()->json([
        'status' => 'success',
        'message' => 'Todo Tested successfully',
    ]);
} );

