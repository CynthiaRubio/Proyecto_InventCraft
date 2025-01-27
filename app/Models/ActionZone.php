<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;


class ActionZone extends Model
{
    use HasFactory;

    protected $collection = 'action_zones';

    protected $fillable = [
        'action_id',
        'zone_id',
    ];

    /* RELACIONES */

    /* Resources N:1 ActionZone */
    public function resources (){ //¿Es correcta esta relación?
        return $this->morphMany(Resource::class , 'resourceable');
    }

    /* ¿ESTAS RELACIONES SON NECESARIAS? */
    public function action()
    {
        return $this->belongsTo(Action::class, 'action_id');
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }
}
