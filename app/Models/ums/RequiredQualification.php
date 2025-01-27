<?php

namespace App\models\ums;;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequiredQualification extends Model
{
    use SoftDeletes;
    protected $table = 'qualifications';
    protected $fillable = [
        'name',
    ];

    
}
