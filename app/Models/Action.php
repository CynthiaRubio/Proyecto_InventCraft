<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Action
 * 
 * Representa una acción realizada por un jugador en el juego.
 * Puede ser una acción de exploración (Zone), creación de invento (Invention) o construcción de edificio (Building).
 * 
 * @property int $id
 * @property int $user_id ID del usuario que realiza la acción
 * @property int $action_type_id ID del tipo de acción (Explorar, Crear, Construir)
 * @property int $actionable_id ID de la entidad polimórfica asociada
 * @property string $actionable_type Tipo de la entidad polimórfica (Zone, Invention, Building)
 * @property \Illuminate\Support\Carbon $time Timestamp de cuándo termina la acción
 * @property bool $finished Indica si la acción ha sido completada
 * @property bool $notification Indica si se ha notificado al usuario sobre la acción
 * @property bool $updated Indica si la acción ha sido actualizada
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Action extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action_type_id',                                   
        'actionable_id',
        'actionable_type',
        'time',
        'finished',
        'notification',
        'updated',
    ];

    protected $casts = [
        'time' => 'datetime',
        'finished' => 'boolean',
        'notification' => 'boolean',
        'updated' => 'boolean',
    ];

    /* RELACIONES */

    /**
     * Obtiene la entidad polimórfica asociada a esta acción
     * Puede ser Zone (exploración), Invention (creación) o Building (construcción)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function actionable()
    {
        return $this->morphTo();
    }

    /**
     * Obtiene las acciones de construcción asociadas a esta acción (a través del modelo pivote)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actionBuildings()
    {
        return $this->hasMany(ActionBuilding::class , 'action_id');
    }

    /**
     * Obtiene los edificios asociados a esta acción de construcción
     * Relación N:M directa usando la tabla pivote action_buildings
     * Incluye datos adicionales: efficiency y available
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function buildings()
    {
        return $this->belongsToMany(Building::class, 'action_buildings')
                    ->withPivot(['efficiency', 'available'])
                    ->withTimestamps();
    }

    /**
     * Obtiene el tipo de acción
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function actionType(){
        return $this->belongsTo(ActionType::class , 'action_type_id');
    }

    /**
     * Obtiene el usuario que realiza la acción
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class , 'user_id');
    }

}
