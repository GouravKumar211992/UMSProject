<?php

namespace App\Models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Subject extends Model
{

    use SoftDeletes;

	protected $appends = ['total_marks'];

    protected $fillable = [
        'program_id',
        'course_id',
        'semester_id',
        'stream_id',
        'name',
        'sub_code',
		'back_fees',
		'subject_type',
		'type',
		'maximum_mark',
		'minimum_mark',
		'internal_maximum_mark',
		'credit',
        'created_by',
        'updated_by',
        'status',
        'batch'
    ];

   public function category() {
		return $this->hasOne(Category::class, 'id','program_id')->withTrashed();
	}
	public function course() {
		return $this->hasOne(Course::class, 'id','course_id')->withTrashed();
	}
	public function semester() {
		return $this->hasOne(Semester::class, 'id','semester_id')->withTrashed();
	}
	public function stream() {
		return $this->hasOne(Stream::class, 'id','stream_id')->withTrashed();
	}
	
	public function schedule() {
        return $this->hasOne(ExamSchedule::class,'paper_code','sub_code')->where('courses_id',$this->course_id);
    }
	
	function getTotalMarksAttribute() {
		return ( (int)$this->internal_maximum_mark + (int)$this->maximum_mark );
	}

	public function mark_filed_details($session){
		$studentSubject = StudentSubject::where('course_id',$this->course_id)
			->where('semester_id',$this->semester_id)
			->where('sub_code',$this->sub_code)
			->where('session',$session)
			->where('type','regular')
			->orderBy('roll_number')
			->get();
		$internalMark = InternalMark::where('course_id',$this->course_id)
			->where('semester_id',$this->semester_id)
			->where('sub_code',$this->sub_code)
			->where('session',$session)
			->where('type','regular')
			->get();
		$externalMark = ExternalMark::where('course_id',$this->course_id)
			->where('semester_id',$this->semester_id)
			->where('sub_code',$this->sub_code)
			->where('session',$session)
			->where('type','regular')
			->get();
		$practicalMark = PracticalMark::where('course_id',$this->course_id)
			->where('semester_id',$this->semester_id)
			->where('sub_code',$this->sub_code)
			->where('session',$session)
			->where('type','regular')
			->get();
		return (object)[
				'all_count'=>$studentSubject->count(),
				'internalMark'=>$internalMark->count(),
				'externalMark'=>$externalMark->count(),
				'practicalMark'=>$practicalMark->count(),
				'get_all_students'=>$studentSubject,
				'internalMark_array'=>$internalMark,
				'externalMark_array'=>$externalMark,
				'practicalMark_array'=>$practicalMark,
		];
	}


