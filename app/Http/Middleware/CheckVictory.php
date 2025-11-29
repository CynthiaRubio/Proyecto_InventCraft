<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Contracts\BuildingServiceInterface;

/**
 * Middleware que verifica si el usuario ha ganado el juego antes de acceder a la pantalla de victoria.
 * 
 * Este middleware protege la ruta de victoria para que solo los usuarios que realmente han
 * construido la Estación Espacial puedan acceder a ella. Si el usuario no ha ganado,
 * será redirigido a la lista de edificios con un mensaje de error.
 */
class CheckVictory
{
    /**
     * Constructor del middleware.
     * 
     * @param BuildingServiceInterface $buildingService Servicio de edificios
     */
    public function __construct(
        private BuildingServiceInterface $buildingService,
    ) {
    }

    /**
     * Maneja una petición entrante.
     * 
     * Verifica si el usuario autenticado ha ganado el juego (ha construido la Estación Espacial).
     * Si no ha ganado, redirige a la lista de edificios con un mensaje de error.
     * 
     * @param Request $request Petición HTTP entrante
     * @param Closure $next Siguiente middleware en la cadena
     * @return Response Respuesta HTTP o redirección
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para acceder a esta página.');
        }

        // Verificar si el usuario ha ganado el juego
        if (!$this->buildingService->checkVictory()) {
            return redirect()->route('buildings.index')
                ->with('error', '⚠️ No puedes acceder a la pantalla de victoria. Debes construir la Estación Espacial y completar el juego primero.');
        }

        return $next($request);
    }
}

