<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo ActionBuilding
 * 
 * Modelo pivote que representa una acción de construcción/mejora de un edificio.
 * Almacena información sobre la eficiencia del edificio en ese nivel y si está disponible.
 * 
 * @property int $id
 * @property int $action_id ID de la acción de construcción
 * @property int $building_id ID del edificio construido/mejorado
 * @property float $efficiency Eficiencia del edificio en este nivel (0-100)
 * @property bool $available Indica si el edificio está disponible para usar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class ActionBuilding extends Model
{
    use HasFactory;

    protected $fillable = [
        'action_id',
        'building_id',
        'efficiency',
        'available',
    ];

    /* RELACIONES */

    /**
     * Obtiene la acción de construcción asociada
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function action()
    {
        return $this->belongsTo(Action::class, 'action_id');
    }

    /**
     * Obtiene el edificio construido/mejorado
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

}
