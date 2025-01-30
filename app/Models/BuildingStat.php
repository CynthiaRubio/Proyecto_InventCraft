<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;


class BuildingStat extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'building_stats';
    
    protected $fillable = [
        'building_id',
        'stat_id',
        'value',
    ];

    /* RELACIONES */

    /* Buildings N:M Stats con la pivote BuildingStat */
    public function building(){
        return $this->belongsTo(Building::class , 'building_id');
    }

    /* Stat 1:N BuildingStat N:1 Building */
    public function stat(){
        return $this->belongsTo(Stat::class , 'stat_id');
    }

}
