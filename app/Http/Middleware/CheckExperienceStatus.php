<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Contracts\UserServiceInterface;

/**
 * Middleware que verifica y actualiza el nivel del usuario basado en su experiencia.
 * 
 * Este middleware se ejecuta en cada petición y verifica si el usuario tiene
 * suficiente experiencia para subir de nivel. Si es así, actualiza el nivel
 * y otorga puntos de estadísticas no asignados.
 */
class CheckExperienceStatus
{
    /**
     * Constructor del middleware.
     * 
     * @param UserServiceInterface $userService Servicio de usuarios
     */
    public function __construct(
        private UserServiceInterface $userService,
    ) {
    }

    /**
     * Maneja una petición entrante.
     * 
     * Verifica si el usuario tiene suficiente experiencia para subir de nivel
     * y actualiza su nivel si es necesario.
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

        $this->userService->checkAndUpdateLevel($user->id);

        return $next($request);
    }
}
