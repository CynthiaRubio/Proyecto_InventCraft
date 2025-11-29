<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo ActionZone
 * 
 * Modelo pivote que representa una acción de exploración en una zona específica.
 * Almacena información sobre los recursos obtenidos y eventos ocurridos durante la exploración.
 * 
 * @property int $id
 * @property int $action_id ID de la acción de exploración
 * @property int $zone_id ID de la zona explorada
 * @property int|null $event_id ID del evento que ocurrió durante la exploración (si aplica)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class ActionZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'action_id',
        'zone_id',
        'event_id',
    ];

    /* RELACIONES */

    /**
     * Obtiene los recursos obtenidos durante esta exploración
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resources (){
        return $this->hasMany(Resource::class , 'action_zone_id');
    }

    /**
     * Obtiene la acción de exploración asociada
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function action()
    {
        return $this->belongsTo(Action::class, 'action_id');
    }

    /**
     * Obtiene la zona explorada
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    /**
     * Obtiene el evento que ocurrió durante la exploración (si aplica)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
