<?php

namespace App\ViewModels;

use App\Models\Zone;

class ZoneShowViewModel extends BaseViewModel
{
    public function __construct(
        public Zone $zone,
        public int $moveTime,
        public ?string $soundUrl = null,
    ) {}

    /**
     * Obtiene el nombre de la zona
     * 
     * @return string Nombre de la zona
     */
    public function zoneName(): string
    {
        return $this->zone->name;
    }

    /**
     * Obtiene la descripción de la zona
     * 
     * @return string|null Descripción de la zona o null si no tiene descripción
     */
    public function zoneDescription(): ?string
    {
        return $this->zone->description ?? null;
    }

    /**
     * Obtiene las coordenadas de la zona como una cadena formateada
     * 
     * @return string Coordenadas en formato [x, y]
     */
    public function coordinates(): string
    {
        return "[{$this->zone->coord_x}, {$this->zone->coord_y}]";
    }

    /**
     * Verifica si la zona tiene materiales
     * 
     * @return bool True si tiene materiales, false en caso contrario
     */
    public function hasMaterials(): bool
    {
        return $this->zone->materials->isNotEmpty();
    }

    /**
     * Obtiene los materiales de la zona
     * 
     * @return \Illuminate\Database\Eloquent\Collection Colección de materiales de la zona
     */
    public function materials()
    {
        return $this->zone->materials;
    }

    /**
     * Verifica si la zona tiene tipos de inventos
     * 
     * @return bool True si tiene tipos de inventos, false en caso contrario
     */
    public function hasInventionTypes(): bool
    {
        return $this->zone->inventionTypes->isNotEmpty();
    }

    /**
     * Obtiene los tipos de inventos de la zona
     * 
     * @return \Illuminate\Database\Eloquent\Collection Colección de tipos de inventos de la zona
     */
    public function inventionTypes()
    {
        return $this->zone->inventionTypes;
    }

    /**
     * Verifica si hay una URL de sonido disponible
     * 
     * @return bool True si hay URL de sonido, false en caso contrario
     */
    public function hasSound(): bool
    {
        return $this->soundUrl !== null && $this->soundUrl !== '';
    }

    /**
     * Obtiene el tiempo de movimiento formateado
     * 
     * @return string Tiempo de movimiento formateado (ej: "5 minutos y 30 segundos")
     */
    public function formattedMoveTime(): string
    {
        if ($this->moveTime === 0) {
            return 'Inmediato';
        }
        
        if ($this->moveTime < 60) {
            return "{$this->moveTime} segundos";
        }
        
        $minutes = floor($this->moveTime / 60);
        $seconds = $this->moveTime % 60;
        
        if ($seconds === 0) {
            return "{$minutes} minuto" . ($minutes > 1 ? 's' : '');
        }
        
        return "{$minutes} minuto" . ($minutes > 1 ? 's' : '') . " y {$seconds} segundo" . ($seconds > 1 ? 's' : '');
    }
}

