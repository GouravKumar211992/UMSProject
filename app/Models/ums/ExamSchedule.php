<?php

namespace App\models\ums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamSchedule extends Model
{
    use SoftDeletes;

    public $table="exam_schedules";
    protected $fillable = [
        'campus' ,
        'courses_id',
        'courses_name' ,
        'semester_id' ,
        'semester_name' ,
        'date' ,
        'shift' ,
        'paper_code' ,
        'paper_name' ,
        'year' ,
        'schedule_count' ,
        'created_at' ,
        'updated_at' ,
        'deleted_at'
    ];
    public function subject() {
		return $this->hasOne(Subject::class,'sub_code','paper_code');
	}
    public function semester() {
		return $this->belongsTo(Semester::class,'semester_id');
	}
	public function course() {
		return $this->belongsTo(course::class,'course_id');
	}
}
