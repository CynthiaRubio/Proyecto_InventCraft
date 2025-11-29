<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo InventoryMaterial
 * 
 * Modelo pivote que representa un material almacenado en un inventario.
 * Almacena la cantidad disponible y la cantidad no disponible (pendiente de acción).
 * 
 * @property int $id
 * @property int $inventory_id ID del inventario
 * @property int $material_id ID del material
 * @property int $quantity Cantidad disponible del material
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class InventoryMaterial extends Model
{
    use HasFactory;

    protected $primaryKey = ['inventory_id', 'material_id'];
    public $incrementing = false;

    protected $fillable = [
        'inventory_id',
        'material_id',
        'quantity',
    ];

    /* RELACIONES */

    /**
     * Obtiene el material almacenado
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function material(){
        return $this->belongsTo(Material::class ,'material_id');
    }

    /**
     * Obtiene el inventario donde se almacena este material
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventory(){
        return $this->belongsTo(Inventory::class , 'inventory_id');
    }
}
