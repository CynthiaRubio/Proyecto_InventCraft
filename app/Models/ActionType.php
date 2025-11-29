<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo ActionType
 * 
 * Representa un tipo de acción que puede realizar un jugador.
 * Ejemplos: Explorar, Crear (invento), Construir (edificio).
 * 
 * @property int $id
 * @property string $name Nombre del tipo de acción
 * @property string $description Descripción del tipo de acción
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class ActionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /* RELACIONES */

    /**
     * Obtiene todas las acciones de este tipo
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actions(){
        return $this->hasMany(Action::class , 'action_type_id');
    }
}
