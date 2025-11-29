<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

use App\Contracts\ActionServiceInterface;
use App\Contracts\UserServiceInterface;
use App\Contracts\BuildingServiceInterface;
use App\Contracts\ResourceServiceInterface;
use App\Contracts\InventionServiceInterface;

/**
 * Middleware que verifica y procesa el estado de las acciones del usuario.
 * 
 * Este middleware se ejecuta en cada petición y realiza las siguientes tareas:
 * - Verifica si hay acciones finalizadas pendientes de procesar
 * - Calcula y añade la experiencia ganada por acciones completadas
 * - Procesa la finalización de acciones según su tipo (Mover, Construir, Crear, Recolectar)
 * - Verifica si el jugador ha ganado (construcción de Estación Espacial) y redirige a la vista de victoria
 * - Previene que el usuario inicie nuevas acciones mientras tiene una en curso
 * - Comparte el tiempo restante de la acción actual con las vistas
 */
class CheckActionStatus
{
    /**
     * Constructor del middleware.
     * 
     * @param ActionServiceInterface $actionService Servicio de acciones
     * @param UserServiceInterface $userService Servicio de usuarios
     * @param BuildingServiceInterface $buildingService Servicio de edificios
     * @param ResourceServiceInterface $resourceService Servicio de recursos
     * @param InventionServiceInterface $inventionService Servicio de inventos
     */
    public function __construct(
        private ActionServiceInterface $actionService,
        private UserServiceInterface $userService,
        private BuildingServiceInterface $buildingService,
        private ResourceServiceInterface $resourceService,
        private InventionServiceInterface $inventionService,
    ) {
    }

    /**
     * Maneja una petición entrante.
     * 
     * Procesa las acciones finalizadas, calcula experiencia, verifica victoria
     * y previene acciones simultáneas.
     * 
     * @param Request $request Petición HTTP entrante
     * @param Closure $next Siguiente middleware en la cadena
     * @return Response Respuesta HTTP
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return $next($request);
        }

        $finishedAction = $this->actionService->getFinishedPendingAction($user->id);

        if ($finishedAction !== null) {
            $experienceGained = $this->actionService->calculateExperienceGained($finishedAction);
            $this->userService->addExperience($user->id, $experienceGained);

            $actionType = $this->actionService->getActionTypeById($finishedAction->action_type_id);

            switch ($actionType->name) {
                case 'Mover':
                    Session::flash('success', "$user->name, has llegado a tu destino ¡Aumenta tu velocidad y serás más rápido en tus próximos trayectos!");
                    break;

                case 'Construir':
                    $result = $this->buildingService->finishConstructionAction($finishedAction, $user->name);
                    
                    if (isset($result['victory']) && $result['victory']) {
                        Session::put('victory_redirect', true);
                        Session::flash('victory_message', $result['message']);
                    }
                    break;

                case 'Crear':
                    $this->inventionService->finishCreationAction($finishedAction, $user->name);
                    break;

                case 'Recolectar':
                    $this->resourceService->finishFarmAction($finishedAction, $user->name);
                    break;
            }

            $this->actionService->finishAction($finishedAction);
        }

        if (Session::has('victory_redirect')) {
            Session::forget('victory_redirect');
            return redirect()->route('buildings.victory');
        }

        $currentAction = $this->actionService->getCurrentAction($user->id);

        if ($currentAction) {
            $timeLeft = max(0, (int) ($currentAction->time->timestamp - now()->timestamp));
            
            if ($request->is('moveZone') || $request->is('buildings/create/*') || $request->is('inventions/create/*') || $request->is('farm')) {
                return redirect()->back()->with('error', '⚠️ No puedes hacer otra acción hasta que termine la actual.');
            }
            
            view()->share([
                'time_left' => $timeLeft,
            ]);
        } else {
            view()->share([
                'time_left' => 0,
            ]);
        }

        return $next($request);
    }
}
