<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Eloquent\Model;

use MongoDB\Laravel\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

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


    /* RELACIONES */

    /* Stat N:M User */
    public function stats(){
        return $this->hasMany(UserStat::class , 'user_id');
    }

    /* Inventory 1:1 User */
    public function inventory(){
        return $this->hasOne(Inventory::class , 'user_id');
    }

    /* Action N:1 User */
    public function actions(){
        return $this->hasMany(Action::class , 'user_id');
    }


}
