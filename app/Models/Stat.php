<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Stat extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'stats';

    protected $fillable = [
        'name'
    ];

    /* RELACIONES */

    /* Users N:M Stats */
    public function users(){
        return $this->hasMany(UserStat::class , 'stat_id'); //, 'users_stats', 'stat_id', 'user_id')->withPivot('value');
    }

    /* Buildings N:M Stats */
    public function buildings(){
        return $this->hasMany(BuildingStat::class ,'stat_id'); //, 'stats_buildings', 'stat_id', 'buiding_id')->withPivot('value');
    }

}
