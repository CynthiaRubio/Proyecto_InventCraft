<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Invention
 * 
 * Representa un invento creado por un jugador en el juego.
 * Los inventos pueden ser usados para crear otros inventos o construir edificios.
 * 
 * @property int $id
 * @property int $invention_type_id ID del tipo de invento
 * @property int $material_id ID del material usado para crear este invento
 * @property int $inventory_id ID del inventario donde se almacena este invento
 * @property string $name Nombre único del invento
 * @property float $efficiency Eficiencia del invento (0-100)
 * @property bool $available Indica si el invento está disponible para usar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class Invention extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'invention_type_id',
        'material_id',
        'inventory_id',
        'name',
        'efficiency',
        'available',
    ];

    /* RELACIONES */

    /**
     * Obtiene el tipo de invento al que pertenece este invento
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventionType(){
        return $this->belongsTo(InventionType::class , 'invention_type_id');
    }

    /**
     * Obtiene el material usado para crear este invento
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function material(){
        return $this->belongsTo(Material::class , 'material_id');
    }

    /**
     * Obtiene el inventario donde se almacena este invento
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventory(){
        return $this->belongsTo(Inventory::class , 'inventory_id');
    }

    /* RELACIONES CON ENTIDADES POLIMORFICAS */

    /**
     * Obtiene la acción de creación asociada a este invento
     * Relación polimórfica 1:1
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function action(){
        return $this->morphOne(Action::class , 'actionable');
    }

    /**
     * Obtiene los recursos asociados a este invento
     * Relación polimórfica N:1
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function resources(){ 
        return $this->morphMany(Resource::class , 'resourceable');
    }

}
