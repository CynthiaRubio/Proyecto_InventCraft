<?php

namespace App\ViewModels;

use App\Models\User;
use Illuminate\Support\Collection;

class InventoryShowViewModel extends BaseViewModel
{
    public function __construct(
        public Collection $inventions,
        public User $user,
    ) {}

    /**
     * Obtiene el nombre del usuario
     * 
     * @return string Nombre del usuario
     */
    public function userName(): string
    {
        return $this->user->name;
    }

    /**
     * Verifica si hay inventos
     * 
     * @return bool True si hay inventos, false en caso contrario
     */
    public function hasInventions(): bool
    {
        return $this->inventions->isNotEmpty();
    }
}

