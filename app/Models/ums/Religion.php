<?php

namespace App\models\ums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Religion extends Model
{
    use SoftDeletes;
    protected $table = 'religions';

    protected $fillable = [
        'name',
        'descriptions',
    ];

    protected $hidden = [
        'deleted_at',
    ];

}