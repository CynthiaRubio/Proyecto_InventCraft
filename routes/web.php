<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MaterialTypeController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\InventionTypeController;
use App\Http\Controllers\InventionController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ActionController;
use App\Http\Controllers\EventController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* Ruta pública para la página de inicio/bienvenida */
Route::get('/', function () {
    return view('home/index');
})->name('home');

/* Rutas permitidas para usuarios que no han iniciado sesión */
Route::middleware(['guest'])->group(function () {
    //Ruta que carga la vista de registro
    Route::get('/register', function () {
        return view('home/register');
    })->name('register');

    //Ruta que carga la vista de login
    Route::get('/login', function () {
        return view('home/login');
    })->name('login');

    //Ruta para mandar los datos del formulario para iniciar sesión
    Route::post('/login', [AuthController::class , 'login'])->name('form.login');

    //Ruta para mandar los datos del formulario de registro
    Route::post('/register', [AuthController::class , 'register'])->name('form.register');
});

/* Rutas permitidas para usuarios que se hayan autenticado */
Route::middleware(['auth' , 'check.actions' , 'check.experience'])->group(function () {
    
    //Ruta para cerrar sesión
    Route::post('/logout', [AuthController::class , 'logout'])->name('logout');

    //Ruta para acceder al inventario
    Route::get('/inventories', [InventoryController::class, 'index'])->name('inventories.index');

    //Ruta para acceder a los inventos de un tipo del inventario
    Route::get('/inventories/{id}', [InventoryController::class, 'show'])->name('inventories.show');

    //Ruta que carga la vista de construir edificio pasándole el id del edificio a construir
    Route::get('buildings/create/{building}', [ActionController::class, 'createBuilding'])->name('createBuilding');

     //Ruta para construir un edificio
     Route::post('buildings/construct', [ActionController::class, 'storeBuilding'])->name('storeBuilding');

    //Ruta que carga la vista de crear invento pasándole el tipo de invento
    Route::get('inventions/create/{invention_type}', [ActionController::class, 'createInvention'])->name('createInvention');

    //Ruta para construir un edificio
    Route::post('inventions/construct', [ActionController::class, 'storeInvention'])->name('storeInvention');

    //Ruta que carga el ranking de los usuarios
    Route::get('/users/ranking', [UserController::class, 'ranking'])->name('users.ranking');

    //Ruta que carga el perfil del usuario autenticado
    Route::get('/users/show', [UserController::class, 'show'])->name('users.show');

    //Ruta para asignar puntos del usuario
    Route::get('/users/points/{user}', [UserController::class , 'points'])->name('users.points');

    //Ruta para guardar la asignación de puntos del usuario
    Route::post('/users/stats/', [UserController::class , 'addStats'])->name('users.addStats');

    //Ruta para desplazarte de zona
    Route::post('/moveZone/', [ActionController::class, 'moveZone'])->name('moveZone');

    //Ruta para explorar una zona
    Route::post('/farm/', [ActionController::class, 'farmZone'])->name('farmZone');

    //Ruta para mostrar los avatares a elegir
    Route::get('/users/{user}/avatar', [UserController::class, 'showAvatarSelection'])->name('users.avatar');

    //Ruta para guardar el avatar elegido
    Route::post('/users/{user}/avatar', [UserController::class, 'changeAvatar'])->name('users.avatar.update');

    //Ruta para mostrar la pantalla de victoria (protegida por middleware que verifica que el usuario haya ganado)
    Route::get('/victory', [BuildingController::class, 'victory'])
        ->middleware('check.victory')
        ->name('buildings.victory');

    //Para todas las funciones de los controladores CRUD
    Route::resources([
            'zones' => ZoneController::class,
            'materialTypes' => MaterialTypeController::class,
            'materials' => MaterialController::class,
            'inventionTypes' => InventionTypeController::class,
            'inventions' => InventionController::class,
            'buildings' => BuildingController::class,
            'events' => EventController::class,
        ]);
});
