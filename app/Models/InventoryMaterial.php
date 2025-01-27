<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class InventoryMaterial extends Model
{
    use HasFactory;

    protected $collection = 'inventory_materials';

    protected $fillable = [
        'inventory_id',
        'material_id',
        'quantity'
    ];

    /* RELACIONES */

    /* Material N:1 InventoryMaterial */
    public function material(){
        return $this->belongsTo(Material::class);
    }

    /* Inventory N:1 InventoryMaterial */
    public function inventory(){
        return $this->belongsTo(Inventory::class);
    }
}
