<?php

namespace App\models\ums;
// use Auth;
// use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseSubject extends Model
{
    protected $fillable = [
        'course_id','subject_id', 'created_by','updated_by'
    ];
	public function course() {
		return $this->belongsTo(Course::class, 'course_id');
	}
	public function subject() {
		return $this->belongsTo(Subject::class, 'subject_id');
	}

}
