<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class ActionBuilding extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'action_buildings';

    protected $fillable = [
        'action_id',
        'building_id',
        'efficiency',
        'available',
    ];

    /* RELACIONES */

    /* Inventions N:1 ActionBuilding */
    public function inventions()
    {
        return $this->hasMany(Invention::class , 'action_building_id');
    }

    /* Action (polimórfica) 1:N ActionBuilding */
    public function action()
    {
        return $this->morphTo();
    }

    /* Building 1:N ActionBuilding */
    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

}
