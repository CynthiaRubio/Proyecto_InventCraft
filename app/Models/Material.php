<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'materials';

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
        return $this->hasMany(InventoryMaterial::class , 'material_id');
    }

    /* Zone 1:N Materials */
    public function zone(){
        return $this->belongsTo(Zone::class , 'zone_id');
    }

    /* MaterialType 1:N Materials */
    public function materialType(){
        return $this->belongsTo(MaterialType::class , 'material_type_id');
    }

    /* Inventions N:1 Material */
    public function inventions(){
        return $this->hasMany(Invention::class , 'material_id');
    }

    /* Resource (polimórfica) N:1 Material */
    public function resources(){
        return $this->morphMany(Resource::class , 'resourceable');
    }

}
