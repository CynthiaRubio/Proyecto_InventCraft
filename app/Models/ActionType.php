<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class ActionType extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'action_types';

    protected $fillable = [
        'name',
        'description',
    ];

    /* RELACIONES */

    /* Actions N:1 ActionType */
    public function actions(){
        return $this->hasMany(Action::class , 'action_type_id');
    }
}
