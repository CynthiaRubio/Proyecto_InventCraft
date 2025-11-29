<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Event
 * 
 * Representa un evento que puede ocurrir durante una exploración en una zona.
 * Los eventos pueden causar pérdida de materiales recolectados.
 * 
 * @property int $id
 * @property int $zone_id ID de la zona donde puede ocurrir este evento
 * @property string $name Nombre del evento
 * @property string $description Descripción del evento
 * @property float $loss_percent Porcentaje de pérdida de materiales (0-100)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone_id',
        'name',
        'description',
        'loss_percent',
    ];

    /* RELACIONES */

    /**
     * Obtiene la zona donde puede ocurrir este evento
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function zone(){
        return $this->belongsTo(Zone::class , 'zone_id');
    }

}
