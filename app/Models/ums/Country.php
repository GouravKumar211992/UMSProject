<?php

namespace App\models\ums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

    protected $table = 'countries';

    protected $fillable = [
        'name', 
        'code', 
        'dial_code', 
        'created_at', 
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var arraysequence
     */
    protected $hidden = ['deleted_at'];

}
