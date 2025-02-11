<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'resources';

    protected $fillable = [
        'action_zone_id',
        'resourceable_id',
        'resourceable_type',
        'quantity',
        'available',
    ];

    /* RELACIONES */

    /* ActionZone 1:N Resources */
    public function actionZone (){
        return $this->belongsTo(ActionZone::class , 'action_zone_id');
    }

    /* Relación polimórfica con Material e Invention 1:N Resources */
    public function resourceable(){
        return $this->morphTo();
    }

}
