<?php

namespace App\models\ums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExternalMark extends Model
{
    use SoftDeletes;
	
	 protected $fillable = [

        'campus_code',	
        'campus_name',	
        'program_id',	
        'program_name',	
        'course_id',	
        'course_name',	
        'semester_id',	
        'semester_name',	
        'session',	
        'faculty_id',	
        'sub_code',	
        'sub_name',	
        'date_of_semester_exam',	
        'maximum_mark',	
        'student_name',	
        'enrollment_number',	
        'roll_number',	
        'semester_marks',	
        'total_marks',	
        'total_marks_words',
        'comment',	
    ];

	public function subjects() {
		return $this->hasMany(Subject::class, 'sub_code','sub_code');
	}
	public function faculty() {
		return $this->belongsTo(Faculty::class, 'faculty_id');
	}
	public function Category() {
		return $this->belongsTo(Category::class, 'program_id');
	}
	public function Course() {
		return $this->belongsTo(Course::class, 'course_id');
	}
	public function Semester() {
		return $this->belongsTo(Semester::class, 'semester_id');
	}
	public function file() {
		return $this->hasOne(AwardSheetFile::class, 'id','award_sheet_file_id');
	}



}
