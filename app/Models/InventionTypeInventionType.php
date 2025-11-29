<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo InventionTypeInventionType
 * 
 * Modelo pivote que representa la relación reflexiva entre tipos de inventos.
 * Define qué tipos de inventos y en qué cantidad se necesitan para crear otro tipo de invento.
 * 
 * @property int $id
 * @property int $invention_type_id ID del tipo de invento que se quiere crear
 * @property int $invention_type_need_id ID del tipo de invento que se necesita
 * @property int $quantity Cantidad de inventos del tipo necesario requeridos
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class InventionTypeInventionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'invention_type_id',
        'invention_type_need_id',
        'quantity',
    ];

    /* RELACIÓN REFLEXIVA InventionType N:M InventionTypes */

    /**
     * Obtiene el tipo de invento que se quiere crear
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventionType(){
        return $this->belongsTo(InventionType::class , 'invention_type_id');
    }

    /**
     * Obtiene el tipo de invento que se necesita para crear el invento principal
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventionTypeNeed(){
        return $this->belongsTo(InventionType::class , 'invention_type_need_id');
    }

}
