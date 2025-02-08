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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class , 'register']);
Route::post('/login', [AuthController::class , 'login']);


/* Rutas con Sanctum */
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class , 'logout']);


    /* Rutas para la api de user */
    Route::apiResource('users', UserController::class);
    Route::get('/users/ranking', [UserController::class, 'ranking'])->name('ranking');
    Route::get('/users/points/{user}', [UserController::class , 'points'])->name('points');


});
