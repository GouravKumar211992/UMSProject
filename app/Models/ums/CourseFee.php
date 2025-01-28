<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseFee extends Model
{
	use SoftDeletes;

	public function course() {
		return $this->hasOne(Course::class,'id','course_id');
	}
	
}
