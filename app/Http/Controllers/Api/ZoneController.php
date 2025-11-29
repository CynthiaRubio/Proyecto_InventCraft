<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Zone;
use App\Contracts\ActionServiceInterface;
use App\Contracts\ZoneServiceInterface;
use App\Contracts\FreeSoundServiceInterface;

class ZoneController extends Controller
{
    /**
     * Constructor del controlador.
     * 
     * @param ActionServiceInterface $actionService Servicio de acciones
     * @param ZoneServiceInterface $zoneService Servicio de zonas
     * @param FreeSoundServiceInterface $freeService Servicio de sonidos
     */
    public function __construct(
        private ActionServiceInterface $actionService,
        private ZoneServiceInterface $zoneService,
        private FreeSoundServiceInterface $freeService,
    ) {
    }

    /**
     * Devuelve todas las zonas en formato JSON.
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con todas las zonas y la zona actual del usuario
     */
    public function index()
    {
        $zones = Zone::all();
        $zone_id_user = $this->actionService->getLastActionableByType('Mover');
        $zone_user = $zone_id_user ? Zone::find($zone_id_user) : null;

        return response()->json([
            'zones' => $zones,
            'current_zone' => $zone_user,
        ], 200);
    }

    /**
     * Devuelve una zona específica con sus materiales e inventos en formato JSON.
     * 
     * @param string $id ID de la zona
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con la zona, URL del sonido y tiempo de movimiento
     */
    public function show(string $id)
    {
        $zone = Zone::with(['materials', 'inventionTypes'])->find($id);

        if (!$zone) {
            return response()->json(['error' => 'Zona no encontrada'], 404);
        }

        $sound_url = $this->freeService->getSound($zone);
        $moveTime = $this->zoneService->calculateMoveTime($id);

        return response()->json([
            'zone' => $zone,
            'sound_url' => $sound_url,
            'move_time' => $moveTime,
        ], 200);
    }
}