	public function getBackPaper($exam_fee_id,$roll_number){
		$backPaper = BackPaper::where('roll_number',$roll_number)
			->where('sub_code',$this->sub_code)
			->where('exam_fee_id',$exam_fee_id)
			->first();
		return $backPaper;

	}
	public function getResult($result_data){
		if($result_data) {
			$result = Result::where('roll_no', $result_data->roll_no)
			->where('exam_session', $result_data->academic_session)
			->where('course_id', $this->course_id)
			->where('semester', $this->semester_id)
			->where('subject_code', $this->sub_code)
			->orderBy('back_status_text', 'DESC')
			->first();
			return $result;
		}else{
			return null;
		}
	}
	public function getResultBack($result_data){
		if($result_data) {
			$result = Result::where('roll_no', $result_data->roll_no)
			->where('exam_session', $result_data->academic_session)
			->where('course_id', $this->course_id)
			->where('semester', $this->semester_id)
			->where('subject_code', $this->sub_code)
			->orderBy('id', 'DESC')
			->first();
			return $result;
		}else{
			return null;
		}
	}
	public function mbbs_subjects($batch){
		return Subject::where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		->where('combined_subject_name','!=',null)
		->where('batch',$batch)
		->where('combined_subject_name',$this->combined_subject_name)
		->get();
	}
	public function mbbs_theory_total($batch,$view='view1'){
		$total = 0;
		$subject = Subject::where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		->where('combined_subject_name','!=',null)
		->where('batch',$batch)
		->where('combined_subject_name',$this->combined_subject_name)
		->where('subject_type','Theory')->get();
		$maximum_mark = $subject->sum(DB::raw('maximum_mark'));
		$total = $total + $maximum_mark;
		if($view=='view2' || $view=='view3'){
			$total = $total + $subject->sum(DB::raw('internal_maximum_mark'));
			$total = $total + $subject->sum(DB::raw('oral'));
		}
		return $total;
	}
	public function mbbs_practical_total($batch,$view='view1'){
		$total = 0;
		$subject = Subject::where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		->where('combined_subject_name','!=',null)
		->where('batch',$batch)
		->where('combined_subject_name',$this->combined_subject_name)
		->where('subject_type','Practical')->get();
		$maximum_mark = $subject->sum(DB::raw('maximum_mark'));
		$total = $total + $maximum_mark;
		if($view=='view2' || $view=='view3'){
			$total = $total + $subject->sum(DB::raw('internal_maximum_mark'));
		}
		return $total;
	}
	public function mbbs_subject_total($batch,$view='view1'){
		$total = 0;
		$subject = Subject::where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		->where('combined_subject_name','!=',null)
		->where('batch',$batch)
		->where('combined_subject_name',$this->combined_subject_name)
		// ->where('subject_type','Theory')
		->get();
		$maximum_mark = $subject->sum(DB::raw('maximum_mark'));
		$total = $total + $maximum_mark;
		if($view=='view2' || $view=='view3'){
			$total = $total + $subject->sum(DB::raw('internal_maximum_mark'));
			$total = $total + $subject->sum(DB::raw('oral'));
		}
		return $total;
	}
	public function mbbs_subject_grand_total($batch,$view='view1'){
		$total = 0;
		$subject = Subject::where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		->where('batch',$batch)
		->where('combined_subject_name','!=',null)
		// ->where('combined_subject_name',$this->combined_subject_name)
		// ->where('subject_type','Theory')
		->get();
		$total = $total + $subject->sum(DB::raw('maximum_mark'));
		if($view=='view2' || $view=='view3'){
			$total = $total + $subject->sum(DB::raw('internal_maximum_mark'));
			$total = $total + $subject->sum(DB::raw('oral'));
		}
		return $total;
	}
	public function mbbs_grand_total($batch,$view='view1'){
		$total = 0;
		$subject = Subject::where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		->where('batch',$batch)
		->where('combined_subject_name','!=',null)
		->where('combined_subject_name',$this->combined_subject_name)
		// ->where('subject_type','Theory')
		->get();
		$maximum_mark = $subject->sum(DB::raw('maximum_mark'));
		$total = $total + $maximum_mark;
		if($view=='view2' || $view=='view3'){
			$total = $total + $subject->sum(DB::raw('internal_maximum_mark'));
			$total = $total + $subject->sum(DB::raw('oral'));
		}
		return $total;
	}
	public function mbbs_internal_assessment_total($batch){
		return Subject::where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		->where('combined_subject_name','!=',null)
		->where('batch',$batch)
		->where('combined_subject_name',$this->combined_subject_name)
		->sum(\DB::raw('internal_maximum_mark'));
	}
	public function mbbs_theory_ia_total($batch){
		return Subject::where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		->where('combined_subject_name','!=',null)
		->where('batch',$batch)
		->where('combined_subject_name',$this->combined_subject_name)
		->where('subject_type','Theory')
		->sum(\DB::raw('internal_maximum_mark'));
	}
	public function mbbs_practical_ia_total($batch){
		return Subject::where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		->where('combined_subject_name','!=',null)
		->where('batch',$batch)
		->where('combined_subject_name',$this->combined_subject_name)
		->where('subject_type','Practical')
		->sum(\DB::raw('internal_maximum_mark'));
	}
	function mbbs_subject_result($roll_number){
		$result = Result::where('roll_no',$roll_number)
		->where('results.course_id',$this->course_id)
		->where('semester',$this->semester_id)
		->where('subject_code',$this->sub_code)
		->first();
		return $result;
	}

