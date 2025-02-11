<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MaterialTypeController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\InventionTypeController;
use App\Http\Controllers\InventionController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\ActionBuildingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ActionController;
use App\Http\Controllers\ResourceController;

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

/* Rutas permitidas para usuarios que no han iniciado sesión */
Route::middleware(['guest'])->group(function () {
    //Ruta que carga la vista de registro, que es la inicial
    Route::get('/', function () {
        return view('home/register');
    })->name('register');

    //Ruta que carga la vista de login
    Route::get('/login', function () {
        return view('home/login');
    }) -> name('login');

    //Ruta para mandar los datos del formulario para iniciar sesión
    Route::post('/login', [AuthController::class , 'login'])->name('form.login');

    //Ruta para mandar los datos del formulario de registro
    Route::post('/register', [AuthController::class , 'register'])->name('form.register');
});

/* Rutas permitidas para usuarios que se hayan autenticado */
Route::middleware(['auth' , 'check.actions' , 'check.experience'])->group(function () {
    //Ruta para cerrar sesión
    Route::get('/logout', [AuthController::class , 'logout'])->name('logout');//->middleware('auth');

    //Ruta para acceder al inventario
    Route::get('/inventories/index', [InventoryController::class, 'show'])->name('inventories.index');

    //Ruta para acceder a los inventos de un tipo del inventario
    Route::get('/inventories/show', [InventoryController::class, 'show'])->name('inventories.show');

    //Ruta que carga la vista de construir edificio pasándole el id del edificio a construir
    Route::get('buildings/create/{building}', [ActionController::class, 'createBuilding'])->name('createBuilding');

     //Ruta para construir un edificio
     Route::post('buildings/construct', [ActionController::class, 'storeBuilding'])->name('storeBuilding');

    //Ruta que carga la vista de crear invento pasándole el tipo de invento
    Route::get('inventions/create/{invention_type}', [InventionController::class, 'create'])->name('inventions.create.withType');

    //Ruta que carga la vista de crear invento pasándole el tipo de invento
    //Route::get('inventions/create/{invention_type}', [ActionController::class, 'createInvention'])->name('createInvention');

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

    //Para todas las funciones de los controladores CRUD
    Route::resources([
            'zones' => ZoneController::class,
            'materialTypes' => MaterialTypeController::class,
            'materials' => MaterialController::class,
            'inventionTypes' => InventionTypeController::class,
            'inventions' => InventionController::class,
            'buildings' => BuildingController::class,
            'actionBuildings' => ActionBuildingController::class,
            'inventories' => InventoryController::class,
            //'users' => UserController::class,
            // 'stats' => StatController::class,
            // 'actions' => ActionController::class,
            // 'actionTypes' => ActionType::class,
        ]);
});



//Route::get('/buildings/{name}', [BuildingController::class , 'create']);

// Route::resource('/materialTypes', MaterialTypeController::class); /* De esta forma usa todos los CRUD */
// Route::get('/materialTypes', [MaterialTypeController::class, 'index']);
// Route::get('/materialTypes/{id}', [MaterialTypeController::class, 'show']);//->name('materialTypes.show');


/*
Route::get('/', function () {
    return view('welcome');
});
*/

// Route::get('/', [BuildingController::class, 'index']);

// // Rutas adicionales para estadísticas de building
// Route::get('buildings/{building}/stats/select', [BuildingController::class, 'selectStat'])->name('buildings.selectStat');
// Route::post('buildings/{building}/stats', [BuildingController::class, 'addStat'])->name('buildings.addStat');
// Route::get('buildings/{building}/stats/{stat}/edit', [BuildingController::class, 'editStat'])->name('buildings.editStat');
// Route::put('buildings/{building}/stats/{stat}', [BuildingController::class, 'updateStat'])->name('buildings.updateStat');
// Route::delete('buildings/{building}/stats/{stat}', [BuildingController::class, 'removeStat'])->name('buildings.removeStat');

// // Rutas adicionales para estadísticas de invention
// Route::get('inventions/{invention}/stats/select', [InventionController::class, 'selectStat'])->name('inventions.selectStat');
// Route::post('inventions/{invention}/stats', [InventionController::class, 'addStat'])->name('inventions.addStat');
// Route::get('inventions/{invention}/stats/{stat}/edit', [InventionController::class, 'editStat'])->name('inventions.editStat');
// Route::put('inventions/{invention}/stats/{stat}', [InventionController::class, 'updateStat'])->name('inventions.updateStat');
// Route::delete('inventions/{invention}/stats/{stat}', [InventionController::class, 'removeStat'])->name('inventions.removeStat');

// // Rutas adicionales para estadísticas de user
// Route::get('users/{user}/stats/select', [UserController::class, 'selectStat'])->name('users.selectStat');
// Route::post('users/{user}/stats', [UserController::class, 'addStat'])->name('users.addStat');
// Route::get('users/{user}/stats/{stat}/edit', [UserController::class, 'editStat'])->name('users.editStat');
// Route::put('users/{user}/stats/{stat}', [UserController::class, 'updateStat'])->name('users.updateStat');
// Route::delete('users/{user}/stats/{stat}', [UserController::class, 'removeStat'])->name('users.removeStat');

// Route::resources([
//     'buildings' => BuildingController::class,
//     'users' => UserController::class,
//     'inventions' => InventionController::class,
// ]);
