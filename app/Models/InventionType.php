<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;


class InventionType extends Model
{
    use HasFactory;

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
    public function material_type(){
        return $this->belongsTo(MaterialType::class);
    }

    /* Zone 1:N InventionTypes */
    public function zone(){
        return $this->belongsTo(Zone::class);
    }

    /* Building 1:N InventionTypes */
    public function building(){
        return $this->belongsTo(Building::class);
    }

    /* Inventions N:1 InventionType */
    public function inventions(){
        return $this->hasMany(Invention::class);
    }

    /* RELACIÓN REFLEXIVA */

    /* InventionType N:M InventionTypes */
    public function inventionTypes(){
        return $this->hasMany(InventionTypeInventionType::class , 'invention_type_id');
    }

    public function inventionTypesNeed(){
        return $this->hasMany(InventionTypeInventionType::class , 'invention_type_need_id');
    }

}
