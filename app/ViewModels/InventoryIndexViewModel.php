<?php

namespace App\ViewModels;

use App\Models\Inventory;
use Illuminate\Support\Collection;

class InventoryIndexViewModel extends BaseViewModel
{
    public function __construct(
        public Inventory $inventory,
        public Collection $inventionsByType,
        public Collection $materialsByType,
        public int $totalMaterials,
        public int $totalInventions,
    ) {}

    /**
     * Obtiene el nombre del usuario desde el inventario
     * 
     * @return string Nombre del usuario o 'Usuario' si no está disponible
     */
    public function userName(): string
    {
        return $this->inventory->user->name ?? 'Usuario';
    }

    /**
     * Verifica si el usuario tiene inventos
     * 
     * @return bool True si tiene inventos, false en caso contrario
     */
    public function hasInventions(): bool
    {
        return $this->totalInventions > 0;
    }

    /**
     * Verifica si el usuario tiene materiales
     * 
     * @return bool True si tiene materiales, false en caso contrario
     */
    public function hasMaterials(): bool
    {
        return $this->totalMaterials > 0;
    }

    /**
     * Verifica si hay inventos agrupados por tipo
     * 
     * @return bool True si hay inventos agrupados por tipo, false en caso contrario
     */
    public function hasInventionsByType(): bool
    {
        return $this->inventionsByType->isNotEmpty();
    }

    /**
     * Verifica si hay materiales agrupados por tipo
     * 
     * @return bool True si hay materiales agrupados por tipo, false en caso contrario
     */
    public function hasMaterialsByType(): bool
    {
        return $this->materialsByType->isNotEmpty();
    }
}

