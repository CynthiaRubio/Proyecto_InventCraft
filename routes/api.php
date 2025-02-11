<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('/register', [AuthController::class , 'register']);
// Route::post('/login', [AuthController::class , 'login']);


/* Rutas con Sanctum */
// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/logout', [AuthController::class , 'logout']);

// });

/* Rutas para la api de user */
Route::apiResource('users', 'App\Http\Controllers\Api\UserController');
Route::get('ranking', 'App\Http\Controllers\Api\UserController@ranking');
Route::get('points/{user}', 'App\Http\Controllers\Api\UserController@points');
Route::get('users/show/{user}', 'App\Http\Controllers\Api\UserController@users/show');



/* Rutas para la Api de AuthController */
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('register', 'App\Http\Controllers\Api\AuthController@register');
    Route::post('login', 'App\Http\Controllers\Api\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\Api\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\Api\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\Api\AuthController@me');


});
