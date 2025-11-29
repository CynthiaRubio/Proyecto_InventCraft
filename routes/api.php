<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ZoneController;
use App\Http\Controllers\Api\BuildingController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\ActionController;
use App\Http\Controllers\Api\InventionController;
use App\Http\Controllers\Api\InventionTypeController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\MaterialTypeController;

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

/* Rutas protegidas con autenticación */
Route::middleware('auth:api')->group(function () {
    /* Rutas para la API de zonas */
    Route::prefix('zones')->group(function () {
        Route::get('/', [ZoneController::class, 'index']); // Listar todas las zonas
        Route::get('/{id}', [ZoneController::class, 'show']); // Ver una zona específica
    });

    /* Rutas para la API de edificios */
    Route::prefix('buildings')->group(function () {
        Route::get('/', [BuildingController::class, 'index']); // Listar todos los edificios
        Route::get('/{id}', [BuildingController::class, 'show']); // Ver un edificio específico
        Route::get('/victory/check', [BuildingController::class, 'victory']); // Verificar victoria
    });

    /* Rutas para la API de inventarios */
    Route::prefix('inventories')->group(function () {
        Route::get('/', [InventoryController::class, 'index']); // Ver inventario completo del usuario
        Route::get('/{id}', [InventoryController::class, 'show']); // Ver inventos de un tipo específico
    });

    /* Rutas para la API de acciones */
    Route::prefix('actions')->group(function () {
        Route::post('/move-zone', [ActionController::class, 'moveZone']); // Mover a otra zona
        Route::post('/farm-zone', [ActionController::class, 'farmZone']); // Explorar una zona
        Route::get('/buildings/{id}/create', [ActionController::class, 'createBuilding']); // Info para construir edificio
        Route::post('/buildings', [ActionController::class, 'storeBuilding']); // Construir edificio
        Route::get('/inventions/{id}/create', [ActionController::class, 'createInvention']); // Info para crear invento
        Route::post('/inventions', [ActionController::class, 'storeInvention']); // Crear invento
    });

    /* Rutas para la API de inventos */
    Route::prefix('inventions')->group(function () {
        Route::get('/', [InventionController::class, 'index']); // Listar todos los inventos
        Route::get('/{id}', [InventionController::class, 'show']); // Ver un invento específico
        Route::delete('/{id}', [InventionController::class, 'destroy']); // Eliminar un invento
    });

    /* Rutas para la API de tipos de inventos */
    Route::prefix('invention-types')->group(function () {
        Route::get('/', [InventionTypeController::class, 'index']); // Listar todos los tipos de inventos
        Route::get('/{id}', [InventionTypeController::class, 'show']); // Ver un tipo de invento específico
    });

    /* Rutas para la API de materiales */
    Route::prefix('materials')->group(function () {
        Route::get('/', [MaterialController::class, 'index']); // Listar todos los materiales
        Route::get('/{id}', [MaterialController::class, 'show']); // Ver un material específico
    });

    /* Rutas para la API de tipos de materiales */
    Route::prefix('material-types')->group(function () {
        Route::get('/', [MaterialTypeController::class, 'index']); // Listar todos los tipos de materiales
        Route::get('/{id}', [MaterialTypeController::class, 'show']); // Ver un tipo de material específico
    });
});
