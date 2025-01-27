<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone_id',
        'name',
        'description'
    ];

    /* RELACIONES */

    /* Zone 1:N Events */
    public function zone(){
        return $this->belongsTo(Zone::class);
    }

}
