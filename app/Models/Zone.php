<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'zones';

    protected $fillable = [
        'name',
        'coord_x',
        'coord_y',
    ];

    /* RELACIONES */

    /* Events N:1 Zone*/
    public function events(){
        return $this->hasMany(Event::class , 'zone_id');
    }

    /* Materials N:1 Zone*/
    public function materials(){
        return $this->hasMany(Material::class , 'zone_id');
    }

    /* InventionTypes N:1 Zone*/
    public function inventionTypes(){
        return $this->hasMany(InventionType::class , 'zone_id');
    }

    /* Actions (polimórfica) N:1 Zone*/
    public function actions(){
        return $this->morphMany(Action::class , 'actionable');
    }

}
