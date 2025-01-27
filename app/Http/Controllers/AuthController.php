<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Inventory;
use App\Models\Action;
use App\Models\Zone;

class AuthController extends Controller
{

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
            'password' => bcrypt($validatedData['password']),
            'level' => 0,
            'experience' => 0,
        ]);

        /* Crear el inventario del usuario */
        Inventory::create([
            'user_id' => $user->_id,
        ]);

        /* Asignar zona de inicio aleatoria para el jugador creando una acción de mover */
        $action_type_id = ActionType::where('name' , 'Mover')->get()->value('id');
        $zones = Zone::all();
        $zone = $zones->random();
        Action::create([
            'user_id' => $user->_id,
            'action_type_id' => $action_type_id,
            'actionable_id' => $zone->_id,
            'actionable_type' => Zone::class,
            'time' => now(),
            'finished' => false,
            'notificacion' => true,
        ]);
        
        /* Redirige a la página de inicio */
        return redirect()->route('index')->with('succes' , 'Te has registrado correctamente');
    }


    /**
     * Función para validar el correo electrónico y la contraseña
     */
    public function login(Request $request){

        //Lógica de validación de datos
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string', 
        ],[
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Introduce una dirección de correo electrónico válida',
            'password.required' => 'La contraseña es obligatoria',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator->errors());
        }

        //Intento de inicio de sesión
        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials)){
            //Si la autenticación es correcta, se redirige a la página de inicio
            return redirect()->route('index')->with('success', 'Has iniciado sessión correctamente');
        }

        //Sino, se redirige al inicio de sesión con mensaje de error
        return redirect()->back()->withErrors(['email' => 'Credenciales incorrectas'])->withInput();
    }

    /**
     * Función para cerrar la sesión del usuario y volver a home
     */
    public function logout(Request $request){
        Auth::logout();
        return redirect()->route('index')->with('success', 'Has cerrado sessión correctamente');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
