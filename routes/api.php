<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Route::apiResource('users', \App\Http\Controllers\UserController::class);

Route::get('/users', [\App\Http\Controllers\UserController::class, 'index']);

Route::get('/users/{user}', [\App\Http\Controllers\UserController::class, 'show']);

Route::post('/users', [\App\Http\Controllers\UserController::class, 'store']);

Route::patch('/users/{user}', [\App\Http\Controllers\UserController::class, 'update']);

Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
