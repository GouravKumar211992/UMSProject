<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use SoftDeletes;

    protected $table = 'states';

    protected $fillable = [
        'name', 
        'country_id', 
        'created_at', 
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var arraysequence
     */
    protected $hidden = ['deleted_at'];

    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

}
