<?php

namespace App\models\ums;
use Illuminate\Database\Eloquent\Model;

class StudentAllFromOldAgency extends Model
{
    protected $table = 'students_old';

    public function course() {
		return $this->belongsTo(Course::class, 'course_id')->withTrashed();
	}


	public function getExamData($type,$session){
          $examfee_data = ExamFee::where('roll_no',$this->roll_no)
          ->where('form_type',$type)
          ->where('academic_session',$session)
          ->first();
          return $examfee_data;
	}

}
