<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ZoneServiceInterface;
use App\Contracts\UserServiceInterface;
use App\Models\Zone;

class ZoneService implements ZoneServiceInterface
{
    public function __construct(
        private UserServiceInterface $userService,
    ) {
    }

    /**
     * Obtiene una zona por ID
     * 
     * @param string $zone_id ID de la zona
     * @return Zone Zona encontrada
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si no se encuentra la zona
     */
    public function getZone(string $zone_id): Zone
    {
        $zone = Zone::findOrFail($zone_id);
        return $zone;
    }

    /**
     * Obtiene una zona con relaciones precargadas (materials, inventionTypes, events)
     * 
     * @param string $zone_id ID de la zona
     * @return Zone Zona con relaciones precargadas
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si no se encuentra la zona
     */
    public function getZoneWithRelations(string $zone_id): Zone
    {
        return Zone::with(['materials', 'inventionTypes', 'events'])->findOrFail($zone_id);
    }

    /**
     * Calcula el tiempo de desplazamiento desde la zona actual del usuario hasta la zona destino
     * 
     * @param string $zone_id ID de la zona destino
     * @return int Tiempo de desplazamiento en minutos (redondeado)
     */
    public function calculateMoveTime(string $zone_id): int
    {
        $zone = $this->getZone($zone_id);
        $user_actual_zone = $this->userService->getUserActualZone();
        
        // Si no hay zona actual, usar la zona destino como actual (distancia 0)
        if ($user_actual_zone === null) {
            $user_actual_zone = $zone;
        }

        return $this->getMoveTime($user_actual_zone, $zone);
    }

    /**
     * Calcula el tiempo de movimiento entre dos zonas basado en la distancia y velocidad del usuario
     * Fórmula varía según distancia: 0 si misma zona, 50-velocidad si distancia=1, etc.
     * 
     * @param Zone|null $actualZone Zona actual del usuario
     * @param Zone $targetZone Zona destino
     * @return int Tiempo de movimiento en minutos (mínimo 0)
     */
    public function getMoveTime(Zone $actualZone, Zone $targetZone): int
    {
        $user = auth()->user();
        $distancia_x = abs($targetZone->coord_x - $actualZone->coord_x);
        $distancia_y = abs($targetZone->coord_y - $actualZone->coord_y);
        $distancia = $distancia_x + $distancia_y;
        $velocidad_user = $this->userService->getUserStat('Velocidad');
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

        return (int) max(0, $tiempo);
    }

    
}
