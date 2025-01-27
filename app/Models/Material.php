<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_type_id',
        'zone_id',
        'name',
        'description',
        'efficiency',
    ];

    /* RELACIONES */

    /* Inventories N:M Materials */
    public function inventories(){
        return $this->hasMany(InventoryMaterial::class , 'inventory_id');
    }

    /* Zone 1:N Materials */
    public function zone(){
        return $this->belongsTo(Zone::class);
    }

    /* MaterialType 1:N Materials */
    public function material_type(){
        return $this->belongsTo(MaterialType::class);
    }

    /* Inventions N:1 Material */
    public function inventions(){
        return $this->hasMany(Invention::class);
    }

    /* Resource (polimórfica) N:1 Material */
    public function resources(){ //¿Plural o singular?
        return $this->morphMany(Resource::class , 'resourceable');
    }

}
