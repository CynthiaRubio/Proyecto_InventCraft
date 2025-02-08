<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Action extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'actions';
    
    protected $fillable = [
        'user_id',
        'action_type_id',                                   
        'actionable_id',
        'actionable_type',
        'time',
        'finished',
        'notification',
        'updated',
    ];


    /* RELACIONES */

    /* Relación para Zone e Invention, con las que Action se comporta de forma polimórfica */
    public function actionable()
    {
        return $this->morphTo();
    }

    /* Relación N:M con Building a través de la tabla pivote ActionBuilding */
    public function buildings(){
        return $this->hasOne(ActionBuilding::class , 'action_id');
    }

    /* ActionType 1:N Actions */
    public function actionType(){
        return $this->belongsTo(ActionType::class , 'action_type_id');
    }

    /* User 1:N Actions */
    public function user() {
        return $this->belongsTo(User::class , 'user_id');
    }

}
