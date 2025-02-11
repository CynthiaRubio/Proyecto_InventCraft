<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;


class ActionZone extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'action_zones';

    protected $fillable = [
        'action_id',
        'zone_id',
    ];

    /* RELACIONES */

    /* Resources N:1 ActionZone */
    public function resources (){
        return $this->hasMany(Resource::class , 'action_zone_id');
    }

    /* Action N: 1 ActionZone */
    public function action()
    {
        return $this->belongsTo(Action::class, 'action_id');
    }

    /* Zone N:1 ActionZone */
    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }
}
