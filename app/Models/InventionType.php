<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo InventionType
 * 
 * Representa un tipo de invento que puede ser creado en el juego.
 * Define los requisitos y características necesarias para crear un invento de este tipo.
 * 
 * @property int $id
 * @property int $material_type_id ID del tipo de material necesario para crear este invento
 * @property int $zone_id ID de la zona donde se puede crear este invento
 * @property int $building_id ID del edificio necesario para crear este invento
 * @property string $name Nombre del tipo de invento
 * @property string $description Descripción del tipo de invento
 * @property int $level_required Nivel mínimo del jugador requerido para crear este invento
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class InventionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_type_id',
        'zone_id',
        'building_id',
        'name',
        'description',
        'level_required',
    ];

    /* RELACIONES */

    /**
     * Obtiene el tipo de material necesario para crear este tipo de invento
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function materialType(){
        return $this->belongsTo(MaterialType::class, 'material_type_id');
    }

    /**
     * Obtiene la zona donde se puede crear este tipo de invento
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function zone(){
        return $this->belongsTo(Zone::class , 'zone_id');
    }

    /**
     * Obtiene el edificio necesario para crear este tipo de invento
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function building(){
        return $this->belongsTo(Building::class , 'building_id');
    }

    /**
     * Obtiene todos los inventos creados de este tipo
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventions(){
        return $this->hasMany(Invention::class , 'invention_type_id');
    }

    /* RELACIÓN REFLEXIVA */

    /**
     * Obtiene los tipos de inventos que se necesitan para crear este tipo de invento
     * 
     * Relación N:M: Un tipo de invento puede necesitar VARIOS tipos de inventos para crearse.
     * Devuelve la colección de InventionTypeInventionType que contiene los tipos requeridos y sus cantidades.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventionTypes(){
        return $this->hasMany(InventionTypeInventionType::class , 'invention_type_id');
    }

    /**
     * Obtiene los tipos de inventos que se pueden crear usando este tipo de invento
     * 
     * Relación N:M: Un tipo de invento puede ser usado para crear VARIOS tipos de inventos.
     * Devuelve la colección de InventionTypeInventionType donde este tipo es requerido.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventionTypesNeed(){
        return $this->hasMany(InventionTypeInventionType::class , 'invention_type_need_id');
    }

}
