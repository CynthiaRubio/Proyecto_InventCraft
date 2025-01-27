<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;


class InventionTypeInventionType extends Model
{
    use HasFactory;

    protected $collection = 'invention_type_invention_types';

    protected $fillable = [
        'invention_type_id',
        'invention_type_need_id',
        'quantity',
    ];

    /* RELACIÓN REFLEXIVA */

    /* InventionType N:M InventionTypes */
    public function inventionType(){
        return $this->belongsTo(InventionType::class , 'invention_type_id');
    }

    
    public function inventionTypeNeed(){
        return $this->belongsTo(InventionType::class , 'invention_type_need_id');
    }

}
