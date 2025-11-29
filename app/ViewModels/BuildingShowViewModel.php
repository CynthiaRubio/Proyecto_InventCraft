<?php

namespace App\ViewModels;

use App\Models\Building;

class BuildingShowViewModel extends BaseViewModel
{
    public function __construct(
        public Building $building,
        public int $actualLevel,
        public float $efficiency,
        public ?array $canBuildSpaceStation = null,
        public bool $isUnderConstruction = false,
    ) {}

    /**
     * Obtiene el nombre del edificio
     * 
     * @return string Nombre del edificio
     */
    public function buildingName(): string
    {
        return $this->building->name;
    }

    /**
     * Obtiene la descripción del edificio
     * 
     * @return string Descripción del edificio
     */
    public function buildingDescription(): string
    {
        return $this->building->description ?? '';
    }

    /**
     * Obtiene las coordenadas del edificio como una cadena formateada
     * 
     * @return string Coordenadas en formato [x, y]
     */
    public function coordinates(): string
    {
        return "[{$this->building->coord_x}, {$this->building->coord_y}]";
    }

    /**
     * Obtiene la ruta de la imagen del edificio
     * 
     * @return string Ruta de la imagen del edificio
     */
    public function imagePath(): string
    {
        return asset('images/buildings/' . $this->building->name . '.webp');
    }

    /**
     * Verifica si este edificio es la Estación Espacial
     * 
     * @return bool True si es la Estación Espacial, false en caso contrario
     */
    public function isSpaceStation(): bool
    {
        return $this->building->name === 'Estación Espacial';
    }

    /**
     * Verifica si la Estación Espacial puede ser construida
     * 
     * @return bool True si puede construirse, false en caso contrario
     */
    public function canBuildSpaceStation(): bool
    {
        return $this->isSpaceStation() && 
               $this->canBuildSpaceStation !== null && 
               ($this->canBuildSpaceStation['can_build'] ?? false);
    }

    /**
     * Obtiene la razón del estado de construcción de la Estación Espacial
     * 
     * @return string|null Razón por la que no se puede construir o null si puede construirse
     */
    public function spaceStationReason(): ?string
    {
        return $this->canBuildSpaceStation['reason'] ?? null;
    }

    /**
     * Obtiene el estado de los edificios para la construcción de la Estación Espacial
     * 
     * @return array Array con el estado de cada edificio
     */
    public function spaceStationBuildingsStatus(): array
    {
        return $this->canBuildSpaceStation['buildings_status'] ?? [];
    }

    /**
     * Obtiene la eficiencia como una cadena de porcentaje
     * 
     * @return string Eficiencia formateada como porcentaje (ej: "85.50%")
     */
    public function efficiencyPercentage(): string
    {
        return number_format($this->efficiency, 2) . '%';
    }

    /**
     * Verifica si el edificio tiene tipos de inventos asociados
     * 
     * @return bool True si tiene tipos de inventos, false en caso contrario
     */
    public function hasInventionTypes(): bool
    {
        return $this->building->inventionTypes->isNotEmpty();
    }
}

