<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class MaterialType extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'material_types';

    protected $fillable = [
        'name',
        'description',
    ];

    /* RELACIONES */

    /* Materials N:1 MaterialType */
    public function materials(){
        return $this->hasMany(Material::class , 'material_type_id');
    }

    /* InventionTypes N:1 MaterialType */
    public function inventionTypes(){
        return $this->hasMany(InventionType::class , 'material_type_id');
    }
}