	public function mbbs_result_theory_total($batch,$roll_no,$view='view1'){
		$total = 0;
		$sub_query = Subject::where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		->where('batch',$batch)
		->where('combined_subject_name','!=',null)
		->where('combined_subject_name',$this->combined_subject_name)
		->where('subject_type','Theory')
		->pluck('sub_code')
		->toArray();
		$result = Result::where('roll_no',$roll_no)
		->where('results.course_id',$this->course_id)
		->where('semester',$this->semester_id)
		->whereIn('subject_code',$sub_query)
		->get();
		foreach($result as $row){
			$total = $total + (int)$row->external_marks;
			if($view=='view2' || $view=='view3'){
				$total = $total + (int)$row->internal_marks;
				$total = $total + (int)$row->oral;
			}
		}
		return $total;
	}
	public function mbbs_result_practical_total($roll_no,$view='view1'){
		$total = 0;
		$sub_query = Subject::where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		->where('combined_subject_name','!=',null)
		->where('combined_subject_name',$this->combined_subject_name)
		->where('subject_type','Practical')
		->pluck('sub_code')
		->toArray();
		$result = Result::where('roll_no',$roll_no)
		->where('results.course_id',$this->course_id)
		->where('semester',$this->semester_id)
		->whereIn('subject_code',$sub_query)
		->get();
		foreach($result as $row){
			$total = $total + (int)$row->external_marks;
			if($view=='view2' || $view=='view3'){
				$total = $total + (int)$row->internal_marks;
				$total = $total + (int)$row->oral;
			}
		}
		return $total;
	}
	function mbbs_grace_mark($roll_no){
        $results_details = Result::where('roll_no',$roll_no)->first();
		return $results_details->mbbs_final_result_grade($roll_no,$this->course_id,$this->semester_id,$this->combined_subject_name);
	}
	// MBBS Code
	public function mbbs_grade_for_third($subject,$obtained_marks){
		$required_mark = (($subject*50)/100);
		if($obtained_marks >= $required_mark){
			return '';
		}
		$obtained_marks = ($obtained_marks + 5);
		if(($obtained_marks >= $required_mark)){
			return '*';
		}else{
			return '#';
		}
}

