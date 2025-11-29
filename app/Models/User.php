<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Modelo User
 * 
 * Representa un usuario/jugador del juego.
 * Almacena información del perfil, nivel, experiencia y puntos sin asignar.
 * 
 * @property int $id
 * @property string $name Nombre del usuario
 * @property string $email Email del usuario (usado para autenticación)
 * @property string $password Contraseña hasheada
 * @property int $level Nivel actual del jugador
 * @property int $experience Experiencia acumulada del jugador
 * @property int $unasigned_points Puntos de estadística sin asignar
 * @property string|null $avatar Nombre del avatar seleccionado
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'level',
        'experience',
        'unasigned_points',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        //Esta linea Guillermo la quita en su ejemplo ya veremos para que sirve
        //'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    /* RELACIONES */

    /**
     * Obtiene las estadísticas del usuario (a través del modelo pivote)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userStats()
    {
        return $this->hasMany(UserStat::class , 'user_id');
    }

    /**
     * Obtiene las estadísticas del usuario
     * Relación N:M directa usando la tabla pivote user_stats
     * Incluye el valor de cada estadística en el pivot
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stats()
    {
        return $this->belongsToMany(Stat::class, 'user_stats')
                    ->withPivot(['value'])
                    ->withTimestamps();
    }

    /**
     * Obtiene el inventario del usuario
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function inventory(){
        return $this->hasOne(Inventory::class , 'user_id');
    }

    /**
     * Obtiene los inventos creados por el usuario a través de su inventario
     * Relación hasManyThrough: User -> Inventory -> Invention
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function inventions()
    {
        return $this->hasManyThrough(
            Invention::class,
            Inventory::class,
            'user_id',      // Foreign key en Inventory
            'inventory_id'  // Foreign key en Invention
        );
    }

    /**
     * Obtiene todas las acciones realizadas por el usuario
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actions(){
        return $this->hasMany(Action::class , 'user_id');
    }


}
