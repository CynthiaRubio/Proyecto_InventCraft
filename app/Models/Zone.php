<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Zone
 * 
 * Representa una zona del mapa donde los jugadores pueden explorar.
 * Las zonas contienen materiales, tipos de inventos y pueden tener eventos.
 * 
 * @property int $id
 * @property string $name Nombre de la zona
 * @property int $coord_x Coordenada X de la zona en el mapa
 * @property int $coord_y Coordenada Y de la zona en el mapa
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Zone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'coord_x',
        'coord_y',
    ];

    /* RELACIONES */

    /**
     * Obtiene los eventos que pueden ocurrir en esta zona
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events(){
        return $this->hasMany(Event::class , 'zone_id');
    }

    /**
     * Obtiene los materiales que se pueden encontrar en esta zona
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function materials(){
        return $this->hasMany(Material::class , 'zone_id');
    }

    /**
     * Obtiene los tipos de inventos que se pueden crear en esta zona
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventionTypes(){
        return $this->hasMany(InventionType::class , 'zone_id');
    }

    /**
     * Obtiene las acciones de exploración realizadas en esta zona
     * Relación polimórfica N:1
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function actions(){
        return $this->morphMany(Action::class , 'actionable');
    }

}
