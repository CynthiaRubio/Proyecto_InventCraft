<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Stat extends Model
{
    use HasFactory;

    protected $fillable = [
        'stat_type'
    ];

    /* RELACIONES */

    /* Users N:M Stats */
    public function users(){
        return $this->hasMany(UserStat::class); //, 'users_stats', 'stat_id', 'user_id')->withPivot('value');
    }

    /* Buildings N:M Stats */
    public function buildings(){
        return $this->hasMany(BuildingStat::class); //, 'stats_buildings', 'stat_id', 'buiding_id')->withPivot('value');
    }

}
