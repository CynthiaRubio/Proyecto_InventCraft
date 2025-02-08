<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;


class InventionType extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'invention_types';

    protected $fillable = [
        'material_type_id',
        'zone_id',
        'building_id',
        'name',
        'description',
        'level_required',
    ];

    /* RELACIONES */

    /*  MaterialType 1:N InventionTypes */
    public function materialType(){
        return $this->belongsTo(MaterialType::class, 'material_type_id');
    }

    /* Zone 1:N InventionTypes */
    public function zone(){
        return $this->belongsTo(Zone::class , 'zone_id');
    }

    /* Building 1:N InventionTypes */
    public function building(){
        return $this->belongsTo(Building::class , 'building_id');
    }

    /* Inventions N:1 InventionType */
    public function inventions(){
        return $this->hasMany(Invention::class , 'invention_type_id');
    }

    /* RELACIÓN REFLEXIVA */

    /* Así obtenemos aquellos inventos que se necesitan para crear este */
    public function inventionTypes(){
        return $this->hasMany(InventionTypeInventionType::class , 'invention_type_id');
    }

    /* Así obtenemos los inventos que se pueden formar a partir de este invento */
    public function inventionTypesNeed(){
        return $this->hasMany(InventionTypeInventionType::class , 'invention_type_need_id');
    }

}
