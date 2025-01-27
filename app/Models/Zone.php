<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'coord_x',
        'coord_y',
    ];

    /* RELACIONES */

    /* Events N:1 Zone*/
    public function events(){
        return $this->hasMany(Event::class);
    }

    /* Materials N:1 Zone*/
    public function materials(){
        return $this->hasMany(Material::class);
    }

    /* InventionTypes N:1 Zone*/
    public function invention_types(){
        return $this->hasMany(InventionType::class);
    }

    /* Actions (polimórfica) N:1 Zone*/
    public function actions(){ //¿actionable?
        return $this->morphMany(Action::class , 'actionable');
    }

}
