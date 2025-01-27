<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;


class ActionBuilding extends Model
{
    use HasFactory;

    protected $collection = 'action_buildings';
    protected $fillable = [
        'action_id',
        'building_id',
        'efficiency',
    ];

    /* RELACIONES */

    /* Inventions N:1 ActionBuilding */
    public function inventions()
    {
        return $this->hasMany(Invention::class);
    }

    /* Action 1:N ActionBuilding */
    public function action(){
        return $this->belongsTo(Action::class, 'actionable'); //morphTo no lleva complementos
    }

    /* Building 1:N ActionBuilding */
    public function building(){
        return $this->belongsTo(Building::class);
    }

}
