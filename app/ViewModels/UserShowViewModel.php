<?php

namespace App\ViewModels;

use App\Models\User;
use App\Models\Zone;

class UserShowViewModel extends BaseViewModel
{
    public function __construct(
        public User $user,
        public ?Zone $zone = null,
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
     * Obtiene el email del usuario
     * 
     * @return string Email del usuario
     */
    public function userEmail(): string
    {
        return $this->user->email;
    }

    /**
     * Obtiene la ruta del avatar del usuario
     * 
     * @return string Ruta del avatar del usuario
     */
    public function avatarPath(): string
    {
        return asset('images/avatars/' . $this->user->avatar . '.webp');
    }

    /**
     * Obtiene el nivel del usuario
     * 
     * @return int Nivel del usuario
     */
    public function level(): int
    {
        return $this->user->level;
    }

    /**
     * Obtiene la experiencia del usuario
     * 
     * @return int Experiencia del usuario
     */
    public function experience(): int
    {
        return $this->user->experience;
    }

    /**
     * Obtiene los puntos sin asignar del usuario
     * 
     * @return int Puntos sin asignar
     */
    public function unassignedPoints(): int
    {
        return $this->user->unasigned_points;
    }

    /**
     * Verifica si el usuario tiene puntos sin asignar
     * 
     * @return bool True si tiene puntos sin asignar, false en caso contrario
     */
    public function hasUnassignedPoints(): bool
    {
        return $this->user->unasigned_points > 0;
    }

    /**
     * Calcula el progreso del nivel como porcentaje (0-100)
     * 
     * @return int Progreso del nivel
     */
    public function levelProgress(): int
    {
        return $this->user->level % 100;
    }

    /**
     * Calcula el progreso de la experiencia como porcentaje (0-100)
     * 
     * @return int Progreso de la experiencia
     */
    public function experienceProgress(): int
    {
        return $this->user->experience % 100;
    }

    /**
     * Verifica si el usuario tiene estadísticas
     * 
     * @return bool True si tiene estadísticas, false en caso contrario
     */
    public function hasStats(): bool
    {
        return $this->user->userStats->isNotEmpty();
    }

    /**
     * Obtiene las estadísticas del usuario
     * 
     * @return \Illuminate\Database\Eloquent\Collection Colección de estadísticas del usuario
     */
    public function stats()
    {
        return $this->user->userStats;
    }

    /**
     * Verifica si el usuario tiene una zona actual
     * 
     * @return bool True si tiene una zona actual, false en caso contrario
     */
    public function hasZone(): bool
    {
        return $this->zone !== null;
    }

    /**
     * Obtiene el nombre de la zona actual
     * 
     * @return string|null Nombre de la zona actual o null si no tiene zona
     */
    public function zoneName(): ?string
    {
        return $this->zone?->name;
    }

    /**
     * Obtiene el ID de la zona actual
     * 
     * @return string|null ID de la zona actual o null si no tiene zona
     */
    public function zoneId(): ?string
    {
        return $this->zone?->id;
    }
}

