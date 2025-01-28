<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
	use SoftDeletes;

   
	protected $fillable = [
        'name', 'start_time','end_time'
    ];
    protected $hidden = [
        'deleted_at',
    ];
}