	function mbbs_result_total($roll_no,$view='view1'){
		$total = 0;
		$sub_query = Subject::where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		->where('combined_subject_name','!=',null)
		->where('combined_subject_name',$this->combined_subject_name)
		->pluck('sub_code')
		->toArray();
		$result = Result::where('roll_no',$roll_no)
		->where('results.course_id',$this->course_id)
		->where('semester',$this->semester_id)
		->whereIn('subject_code',$sub_query)
		->get();
		foreach($result as $row){
			$total = $total + (int)$row->external_marks;
			if($view=='view2' || $view=='view3'){
				$total = $total + (int)$row->internal_marks;
				$total = $total + (int)$row->oral;
			}
		}
		return $total;
	}
	function mbbs_result_grand_total($batch,$roll_no,$view='view1'){
		$total = 0;
		$sub_query = Subject::where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		->where('batch',$batch)
		->where('combined_subject_name','!=',null)
		// ->where('combined_subject_name',$this->combined_subject_name)
		->pluck('sub_code')
		->toArray();
		$result = Result::where('roll_no',$roll_no)
		->where('results.course_id',$this->course_id)
		->where('semester',$this->semester_id)
		->whereIn('subject_code',$sub_query)
		->get();
		foreach($result as $row){
			$total = $total + (int)$row->external_marks;
			if($view=='view2' || $view=='view3'){
				$total = $total + (int)$row->internal_marks;
				$total = $total + (int)$row->oral;
			}
		}
		return $total;
	}
	public function mbbs_result_theory_ia_total($batch,$roll_no){
		$sub_query = Subject::where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		->where('batch',$batch)
		->where('combined_subject_name','!=',null)
		->where('combined_subject_name',$this->combined_subject_name)
		->where('subject_type','Theory')
		->pluck('sub_code')
		->toArray();
		$result = Result::where('roll_no',$roll_no)
		->where('results.course_id',$this->course_id)
		->where('semester',$this->semester_id)
		->whereIn('subject_code',$sub_query)
		->sum(\DB::raw('internal_marks'));
		return $result;
	}
	public function mbbs_result_theory_ia_total_2018($batch,$roll_no){
		$sub_query = Subject::where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		->where('batch',$batch)
		->where('combined_subject_name','!=',null)
		->where('combined_subject_name',$this->combined_subject_name)
		->where('subject_type','Theory')
		->pluck('sub_code')
		->toArray();
		$result = Result::where('roll_no',$roll_no)
		->where('results.course_id',$this->course_id)
		->where('semester',$this->semester_id)
		->whereIn('subject_code',$sub_query)
		->sum(\DB::raw('oral'));
		return $result;
	}
	public function mbbs_result_theory_oral_total($batch,$roll_no){
		$sub_query = Subject::where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		->where('batch',$batch)
		->where('combined_subject_name','!=',null)
		->where('combined_subject_name',$this->combined_subject_name)
		->where('subject_type','Theory')
		->pluck('sub_code')
		->toArray();
		$result = Result::where('roll_no',$roll_no)
		->where('results.course_id',$this->course_id)
		->where('semester',$this->semester_id)
		->whereIn('subject_code',$sub_query)
		->sum(\DB::raw('oral'));
		return $result;
	}
	public function mbbs_result_practical_oral_total($batch,$roll_no){
		$sub_query = Subject::where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		->where('combined_subject_name','!=',null)
		->where('batch',$batch)
		->where('combined_subject_name',$this->combined_subject_name)
		->where('subject_type','Practical')
		->pluck('sub_code')
		->toArray();
		$result = Result::where('roll_no',$roll_no)
		->where('results.course_id',$this->course_id)
		->where('semester',$this->semester_id)
		->whereIn('subject_code',$sub_query)
		->sum(\DB::raw('internal_marks'));
		return $result;
	}
	

	public function mbbs_result_internal_assessment_total($batch,$roll_no,$view=''){
		$sub_query = Subject::where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		->where('combined_subject_name','!=',null)
		->where('batch',$batch)
		->where('combined_subject_name',$this->combined_subject_name)
		// ->where('subject_type','Theory')
		->pluck('sub_code')
		->toArray();
		$result = Result::where('roll_no',$roll_no)
		->where('results.course_id',$this->course_id)
		->where('semester',$this->semester_id)
		->whereIn('subject_code',$sub_query)
		->sum(\DB::raw('(oral + internal_marks)'));
		return $result;
	}
	
	public function mbbs_result_internal_assessment_total_view1($batch,$roll_no,$view=''){
		$sub_query = Subject::where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		->where('combined_subject_name','!=',null)
		->where('batch',$batch)
		->where('combined_subject_name',$this->combined_subject_name)
		// ->where('subject_type','Theory')
		->pluck('sub_code')
		->toArray();
		$result = Result::where('roll_no',$roll_no)
		->where('results.course_id',$this->course_id)
		->where('semester',$this->semester_id)
		->whereIn('subject_code',$sub_query)
		->sum(\DB::raw('internal_marks'));
		return $result;
	}
	
	public function mbbs_result_internal_assessment_total_view1_2018($batch,$roll_no,$view=''){
		$sub_query = Subject::where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		->where('combined_subject_name','!=',null)
		->where('batch',$batch)
		->where('combined_subject_name',$this->combined_subject_name)
		->where('subject_type','Theory')
		->pluck('sub_code')
		->toArray();
		$result = Result::where('roll_no',$roll_no)
		->where('results.course_id',$this->course_id)
		->where('semester',$this->semester_id)
		->whereIn('subject_code',$sub_query)
		->get();
		$total = 0;
		foreach($result as $row){
			$total = $total + ((int)$row->oral + (int)$row->internal_marks);
		}
		
		return $total;
	}
	
}
