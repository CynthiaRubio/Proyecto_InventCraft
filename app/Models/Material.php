<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Material
 * 
 * Representa un material que puede ser recolectado en el juego.
 * Los materiales se usan para crear inventos y se encuentran en zonas específicas.
 * 
 * @property int $id
 * @property int $material_type_id ID del tipo de material
 * @property int $zone_id ID de la zona donde se puede encontrar este material
 * @property string $name Nombre del material
 * @property string $description Descripción del material
 * @property float $efficiency Eficiencia del material (0-100), afecta la eficiencia del invento creado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_type_id',
        'zone_id',
        'name',
        'description',
        'efficiency',
    ];

    /* RELACIONES */

    /**
     * Obtiene los inventarios que contienen este material (a través del modelo pivote)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventories(){
        return $this->hasMany(InventoryMaterial::class , 'material_id');
    }

    /**
     * Obtiene la zona donde se puede encontrar este material
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function zone(){
        return $this->belongsTo(Zone::class , 'zone_id');
    }

    /**
     * Obtiene el tipo de material
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function materialType(){
        return $this->belongsTo(MaterialType::class , 'material_type_id');
    }

    /**
     * Obtiene los inventos creados con este material
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventions(){
        return $this->hasMany(Invention::class , 'material_id');
    }

    /**
     * Obtiene los recursos asociados a este material
     * Relación polimórfica N:1
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function resources(){
        return $this->morphMany(Resource::class , 'resourceable');
    }

}
