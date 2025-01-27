<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Eloquent\Model; //Esto no se si hace falta ponerlo: no hace falta usarlo porque no lo necesita ahora mismo

use MongoDB\Laravel\Auth\User as Authenticatable;

class User extends Authenticatable
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


    /* RELACIONES */

    /* Stat N:M User */
    public function stats(){
        return $this->hasMany(UserStat::class);
    }

    /* Inventory 1:1 User */
    public function inventory(){
        return $this->hasOne(Inventory::class);
    }

    /* Action N:1 User */
    public function actions(){
        return $this->hasMany(Action::class , 'user_id'); //¿O morphMany sin actionable?
    }


}
