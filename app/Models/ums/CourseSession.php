<?php

namespace App\models\ums;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseSession extends Model
{
    //
	use SoftDeletes;
	
	protected $fillable = [
		'course_id',
		'academic_session',
		'seat',
		'basic_eligibility',	
		'mode_of_admission',
		'course_duration',
		'tuition_fee_for_divyang_per_sem',	
		'tuition_fee_for_other_per_sem',	
		'payable_fee_for_divyang_per_sem',	
		'payable_fee_for_other_per_sem',
	];
	 public function course() {
        return $this->hasOne(Course::class,'id','course_id');
    }
}
