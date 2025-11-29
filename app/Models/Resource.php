<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Resource
 * 
 * Representa un recurso obtenido durante una acción de exploración.
 * Puede ser un Material o un Invention encontrado en una zona.
 * 
 * @property int $id
 * @property int $action_zone_id ID de la acción de exploración donde se obtuvo este recurso
 * @property int $resourceable_id ID de la entidad polimórfica (Material o Invention)
 * @property string $resourceable_type Tipo de la entidad polimórfica (Material o Invention)
 * @property int $quantity Cantidad del recurso obtenido
 * @property bool $available Indica si el recurso está disponible (se hace disponible cuando termina la acción)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'action_zone_id',
        'resourceable_id',
        'resourceable_type',
        'quantity',
        'available',
    ];

    /* RELACIONES */

    /**
     * Obtiene la acción de exploración donde se obtuvo este recurso
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function actionZone (){
        return $this->belongsTo(ActionZone::class , 'action_zone_id');
    }

    /**
     * Obtiene la entidad polimórfica asociada a este recurso
     * Puede ser Material o Invention
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function resourceable(){
        return $this->morphTo();
    }

}
