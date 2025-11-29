<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserStatsRequest;
use App\Http\Requests\ChangeAvatarRequest;
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
        $user = Auth::user()->load('userStats.stat');
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
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los puntos sin asignar y estadísticas
     */
    public function points()
    {
        $user = Auth::user()->load('userStats.stat');
        return response()->json([
            'unasigned_points' => $user->unasigned_points,
            'user_stats' => $user->userStats,
        ], 200);
    }

    /**
     * Permite asignar puntos a las estadísticas del usuario autenticado.
     * 
     * @param UpdateUserStatsRequest $request Solicitud validada con los puntos a asignar
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con confirmación de asignación
     */
    public function addStats(UpdateUserStatsRequest $request)
    {
        $user = Auth::user();
        
        // En API, el user_id viene del usuario autenticado, pero validamos que coincida
        $userId = (int) $request->user_id;
        if ($userId !== $user->id) {
            return response()->json([
                'error' => 'No tienes permiso para asignar puntos a otro usuario.'
            ], 403);
        }

        $totalAssigned = $this->userService->updateUserStats($userId, $request->input('stats'));

        return response()->json([
            'message' => 'Puntos asignados correctamente',
            'total_assigned' => $totalAssigned,
            'user' => $user->fresh()->load('userStats.stat'),
        ], 200);
    }

    /**
     * Actualiza el avatar del usuario autenticado.
     * 
     * @param ChangeAvatarRequest $request Solicitud validada con el ID del avatar seleccionado
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con confirmación de actualización
     */
    public function changeAvatar(ChangeAvatarRequest $request)
    {
        $user = Auth::user();
        $user->avatar = $request->avatar;
        $user->save();

        return response()->json(['message' => 'Avatar actualizado correctamente', 'avatar' => $user->avatar], 200);
    }
}
