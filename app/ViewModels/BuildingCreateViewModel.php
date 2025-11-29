<?php

namespace App\ViewModels;

use App\Models\Building;
use Illuminate\Support\Collection;

class BuildingCreateViewModel extends BaseViewModel
{
    public function __construct(
        public Building $building,
        public int $buildingNextLevel,
        public Collection $userInventionsByType,
        public Collection $inventionTypesNeeded,
        public ?array $canBuildSpaceStation = null,
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
     * Verifica si el usuario tiene inventos de un tipo específico
     * 
     * @param int $typeId ID del tipo de invento
     * @return bool True si tiene inventos de ese tipo, false en caso contrario
     */
    public function hasInventionsForType(int $typeId): bool
    {
        return isset($this->userInventionsByType[$typeId]) && 
               $this->userInventionsByType[$typeId]->isNotEmpty();
    }

    /**
     * Obtiene los inventos de un tipo específico
     * 
     * @param int $typeId ID del tipo de invento
     * @return Collection Colección de inventos del tipo especificado
     */
    public function getInventionsForType(int $typeId): Collection
    {
        return $this->userInventionsByType[$typeId] ?? collect();
    }
}

