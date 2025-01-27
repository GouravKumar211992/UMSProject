<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class ResultsSubjects extends Model
{

    use SoftDeletes;

	protected $table = 'results_subjects';
	protected $appends = ['total_marks'];

	public function course() {
		return $this->hasOne(Course::class, 'id','course_id')->withTrashed();
	}
	public function semester() {
		return $this->hasOne(Semester::class, 'id','semester_id')->withTrashed();
	}
    
}
