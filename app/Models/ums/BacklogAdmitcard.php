<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;


class BacklogAdmitcard extends Model
{
   protected $fillable = [
        'roll_no',
        'enrollment_no',
        'student_name',
        'father_name',
        'mother_name',
        'course_id',
        'branch_id',
        'semester_number',
        'sub_code',
        'adhar_card_number',
        'photo',
        'sign',
        'scribe',
        'category',
        'batch',
        'gender',
        'dob',
        'form_type',
    ];
	public function course() {
		return $this->hasOne(Course::class,'id', 'course_id')->withTrashed();
	}
	public function stream() {
		return $this->hasOne(Stream::class, 'course_id','course_id')->withTrashed();
	}

}
