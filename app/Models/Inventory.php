<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'inventories';

    protected $fillable = [
        'user_id',
    ];


    /* RELACIONES */

    /* Inventory 1:1 User */
    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    /* Inventories N:M Materials */
    public function materials(){
        return $this->hasMany(InventoryMaterial::class , 'inventory_id');
    }

    /* Inventory 1:N Inventions */
    public function inventions(){
        return $this->hasMany(Invention::class , 'inventory_id');
    }
}
