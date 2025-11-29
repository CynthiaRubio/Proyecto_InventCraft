<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Inventory
 * 
 * Representa el inventario de un jugador.
 * Almacena materiales e inventos que el jugador ha recolectado o creado.
 * 
 * @property int $id
 * @property int $user_id ID del usuario propietario del inventario
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];


    /* RELACIONES */

    /**
     * Obtiene el usuario propietario del inventario
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    /**
     * Obtiene los materiales del inventario (a través del modelo pivote)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventoryMaterials()
    {
        return $this->hasMany(InventoryMaterial::class , 'inventory_id');
    }

    /**
     * Obtiene los materiales almacenados en este inventario
     * Relación N:M directa usando la tabla pivote inventory_materials
     * Incluye datos adicionales: quantity (disponible)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function materials()
    {
        return $this->belongsToMany(Material::class, 'inventory_materials')
                    ->withPivot(['quantity'])
                    ->withTimestamps();
    }

    /**
     * Obtiene los inventos almacenados en este inventario
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventions(){
        return $this->hasMany(Invention::class , 'inventory_id');
    }
}
