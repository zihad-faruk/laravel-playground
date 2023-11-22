<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;

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

Route::post('register', [AuthController::class,'register'] );
Route::post('login', [AuthController::class,'login'] );
Route::get('todo', [TodoController::class,'index'] );
Route::get('test', function (){
    return response()->json([
        'status' => 'success',
        'message' => 'Todo Tested successfully',
    ]);
} );

