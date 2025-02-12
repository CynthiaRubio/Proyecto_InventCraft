<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;

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

/* Rutas con Sanctum */
// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/logout', [AuthController::class , 'logout']);

// });

/* Rutas para la api de users */
    // - alternativa: // Route::middleware('auth:api')->group(function () {
Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'users'
], function () {
    Route::get('/', [UserController::class, 'index']); // Listar usuarios
    Route::get('/me', [UserController::class, 'show']); // Ver perfil del usuario autenticado
    Route::get('/ranking', [UserController::class, 'ranking']); // Ranking de jugadores
    Route::get('/points', [UserController::class, 'points']); // Ver puntos sin asignar
    Route::post('/stats', [UserController::class, 'addStats']); // Asignar puntos a estadísticas
    Route::post('/avatar', [UserController::class, 'changeAvatar']); // Cambiar avatar
});

/* Rutas para la API de eventos */
Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index']); // Obtener todos los eventos
    Route::post('/', [EventController::class, 'store']); // Crear un evento
    Route::get('/{id}', [EventController::class, 'show']); // Obtener un evento
    Route::post('/{id}', [EventController::class, 'update']); // Actualizar un evento
    Route::delete('/{id}', [EventController::class, 'destroy']); // Eliminar un evento

});

/* Rutas para la api de auth */
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});
