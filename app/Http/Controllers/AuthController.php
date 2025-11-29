<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Zone;
use App\Contracts\UserServiceInterface;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    /**
     * Constructor del controlador.
     * 
     * @param UserServiceInterface $userService Servicio de usuarios
     */
    public function __construct(
        private UserServiceInterface $userService,
    ) {
    }

    /**
     * Valida los datos de registro y genera un nuevo usuario.
     * 
     * @param StoreUserRequest $request Solicitud validada con los datos del usuario
     * @return \Illuminate\Http\RedirectResponse Redirección a la página de perfil con mensaje de éxito
     */
    public function register(StoreUserRequest $request)
    {
        /* Asignar zona de inicio aleatoria para el jugador */
        $zones = Zone::all();
        $initialZone = $zones->random();

        /* Registrar el usuario completo: usuario, inventario, acción inicial y stats */
        $user = $this->userService->registerUser($request->validated(), $initialZone);

        /* Autenticar automáticamente al usuario después del registro */
        Auth::login($user);

        /* Redirige directamente al perfil del usuario confirmando el registro */
        return redirect()->route('users.show')->with('success', "$user->name, te has registrado correctamente");
    }


    /**
     * Valida las credenciales del usuario e inicia sesión.
     * 
     * @param LoginRequest $request Solicitud validada con email y contraseña
     * @return \Illuminate\Http\RedirectResponse Redirección a la página de perfil o de vuelta con errores
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        /* Si la autenticación es correcta */
        if (Auth::attempt($credentials)) {

            $user = auth()->user();
            return redirect()->route('users.show')->with('success', "$user->name, has iniciado sesión correctamente");
        }

        /* Si la autenticación no es correcta */
        return redirect()->back()->withErrors(['email' => 'Credenciales incorrectas'])->withInput();
    }

    /**
     * Cierra la sesión del usuario y redirige a la página de login.
     * 
     * @param Request $request Solicitud HTTP
     * @return \Illuminate\Http\RedirectResponse Redirección a la página de login con mensaje de éxito
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Has cerrado sessión correctamente. ¡No tardes en volver!');
    }

}
