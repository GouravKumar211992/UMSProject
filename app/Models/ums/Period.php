<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'time_rang',
        'status'
    ];
    protected $hidden = [
        'deleted_at',
    ];
}
