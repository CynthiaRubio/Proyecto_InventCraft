<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo UserStat
 * 
 * Modelo pivote que representa una estadística asignada a un usuario.
 * Almacena el valor de la estadística para ese usuario específico.
 * 
 * @property int $id
 * @property int $user_id ID del usuario
 * @property int $stat_id ID de la estadística
 * @property int $value Valor de la estadística para este usuario
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class UserStat extends Model
{
    use HasFactory;

    protected $primaryKey = ['user_id', 'stat_id'];
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'stat_id',
        'value'
    ];

    /* RELACIONES */

    /**
     * Obtiene el usuario propietario de esta estadística
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtiene la estadística asociada
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stat(){
        return $this->belongsTo(Stat::class, 'stat_id');
    }

}
