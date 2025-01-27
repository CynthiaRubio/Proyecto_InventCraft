<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];


    /* RELACIONES */

    /* Inventory 1:1 User */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /* Inventories N:M Materials */
    public function materials(){
        return $this->hasMany(InventoryMaterial::class);
    }

    /* Inventory 1:N Inventions */
    public function inventions(){
        return $this->hasMany(Invention::class);
    }
}
