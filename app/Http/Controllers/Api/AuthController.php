<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Inventory;
use App\Models\ActionType;
use App\Models\Zone;
use App\Models\Action;
use App\Models\Stat;
use App\Models\UserStat;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    /**
     * 
     */
    public function register(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:4,confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($request->name) . '&size=256&background=random',
            'level' => 1,
            'experience' => 0,
            'unasigned_points' => 15,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //Crear el inventario del usuario
        Inventory::create([
            'user_id' => $user->_id,
        ]);
    
        //Asignar una zona de inicio aleatoria creando una acción "Mover"
        $action_type_id = ActionType::where('name', 'Mover')->first()->id;
        $zones = Zone::all();
        $zone = $zones->random(); // Seleccionar una zona aleatoria
        
        Action::create([
            'user_id' => $user->_id,
            'action_type_id' => $action_type_id,
            'actionable_id' => $zone->_id,
            'actionable_type' => Zone::class,
            'time' => now(),
            'finished' => true,
            'notificacion' => false,
        ]);
        
        //Crear estadísticas base para el usuario
        $stats = Stat::all();
        foreach ($stats as $stat) {
            UserStat::create([
                'user_id' => $user->_id,
                'stat_id' => $stat->_id,
                'value' => 0,
            ]);
        }

        return response()->json([
            'message' => 'Usuario registrado con exito!',
            'user' => $user,
        ]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
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
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
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
