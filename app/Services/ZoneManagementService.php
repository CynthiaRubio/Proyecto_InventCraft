<?php

namespace App\Services;

use App\Models\Zone;

class ZoneManagementService
{
    public function __construct(
        private UserManagementService $user_service,
    ) {
    }

    /**
     * Obtiene una zona por ID
     */
    public function getZone(string $zone_id)
    {
        $zone = Zone::findOrFail($zone_id);
        return $zone;
    }

    /**
     * Obtiene una zona con relaciones precargadas
     */
    public function getZoneWithRelations(string $zone_id)
    {
        return Zone::with(['materials', 'inventionTypes', 'events'])->findOrFail($zone_id);
    }

    /**
     * Calcula el tiempo de desplazamiento entre dos zonas
     */
    public function calculateMoveTime(string $zone_id)
    {
        $zone = $this->getZone($zone_id);
        $user_actual_zone = $this->user_service->getUserActualZone();

        return round($this->getMoveTime($user_actual_zone, $zone));
    }

    /**
     * Calcula el tiempo de movimiento entre dos zonas
     */
    public function getMoveTime($actualZone, $targetZone)
    {
        $user = auth()->user();
        $distancia_x = abs($targetZone->coord_x - $actualZone->coord_x);
        $distancia_y = abs($targetZone->coord_y - $actualZone->coord_y);
        $distancia = $distancia_x + $distancia_y;
        $velocidad_user = $this->user_service->getUserStat('Velocidad');
        $tiempo_base = 50;

        if ($velocidad_user === 0) {
            $velocidad_user = 1;
        }

        $tiempo = match(true) {
            $distancia === 0 => 0,
            $distancia === 1 => $tiempo_base - $velocidad_user,
            $distancia === 2 => $tiempo_base + ($tiempo_base / $velocidad_user),
            $distancia >= 3 => (2 * $tiempo_base) + ($tiempo_base / $velocidad_user),
            default => 0,
        };

        return max(0, $tiempo);
    }

    
}
