<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Building
 * 
 * Representa un edificio que puede ser construido en el juego.
 * Los edificios otorgan bonificaciones de estadísticas y permiten crear ciertos tipos de inventos.
 * 
 * @property int $id
 * @property string $name Nombre del edificio
 * @property string $description Descripción del edificio
 * @property int $coord_x Coordenada X del edificio en el mapa
 * @property int $coord_y Coordenada Y del edificio en el mapa
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'coord_x',
        'coord_y',
    ];

    /* RELACIONES */

    /**
     * Obtiene los tipos de inventos que se pueden crear en este edificio
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventionTypes()
    {
        return $this->hasMany(InventionType::class , 'building_id');
    }

    /**
     * Obtiene las estadísticas que otorga este edificio (a través del modelo pivote)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function buildingStats()
    {
        return $this->hasMany(BuildingStat::class , 'building_id');
    }

    /**
     * Obtiene las acciones de construcción/mejora de este edificio (a través del modelo pivote)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actionBuildings()
    {
        return $this->hasMany(ActionBuilding::class , 'building_id');
    }

    /**
     * Relación N:M directa con Stats a través de building_stats.
     * Permite acceder a las estadísticas que otorga el edificio.
     */
    public function stats()
    {
        return $this->belongsToMany(Stat::class, 'building_stats')
                    ->withPivot(['value'])
                    ->withTimestamps();
    }

    /**
     * Relación N:M directa con Actions a través de action_buildings.
     * Permite acceder a las acciones de construcción/mejora del edificio.
     */
    public function actions()
    {
        return $this->belongsToMany(Action::class, 'action_buildings')
                    ->withPivot(['efficiency', 'available'])
                    ->withTimestamps();
    }
}
