<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;


class UserStat extends Model
{
    use HasFactory;

    protected $collection = 'user_stats';

    protected $fillable = [
        'user_id',
        'stat_id',
        'value'
    ];

    /* RELACIONES */

    /* User 1:N UserStats */
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    /* Stat 1:N UserStats */
    public function stat(){
        return $this->belongsTo(Stat::class, 'stat_id');
    }

}
