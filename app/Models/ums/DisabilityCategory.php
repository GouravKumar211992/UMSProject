<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;

class DisabilityCategory extends Model
{
    protected $table = 'disability_category';

    protected $fillable = [
        'disability_category', 
        'short_name', 
        'status', 
        'created_at', 
        'updated_at',
    ];
}
