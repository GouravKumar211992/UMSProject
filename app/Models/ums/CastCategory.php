<?php

namespace App\models\ums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CastCategory extends Model
{
    use SoftDeletes;
    protected $table = 'cast_category';

    protected $fillable = [
        'name',
        'descriptions',
    ];

    protected $hidden = [
        'deleted_at',
    ];

}