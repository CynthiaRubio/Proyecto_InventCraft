<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Inventory;
use App\Models\Zone;
use App\Models\ActionType;
use App\Models\Action;
use App\Models\UserStat;
use App\Models\Stat;
use App\Services\ActionManagementService;
use App\Services\ZoneManagementService;

class AuthController extends Controller
{
    public function __construct(
        private ActionManagementService $action_service,
        private ZoneManagementService $zone_service,
    ) {
    }

    /**
     * Función para validar los datos de registro y generar un nuevo usuario
     */
    public function register(Request $request)
    {

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
            /* TODO Cambiar a 0 si da tiempo a revisar los cálculos en los que interviene */
            'level' => 1,
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

        /* Se crea la primera acción de mover para situarlo en la zona aleatoria */
        $action_type_id = ActionType::where('name', 'Mover')->first()->id;
        $actionable_type = "App\Models\Zone";

        $action = Action::create([
            'user_id' => $user->_id,
            'action_type_id' => $action_type_id,
            'actionable_id' => $zone->_id,
            'actionable_type' => $actionable_type,
            'time' =>  now()->addSeconds(0),
            'finished' => true,
            'notification' => false,
            'updated' => true,
        ]);

        /* Creamos las estadísticas del usuario */
        $stats = Stat::all();
        foreach ($stats as $stat) {
            UserStat::create([
                'user_id' => $user->_id,
                'stat_id' => $stat->_id,
                'value' => 0,
            ]);
        }

        /* Redirige a la página de inicio confirmando el registro */
        return redirect()->route('login')->with('success', "$user->name Te has registrado correctamente");
    }


    /**
     * Función para validar el correo electrónico y la contraseña
     */
    public function login(Request $request)
    {

        //Lógica de validación de datos
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Introduce una dirección de correo electrónico válida',
            'password.required' => 'La contraseña es obligatoria',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
    
        $credentials = $request->only('email', 'password');

        /* Si la autenticación es correcta */
        if (Auth::attempt($credentials)) {

            $user = auth()->user();
            return redirect()->route('users.show')->with('success', "$user->name has iniciado sessión correctamente");
        }

        /* Si la autenticación no es correcta */
        return redirect()->back()->withErrors(['email' => 'Credenciales incorrectas'])->withInput();
    }

    /**
     * Función para cerrar la sesión del usuario y volver a home
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Has cerrado sessión correctamente');
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
