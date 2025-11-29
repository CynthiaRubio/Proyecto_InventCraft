<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contracts\UserServiceInterface;
use App\Contracts\ActionServiceInterface;
use App\Contracts\ZoneServiceInterface;



class UserController extends Controller
{
    /**
     * Constructor del controlador.
     * 
     * @param UserServiceInterface $userService Servicio de usuarios
     * @param ActionServiceInterface $actionService Servicio de acciones
     * @param ZoneServiceInterface $zoneService Servicio de zonas
     */
    public function __construct(
        private UserServiceInterface $userService,
        private ActionServiceInterface $actionService,
        private ZoneServiceInterface $zoneService,
    ) {
    }

    /**
     * Devuelve todos los usuarios en formato JSON.
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con todos los usuarios
     */
    public function index()
    {
        $users = $this->userService->getAllUsers();
        return response()->json(['users' => $users], 200);
    }

    /**
     * Muestra el perfil de usuario autenticado.
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el usuario y su zona actual
     */
    public function show()
    {
        $user = Auth::user()->load('stats.stat');
        $zone_id = $this->actionService->getLastActionableByType('Mover');
        $zone = $zone_id ? $this->zoneService->getZone($zone_id) : null;
    
        return response()->json([
            'user' => $user,
            'current_zone' => $zone,
        ], 200);
    }

    /**
     * Muestra el ranking de usuarios ordenado por nivel y experiencia.
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el ranking de usuarios
     */
    public function ranking()
    {
        $users = $this->userService->getRanking();
        return response()->json(['ranking' => $users], 200);
    }

    /**
     * Devuelve los puntos sin asignar del usuario autenticado.
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los puntos sin asignar
     */
    public function points()
    {
        $user = Auth::user();
        return response()->json(['unasigned_points' => $user->unasigned_points], 200);
    }

    /**
     * Permite asignar puntos a las estadísticas del usuario autenticado.
     * 
     * @param Request $request Solicitud con los puntos a asignar
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con confirmación de asignación
     */
    public function addStats(Request $request)
    {
        $request->validate([
            'stats' => 'required|array',
        ]);

        $user = Auth::user();
        $totalAssigned = $this->userService->updateUserStats($user->id, $request->input('stats'));

        return response()->json([
            'message' => 'Puntos asignados correctamente',
            'total_assigned' => $totalAssigned,
            'user' => $user->fresh(),
        ], 200);
    }

    /**
     * Actualiza el avatar del usuario autenticado.
     * 
     * @param Request $request Solicitud con el ID del avatar seleccionado
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con confirmación de actualización
     */
    public function changeAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|integer|min:1|max:6',
        ]);

        $user = Auth::user();
        $user->avatar = $request->avatar;
        $user->save();

        return response()->json(['message' => 'Avatar actualizado correctamente', 'avatar' => $user->avatar], 200);
    }
}
