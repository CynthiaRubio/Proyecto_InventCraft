<?php

namespace App\ViewModels;

use App\Models\Zone;
use Illuminate\Support\Collection;

class ZoneIndexViewModel extends BaseViewModel
{
    public function __construct(
        public Collection $zones,
        public ?Zone $currentZone = null,
    ) {}

    /**
     * Verifica si el usuario tiene una zona actual
     * 
     * @return bool True si tiene una zona actual, false en caso contrario
     */
    public function hasCurrentZone(): bool
    {
        return $this->currentZone !== null;
    }

    /**
     * Obtiene el nombre de la zona actual
     * 
     * @return string|null Nombre de la zona actual o null si no tiene zona
     */
    public function currentZoneName(): ?string
    {
        return $this->currentZone?->name;
    }

    /**
     * Obtiene el ID de la zona actual
     * 
     * @return string|null ID de la zona actual o null si no tiene zona
     */
    public function currentZoneId(): ?string
    {
        return $this->currentZone?->id;
    }

    /**
     * Verifica si hay zonas disponibles
     * 
     * @return bool True si hay zonas, false en caso contrario
     */
    public function hasZones(): bool
    {
        return $this->zones->isNotEmpty();
    }
}

