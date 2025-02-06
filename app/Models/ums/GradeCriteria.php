<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradeCriteria extends Model
{
	use SoftDeletes;

	protected $hidden = [
		'deleted_at'
	];

	protected $fillable = [
        'name', 'organization_id', 'status','min', 'max'
    ];
}
