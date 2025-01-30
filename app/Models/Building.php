<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'buildings';

    protected $fillable = [
        'name',
        'description',
        'coord_x',
        'coord_y',
    ];

    /* RELACIONES */

    /* InventionTypes N:1 Building */
    public function inventionTypes(){
        return $this->hasMany(InventionType::class , 'building_id');
    }

    /* Stats N:M Buildings con tabla pivote */
    public function stats(){
        return $this->hasMany(BuildingStat::class , 'building_id');
    }

    /* Action (polimórfica) N:M Buildings con tabla pivote */
    public function actions(){
        return $this->hasMany(ActionBuilding::class , 'building_id');
    }
}
