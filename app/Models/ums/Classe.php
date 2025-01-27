<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
   
   public function categories() {
		return $this->belongsTo(Category::class,'program_id');
	}
	public function course() {
		return $this->belongsTo(Course::class, 'course_id');
	}
	
	public function semester() {
		return $this->belongsTo(Semester::class, 'semester_id');
	}
	
	public function subject() {
		return $this->belongsTo(Subject::class, 'sub_code','sub_code');
	}
	

}
