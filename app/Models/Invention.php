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
    protected $table = 'inventions';

    protected $fillable = [
        'invention_type_id',
        'material_id',
        'inventory_id',
        'action_building_id',
        'invention_created_id',
        'name',
        'efficiency',
    ];

    /* RELACIONES */

    /* InventionType 1:N Inventions */
    public function inventionType(){
        return $this->belongsTo(InventionType::class);
    }

    /* Material 1:N Inventions */
    public function material(){
        return $this->belongsTo(Material::class , 'material_id');
    }

    /* Inventory 1:N Inventions */
    public function inventory(){
        return $this->belongsTo(Inventory::class);
    }

    /* ActionBuilding 1:N Inventions */
    public function actionBuilding(){
        return $this->belongsTo(ActionBuilding::class);
    }

    /* RELACIONES CON ENTIDADES POLIMORFICAS */

    /* Action (Polimórfica) 1:1 Inventions */
    public function action(){ //REVISAR SI ES ACTIONABLE
        return $this->morphOne(Action::class , 'actionable');
    }

    /* Resources (Polimórfica) N:1 Invention */
    public function resources(){   //¿Plural o singular?
        return $this->morphMany(Resource::class , 'resourceable');
    }

    /* RELACIÓN REFLEXIVA */

    /* Invention 1:N Inventions */
    public function inventionUsed(){
        return $this->belongsTo(Invention::class, 'invention_id');
    }

    /* Inventions N:1 Invention */
    public function inventionCreated(){
        return $this->hasMany(Invention::class , 'invention_created_id');
    }

}
