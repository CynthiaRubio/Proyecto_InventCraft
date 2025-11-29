<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo MaterialType
 * 
 * Representa un tipo de material (ej: Madera, Piedra, Metal, etc.).
 * Agrupa materiales similares y define qué tipo de material se necesita para crear ciertos inventos.
 * 
 * @property int $id
 * @property string $name Nombre del tipo de material
 * @property string $description Descripción del tipo de material
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class MaterialType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /* RELACIONES */

    /**
     * Obtiene todos los materiales de este tipo
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function materials(){
        return $this->hasMany(Material::class , 'material_type_id');
    }

    /**
     * Obtiene los tipos de inventos que requieren este tipo de material
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventionTypes(){
        return $this->hasMany(InventionType::class , 'material_type_id');
    }
}
