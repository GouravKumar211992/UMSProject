<?php

namespace App\models\ums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseDescription extends Model
{
	use SoftDeletes;

	protected $hidden = [
		'created_by', 'updated_by', 'deleted_at'
	];

	protected $fillable = [
        'section', 'description','created_by'
    ];
}
