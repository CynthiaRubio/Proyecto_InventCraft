<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Inventory;
use App\Models\Zone;
use App\Models\UserStat;
use App\Models\Stat;

use App\Services\ActionManagementService;

class AuthController extends Controller
{
    public function __construct(
        private ActionManagementService $action_service,
    ) {
    }

    /**
     * Función para validar los datos de registro y generar un nuevo usuario
     */
    public function register(Request $request){
        
        /* Lógica de validación de datos */
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        /* Crear el usuario */
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']), // Hash::make($validatedData['password'])
            'level' => 0,
            'experience' => 0,
            'unasigned_points' => 15,
            'avatar' => null,
        ]);

        /* Crear el inventario del usuario */
        Inventory::create([
            'user_id' => $user->_id,
        ]);

        /* Asignar zona de inicio aleatoria para el jugador creando una acción de mover */
        $zones = Zone::all();
        $zone = $zones->random();

        $this->action_Service->createAction('Mover' , $zone->_id, 'Zone' , 0);

        /* Creamos las estadísticas del usuario */
        $stats = Stat::all();
        foreach($stats as $stat){
            UserStat::create([
                'user_id' => $user->_id,
                'stat_id' => $stat->_id,
                'value' => 0,
            ]);
        }
        
        /* Generar token de Sanctum */
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado con éxito',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Función para validar el correo electrónico y la contraseña
     */
    public function login(Request $request){

        // //Lógica de validación de datos
        // $validator = Validator::make($request->all(), [
        //     'email' => 'required|string|email',
        //     'password' => 'required|string', 
        // ],[
        //     'email.required' => 'El correo electrónico es obligatorio',
        //     'email.email' => 'Introduce una dirección de correo electrónico válida',
        //     'password.required' => 'La contraseña es obligatoria',
        // ]);

        // if($validator->fails()){
        //     return redirect()->back()->withErrors($validator->errors());
        // }

        $credentials = $request->only('email' , 'password');
        if(!$token = Sanctum::attempt($credentials)){
            return response()->json(['error' => 'Credenciales incorrectas'] , 401);
        }
        return response()->json(['token' => $token] , 200);

        // $user = User::where('email', $request->email)->first();

        // if (!$user || !Hash::check($request->password, $user->password)) {
        //     return response()->json(['error' => 'Credenciales incorrectas'], 401);
        // }

        // // Generar token
        // $token = $user->createToken('auth_token')->plainTextToken;

        // return response()->json([
        //     'message' => 'Inicio de sesión exitoso',
        //     'user' => $user,
        //     'token' => $token
        // ]);

    }

    /**
     * Función para cerrar la sesión del usuario y volver a home
     */
    public function logout(Request $request){
        
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Cierre de sesión exitoso'
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
