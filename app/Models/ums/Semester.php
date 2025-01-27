<?php
namespace App\models\ums;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Result;

class Semester extends Model
{
   use SoftDeletes;
protected $fillable = [
		'program_id',
		'course_id',
		'name',
		'semester_number'	
		];
		
	public function category() {
		return $this->belongsTo(Category::class, 'program_id');
	}
	public function course() {
		return $this->belongsTo(Course::class, 'course_id');
	}
	public function subject() {
      return $this->hasMany(Subject::class);
	}
	public function semester_max_marks($session) {
      $result = Result::select('roll_no')
	  ->where('back_status_text','REGULAR')
	  ->where('exam_session',$session)
	  ->where('semester',$this->id)
	//   ->where('result','P')
	  ->distinct('roll_no')
	  ->orderBy('roll_no','DESC')
	  ->first();
	  if(!$result){
		return 0;
	  }
      $internal_maximum_mark = Result::where('roll_no',$result->roll_no)
	  ->where('back_status_text','REGULAR')
	  ->where('exam_session',$session)
	  ->where('credit','>',0)
	  ->where('semester',$this->id)
	  ->sum('max_internal_marks');
      $externam_maximum_mark = Result::where('roll_no',$result->roll_no)
	  ->where('back_status_text','REGULAR')
	  ->where('exam_session',$session)
	  ->where('credit','>',0)
	  ->where('semester',$this->id)
	  ->sum('max_external_marks');
	  return ($internal_maximum_mark + $externam_maximum_mark);
	}
	public function result_data($roll_no,$type) {
      $result = Result::where('roll_no',$roll_no)->where('semester',$this->id)->first();
	  if(!$result){
		return null;
	  }
	  if($type==1){
		return $result->get_semester_result_single();
		}else{
			return $result->get_semester_result(1);
		}
	}

	function getOddEven(){
		if($this->semester_number % 2 == 0){
			return 'EVEN';
		}else{
			return 'ODD';
		}
	}

	public function getNextSemester(){
		$semester = Semester::withTrashed()
			->where('semester_number',($this->semester_number+1))
			->where('course_id',$this->course_id)
			->first();
			return $semester;
	}
	public function getLastSemester(){
		$semester = Semester::withTrashed()
			->where('semester_number',($this->semester_number-1))
			->where('course_id',$this->course_id)
			->first();
			return $semester;
	}
	public function examFees() {
        return $this->hasMany(ExamFee::class, 'course_id', 'course_id')->where('semester', $this->semester);
    }

	public function get_subjects_from_result($batch){
		$subjects = ResultsSubjects::select('subject_name','course_id','semester_id','subject_code','max_internal_marks','max_external_marks','max_total_marks')
		->where('course_id', $this->course_id)
		->where('semester_id', $this->id)
		->where('batch', $batch)
		->distinct()
		->get();
		return $subjects;
	}
}
