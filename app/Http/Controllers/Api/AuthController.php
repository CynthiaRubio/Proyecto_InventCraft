<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Contracts\UserServiceInterface;
use App\Models\Zone;

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
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    /**
     * Registra un nuevo usuario en el sistema.
     * 
     * @param Request $request Solicitud con los datos del usuario
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el usuario creado o errores de validación
     */
    public function register(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:4|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $zones = Zone::all();
        $initialZone = $zones->random();
        
        $user = $this->userService->registerUser($validator->validated(), $initialZone);

        return response()->json([
            'message' => 'Usuario registrado con éxito!',
            'user' => $user,
        ], 201);
    }

    /**
     * Obtiene un token JWT mediante las credenciales proporcionadas.
     * 
     * @param Request $request Solicitud con email y contraseña
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el token y el usuario o error de autenticación
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        //return response$this->respondWithToken($token);
        return response()->json([
            'token' => $token,
            'user' => auth('api')->user()
        ]);
    }

    /**
     * Obtiene el usuario autenticado.
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el usuario autenticado
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Cierra la sesión del usuario (invalida el token).
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con mensaje de confirmación
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    /**
     * Renueva el token de autenticación.
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el nuevo token
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Obtiene la estructura del array del token.
     * 
     * @param string $token Token JWT
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el token y su información
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
