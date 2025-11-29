<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo BuildingStat
 * 
 * Modelo pivote que representa una estadística otorgada por un edificio.
 * Almacena el valor de la bonificación de estadística que otorga el edificio.
 * 
 * @property int $id
 * @property int $building_id ID del edificio
 * @property int $stat_id ID de la estadística
 * @property int $value Valor de la bonificación de estadística otorgada por el edificio
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class BuildingStat extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'building_id',
        'stat_id',
        'value',
    ];

    /* RELACIONES */

    /**
     * Obtiene el edificio que otorga esta estadística
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function building(){
        return $this->belongsTo(Building::class , 'building_id');
    }

    /**
     * Obtiene la estadística otorgada
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stat(){
        return $this->belongsTo(Stat::class , 'stat_id');
    }

}
