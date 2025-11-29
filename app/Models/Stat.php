<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Stat
 * 
 * Representa una estadística del juego (Suerte, Ingenio, Vitalidad, Velocidad).
 * Las estadísticas pueden ser asignadas a usuarios y otorgadas por edificios.
 * 
 * @property int $id
 * @property string $name Nombre de la estadística
 * @property string|null $description Descripción de la estadística
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Stat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    /* RELACIONES */

    /**
     * Obtiene las estadísticas de usuarios (a través del modelo pivote)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userStats()
    {
        return $this->hasMany(UserStat::class , 'stat_id');
    }

    /**
     * Obtiene las estadísticas de edificios (a través del modelo pivote)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function buildingStats()
    {
        return $this->hasMany(BuildingStat::class ,'stat_id');
    }

    /**
     * Obtiene los usuarios que tienen esta estadística
     * Relación N:M directa usando la tabla pivote user_stats
     * Incluye el valor de la estadística en el pivot
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_stats')
                    ->withPivot(['value'])
                    ->withTimestamps();
    }

    /**
     * Obtiene los edificios que otorgan esta estadística
     * Relación N:M directa usando la tabla pivote building_stats
     * Incluye el valor de la bonificación en el pivot
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function buildings()
    {
        return $this->belongsToMany(Building::class, 'building_stats')
                    ->withPivot(['value'])
                    ->withTimestamps();
    }

}
