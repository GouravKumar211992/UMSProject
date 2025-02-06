<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campuse extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name',
        'is_affiliated',
        'short_name',
        'email',
        'campus_code',
        'contact',
        'website',
        'address',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    public function getCourse(){
        return $this->hasMany(Course::class, 'campus_id')->orderBy('name','ASC');
    }

}