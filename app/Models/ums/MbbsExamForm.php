<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MbbsExamForm extends Model
{
     use SoftDeletes;
	 
	 protected $fillable = [

        'exam_fee_id',	
        'rollno',	
        'enrollment_number',
        'name',	
        'father_name',	
        'mother_name',	
        'date_of_birth',	
        'mobile',	
        'email',	
        'alternate_email_id',	
        'aadhar',	
        'address',	
        'course_id',	
        'branch_id',	
        'batch',	
        'scribe',	
        'semester',	
        'sub_code',	
        'form_type',	
        'exam_form',	
        'exam_fees',	
        'vaccinated',	
        'pin_code',	
        'category',	
        'gender',	
        'bank_name',	
        'branch_name',	
        'receipt_no',	
        'receipt_date',	
        'amount',	
        'bank_ifsc_code',	
        'is_agree',
    ];
	public function Subject() {
	return $this->hasOne(Subject::class, 'sub_code','sub_code')->withTrashed();
	}
	public function semesters() {
		return $this->hasOne(Semester::class, 'id','semester');
	}
	public function course() {
		return $this->hasOne(Course::class, 'id','course_id');
	}
	public function stream() {
		return $this->hasOne(Stream::class, 'course_id','course_id');
	}
	public function student() {
		return $this->hasOne(StudentAllFromOldAgency::class, 'roll_no','rollno');
	}
}
