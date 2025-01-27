<?php

namespace App\models\ums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;

class SemesterFee extends Model implements HasMedia
{
	use SoftDeletes,InteractsWithMedia;
	
    protected $fillable = [
			'program_id' ,
			'course_id' ,
            'student_id' ,
            'semester_id' ,
            'registration_fee' ,
            'admission_fee' ,
            'exam_fee' ,
            'tution_fee' ,
            'computer_fee' ,
            'library_fee' ,
            'caution_money' ,
            'insurance_fee' ,
            'student_welfare_fund' ,
			'game_fee'
			];
			public function category() {
		return $this->belongsTo(Category::class, 'program_id')->withTrashed();
	}
	public function course() {
		return $this->belongsTo(Course::class, 'course_id')->withTrashed();
	}
	public function semester() {
		return $this->belongsTo(Semester::class, 'semester_id')->withTrashed();
	}

}
