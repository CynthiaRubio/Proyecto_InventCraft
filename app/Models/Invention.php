<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invention extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $connection = 'mongodb';
    protected $collection = 'inventions';

    protected $fillable = [
        'invention_type_id',
        'material_id',
        'inventory_id',
        'action_building_id',
        'invention_created_id',
        'name',
        'efficiency',
        'available',
    ];

    /* RELACIONES */

    /* InventionType 1:N Inventions */
    public function inventionType(){
        return $this->belongsTo(InventionType::class , 'invention_type_id');
    }

    /* Material 1:N Inventions */
    public function material(){
        return $this->belongsTo(Material::class , 'material_id');
    }

    /* Inventory 1:N Inventions */
    public function inventory(){
        return $this->belongsTo(Inventory::class , 'inventory_id');
    }

    /* ActionBuilding 1:N Inventions */
    public function actionBuilding(){
        return $this->belongsTo(ActionBuilding::class , 'action_building_id');
    }

    /* RELACIONES CON ENTIDADES POLIMORFICAS */

    /* Action (Polimórfica) 1:1 Inventions */
    public function action(){
        return $this->morphOne(Action::class , 'actionable');
    }

    /* Resources (Polimórfica) N:1 Invention */
    public function resources(){ 
        return $this->morphMany(Resource::class , 'resourceable');
    }

    /* RELACIÓN REFLEXIVA */

    /* Invention 1:N Inventions */
    public function inventionUsed(){
        return $this->belongsTo(Invention::class, 'invention_created_id');
    }

    /* Invention 1:N Inventions */
    public function inventionCreated(){
        return $this->hasMany(Invention::class , 'invention_id');
    }

}
