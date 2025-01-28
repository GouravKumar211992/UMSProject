<?php

namespace App\models\ums;

use App\Models\Semester;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use DB;

class Result extends Model implements HasMedia
{
	use SoftDeletes,InteractsWithMedia;

    protected $table = 'results';

    protected $fillable = [
		'back_id',
		'back_status_text',
		'enrollment_no',
		'roll_no',
		'exam_session',
		'session_name',
		'semester',
		'semester_number',
		'semester_final',
		'course_id',
		'subject_position',
		'subject_code',
		'subject_name',
		'oral',
		'internal_marks',
		'external_marks',
		'practical_marks',
		'total_marks',
		'absent_status',
		'max_internal_marks',
		'max_external_marks',
		'max_total_marks',
		'credit',
		'total_credit',
		'total_semester_credit',
		'grade_letter',
		'grade_point',
		'qp',
		'total_qp',
		'sgpa',
		'total_sgpa',
		'cgpa',
		'result',
		'year_back',
		'result_overall',
		'failed_semester_number',
		'obtained_marks',
		'total_obtained_marks',
		'required_marks',
		'total_required_marks',
		'result_type',
		'serial_no',
		'status',
		'back_status',
		'scrutiny',
		'created_at',
		'updated_at',
		'deleted_at',
		'approval_date',
		'external_marks_cancelled',
		'current_internal_marks',
		'current_external_marks',
		'comment'
    ];

    protected $appends = [
        'status_text',
        'result_full',
        'result_full_text',
        'semester_final_check',
        'upload_approval_file',
        'subject_type',
        'oral_int',
        'internal_marks_int',
        'external_marks_int',
        'practical_marks_int',
        'approval_date_format',
    ];


    public function getUploadApprovalFileAttribute()
    {

        if ($this->getMedia('upload_approval_file')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('upload_approval_file')->first()->getFullUrl();
        }
    }

	public function getSemesterFinalCheckAttribute()
    {
		$semeser = Semester::select('id')
			->where('course_id',$this->course_id)
			->orderBy('semester_number','DESC')
			->first();
		if($semeser->id == $this->semester){
			return 1;
		}else{
			return 0;
		}
    }


	public function getCgpaAttribute($value)
    {
		return number_format((float)$value, 2, '.', '');
	}

	public function getResultFullAttribute()
    {
		if($this->result=='P'){
			return 'PASSED';
		}
		else if($this->result=='F'){
			return 'FAILED';
		}
		else if($this->result=='A' || $this->result=='' || $this->result==null){
			return 'ABSENT';
		}else{
			return $this->result;
		}
    }

	public function getResultFullTextAttribute()
    {
		if(!$this){
			return '';
		}
		if($this->result=='P'){
			return 'PASS';
		}else if($this->result=='F' && $this->year_back==1){
			return 'Year Back';
		}else if($this->result=='F' && $this->year_back==0){
			return 'FAILED';
		}else if($this->result=='A' || $this->result=='' || $this->result==null){
			return 'ABSENT';
		}else if($this->result=='PCP'){
			// echo 'result full text';
			// dd($this);
			// return 'Promoted with Carry Over Papers';
		}else{
			return $this->result;
		}
    }

	public function getResultAttribute($value)
    {
		if($value=='PASS'){
			return 'P';
		}
		if($value=='FAILED'){
			return 'F';
		}
		if($value=='ABSENT'){
			return 'A';
		}else{
			return $value;
		}
    }

	public function getStatusTextAttribute()
    {
        if($this->status==2){
			return 'Approved';
		}else{
			return 'Pending';
		}
    }
	public function getApprovalDateFormatAttribute()
    {
        if($this->approval_date){
			return date('d-m-Y',strtotime($this->approval_date));
		}else{
			return $this->approval_date;
		}
    }


    public function getInternalMarksAttribute($value)
    {

		if($this->course_id == 49){
			return $value;
		}

		if( ($this->oral > 0) && ($value == null || $value == 'ABSENT') ){
			return $this->oral;
		}
		if( ($this->oral == 0) && ($value == null) ){
			return '';
		}

		// if(!$this->subject){
		// 	dd('Subject not found '.$this);
		// }
		if($value == null && $this->subject->type == 'optional'){
			return '';
		}
		if($value == null && $this->subject->type == 'compulsory' && $this->subject->internal_maximum_mark == 0){
			return '';
		}

		if($value == null){
			return '';
		}else if($value == 'ABSENT'){
			return 'ABS';
		}else{
			$internalMarks = ( (int)$value + (int)$this->oral );
			return $internalMarks;
		}
    }

	public function getExternalMarksAttribute($value)
    {
		if($this->external_marks_value == true){
			return $value;
		}
		if($this->course_id == 49){
			$externalMarks = ( (int)$value + (int)$this->practical_marks );
			return $externalMarks;
		}
		if( ($this->practical_marks > 0) && ($value == null || $value == 'ABSENT') ){
			return $this->practical_marks;
		}
		// if($this->subject==null && $this->result_type='new'){
		// 	return '';
		// 	// dd($this);
		// }

		// if(!$this->subject){
		// 	echo ('Subject not found ');
		// 	dd($this);
		// }
		if($value == null && $this->practical_marks == null && $this->subject && $this->subject->type == 'optional'){
			return '';
		}

		if($value == null && $this->practical_marks == null && $this->subject && $this->subject->type == 'compulsory' && $this->subject->maximum_mark == 0){
			return '';
		}

		if($value=='UFM'){
			return 'CANCELLED';
		}
		if( $value == 'ABSENT' && $this->practical_marks == 'ABSENT' ){
			return 'ABS';
		}elseif( $value == null && $this->practical_marks == null ){
			return 'ABS';
		}elseif( $value == null && $this->practical_marks == 'ABSENT' ){
			return 'ABS';
		}elseif( $value == 'ABSENT' && $this->practical_marks == null ){
			return 'ABS';
		}else{
			$externalMarks = ( (int)$value + (int)$this->practical_marks );
			return $externalMarks;
		}
    }
    public function getOralIntAttribute($value){
		if(is_numeric($value)){
			return $value;
		}else{
			return 0;
		}
    }
    public function getInternalMarksIntAttribute($value)
	{
		if(is_numeric()){
			return $value;
		}else{
			return 0;
		}
    }
	public function getExternalMarksIntAttribute($value){
		if(is_numeric($value)){
			return $value;
		}else{
			return 0;
		}
    }
    public function getPracticalMarksIntAttribute($value){
		if(is_numeric($value)){
			return $value;
		}else{
			return 0;
		}
    }
    public function getTotalMarksAttribute($value)
    {
		if($this->course_id == 49){
			// return $value;
		}

		$totalMarks = ( (int)$this->internal_marks + (int)$this->external_marks );
		return $totalMarks;
    }
    public function getMaxTotalMarksAttribute($value)
    {
		if($this->course_id == 49){
			return $value;
		}

		$totalMarks = ( (int)$this->max_internal_marks + (int)$this->max_external_marks );
		return $totalMarks;
    }

	public function course() {
		return $this->hasOne(Course::class, 'id','course_id');
	}
	public function course_name(){
		if($this->semester==135 || $this->semester==136){
			return 'BVA';
		}else{
			return $this->course->name;
		}
	}
	public function course_description(){
		if($this->semester==135 || $this->semester==136){
			return 'BACHELOR OF VISUAL ARTS';
		}else{
			return $this->course->course_description;
		}
	}
	public function semester() {
		return $this->hasOne(\App\Models\Semester::class, 'id','semester');
	}
	public function semester_details() {
		return $this->hasOne(Semester::class, 'id','semester')->withTrashed();
	}
	public function semester_by_number() {
		return $this->hasOne(Semester::class, 'semester_number','failed_semester_number')->where('course_id',$this->course_id);
	}

	public function subject() {
		return $this->hasOne(Subject::class, 'sub_code','subject_code')
			->where('semester_id',$this->semester)
			->where('course_id',$this->course_id);
			//->withTrashed();
	}
	public function getSubjectTypeAttribute() {
		$subject = Subject::withTrashed()
		->where('sub_code',$this->subject_code)
		->where('course_id',$this->course_id)
		->where('semester_id',$this->semester)
		->first();
		return $subject->subject_type;
	}

	public function grade(){
		$student_total = $this->total_marks;
		$subject_total = $this->max_total_marks;
		$student_total_percent = 0;
		if($student_total > 0 && $subject_total > 0 ){
			$student_total_percent = ($student_total*100)/$subject_total;
		}
		$student_total_percent = (int)$student_total_percent;

		// $academic_session = '2021-2022';
		$student_batch = batchFunctionReturn($this->roll_no);
		if(grade_old_allowed_semester($this->semester,$student_batch)){
			$grade = GradeOld::where('min','<=',$student_total_percent)
			->where('max','>=',$student_total_percent)
			->first();
		}else{
			$grade = Grade::where('min','<=',$student_total_percent)
			->where('max','>=',$student_total_percent)
			->first();
		}
		if(!$grade){
			dd('Max marks is less than obtained marks',$this);
		}
		if((int)$this->internal_marks > $this->max_internal_marks){
			dd('Max Internal marks is less than obtained internal marks',$this);
		}
		if((int)$this->external_marks > $this->max_external_marks){
			dd('Max External marks is less than obtained external marks',$this,$this->external_marks);
		}

		return $grade;
	}

	public function semesters() {
		return $this->hasOne(Semester::class, 'id','semester')->withTrashed();
	}

	public function enrollments() {
		return $this->hasOne(Enrollment::class, 'enrollment_no','enrollment_no');
	}
	public function application() {
		$application = Application::select('applications.*')
		->join('enrollments','enrollments.application_id','applications.id')
		->where('enrollments.roll_number',$this->roll_no)
		->first();
		return $application;
	}
	public function student() {
		return $this->hasOne(Student::class, 'roll_number','roll_no');
	}
	public function students() {
		return $this->hasOne(StudentSubject::class, 'enrollment_number','enrollment_no');
	}
	public function examFees() {
		return $this->hasOne(ExamFee::class, 'semester','semester')->where('course_id',$this->course_id);
	}
	public function Mbbs() {
		return $this->hasOne(MbbsExamForm::class, 'rollno','roll_no')->withTrashed();
	}
	public function subjectSuggetions($semester) {
		$subject = Subject::withTrashed()
		->where('sub_code',$this->subject_code)
		->where('semester_id',$semester)
		->pluck('name')
		->toArray();
		return $subject;
	}
	
	public function final_result_grade($roll_no,$course_id,$semester_id,$combined_subject_name){
			$grade = $this->final_result_grade_single($roll_no,$course_id,$semester_id,$combined_subject_name);
			if($grade!=''){
				return $grade;
			}
			$obtained_marks_total = Result::join('subjects','subjects.sub_code','results.subject_code')
			->where('roll_no',$roll_no)
			->whereNotNull('combined_subject_name')
			->where('subjects.course_id',$course_id)
			->where('semester',$semester_id)
			->where('combined_subject_name',$combined_subject_name)
			->where('subject_type','Theory')
			->get();
			$subject_total = Subject::where('subjects.course_id',$course_id)
			->whereNotNull('combined_subject_name')
			->where('semester_id',$semester_id)
			->where('combined_subject_name',$combined_subject_name)
			->where('subject_type','Theory')
			->get();


			$subject_total_test = Result::join('subjects','subjects.sub_code','results.subject_code')
			->where('roll_no',$roll_no)
			->whereNotNull('combined_subject_name')
			->where('subjects.course_id',$course_id)
			->where('semester',$semester_id)
			->where('combined_subject_name',$combined_subject_name)
			->where('subject_type','Theory')
			->first();
			if(!$subject_total_test){
				return '';
			}
			$subject = $subject_total->sum('maximum_mark');

			$obtained_marks = $obtained_marks_total->sum('external_marks');

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
	public function mbbs_final_result_grade($roll_no,$course_id,$semester_id,$combined_subject_name){
			$batch = batchFunctionMbbs($roll_no);
			$grade = $this->final_result_grade_single($roll_no,$course_id,$semester_id,$combined_subject_name);
			if($grade!=''){
				return $grade;
			}
			$obtained_marks_total = Result::join('subjects','subjects.sub_code','results.subject_code')
			->where('roll_no',$roll_no)
			->whereNotNull('combined_subject_name')
			->where('subjects.course_id',$course_id)
			->where('semester',$semester_id)
			->where('batch',$batch)
			->where('combined_subject_name',$combined_subject_name)
			->where('subject_type','Theory')
			->get();
			$subject_total = Subject::where('subjects.course_id',$course_id)
			->whereNotNull('combined_subject_name')
			->where('semester_id',$semester_id)
			->where('batch',$batch)
			->where('combined_subject_name',$combined_subject_name)
			->where('subject_type','Theory')
			->get();


			$subject_total_test = Result::join('subjects','subjects.sub_code','results.subject_code')
			->where('roll_no',$roll_no)
			->whereNotNull('combined_subject_name')
			->where('subjects.course_id',$course_id)
			->where('semester',$semester_id)
			->where('combined_subject_name',$combined_subject_name)
			->where('subject_type','Theory')
			->first();
			if(!$subject_total_test){
				return '';
			}
			$subject = $subject_total->sum('maximum_mark');

			$obtained_marks = $obtained_marks_total->sum('external_marks');

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
	
	public function final_result_grade_theory_practical($roll_no,$course_id,$semester_id,$combined_subject_name){
		$theory = $this->final_result_grade_theory($roll_no,$course_id,$semester_id,$combined_subject_name);
		$practical = $this->final_result_grade_practical($roll_no,$course_id,$semester_id,$combined_subject_name);
		if($theory == '#' || $practical == '#'){
			return '#';
		}elseif($theory == '*' || $practical == '*'){
			return '*';
		}else{
			return '';
		}
	}
	public function final_result_grade_practical($roll_no,$course_id,$semester_id,$combined_subject_name){
			$batch = batchFunctionReturn($roll_no);
			$grade = $this->final_result_grade_single_practical($roll_no,$course_id,$semester_id,$combined_subject_name);
			if($grade!=''){
				return $grade;
			}
			$obtained_marks_total = Result::select('results.*')
			->join('subjects','subjects.sub_code','results.subject_code')
			->where('roll_no',$roll_no)
			->whereNotNull('combined_subject_name')
			->where('subjects.course_id',$course_id)
			->where('semester',$semester_id)
			->where('combined_subject_name',$combined_subject_name)
			->where('batch',$batch)
			->where('subject_type','Practical')
			->get();
			$subject_total = Subject::where('subjects.course_id',$course_id)
			->whereNotNull('combined_subject_name')
			->where('semester_id',$semester_id)
			->where('combined_subject_name',$combined_subject_name)
			->where('batch',$batch)
			->where('subject_type','Practical')
			->get();

			$subject_total_test = Result::select('results.*')
			->join('subjects','subjects.sub_code','results.subject_code')
			->where('roll_no',$roll_no)
			->whereNotNull('combined_subject_name')
			->where('subjects.course_id',$course_id)
			->where('semester',$semester_id)
			->where('combined_subject_name',$combined_subject_name)
			->where('batch',$batch)
			->where('subject_type','Practical')
			->first();
			if(!$subject_total_test){
				return '';
			}
			$subject = $subject_total->sum('maximum_mark');

			$obtained_marks = $obtained_marks_total->sum('external_marks');

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
	public function final_result_grade_theory($roll_no,$course_id,$semester_id,$combined_subject_name){
			$batch = batchFunctionReturn($roll_no);
			$grade = $this->final_result_grade_single($roll_no,$course_id,$semester_id,$combined_subject_name);
			if($grade!=''){
				return $grade;
			}
			$obtained_marks_total = Result::join('subjects','subjects.sub_code','results.subject_code')
			->where('roll_no',$roll_no)
			->whereNotNull('combined_subject_name')
			->where('subjects.course_id',$course_id)
			->where('semester',$semester_id)
			->where('combined_subject_name',$combined_subject_name)
			->where('batch',$batch)
			->where('subject_type','Theory')
			->get();
			$subject_total = Subject::where('subjects.course_id',$course_id)
			->whereNotNull('combined_subject_name')
			->where('semester_id',$semester_id)
			->where('combined_subject_name',$combined_subject_name)
			->where('batch',$batch)
			->where('subject_type','Theory')
			->get();


			$subject_total_test = Result::join('subjects','subjects.sub_code','results.subject_code')
			->where('roll_no',$roll_no)
			->whereNotNull('combined_subject_name')
			->where('subjects.course_id',$course_id)
			->where('semester',$semester_id)
			->where('combined_subject_name',$combined_subject_name)
			->where('batch',$batch)
			->where('subject_type','Theory')
			->first();
			if(!$subject_total_test){
				return '';
			}
			$subject = $subject_total->sum('maximum_mark');

			$obtained_marks = $obtained_marks_total->sum('external_marks');

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
	

	public function final_result_for_third($item_student){
		//dd($item_student->subjects_group_all->pluck('grace_mark')->toArray());
		$grace_mark_status_array = [];
		$grace_mark_status = $item_student->subjects_group_all->pluck('grace_mark')->toArray();
		foreach($grace_mark_status as $loop1){
			foreach($loop1 as $loop2){
				array_push($grace_mark_status_array,$loop2);
			}
		}
		$grace_mark_status = array_filter($grace_mark_status_array);
		if(count($grace_mark_status)==0){
			return 'PASS';
		}
		$results = array_count_values($grace_mark_status);

		if(isset($results['#']) && $results['#'] > 0){
			return 'FAIL';
		}elseif(isset($results['*']) && $results['*'] > 1){
			return 'FAIL';
		}elseif(isset($results['*']) && $results['*'] == 1){
			return 'PASS WITH GRACE';
		}else{
			return 'PASS';
		}
	}


	public function final_result_grade_single($roll_no,$course_id,$semester_id,$combined_subject_name){
		$grade = '';
		$results = Result::select('results.*')
		->join('subjects','subjects.sub_code','results.subject_code')
		->where('roll_no',$roll_no)
		->whereNotNull('combined_subject_name')
		->where('subjects.course_id',$course_id)
		->where('semester',$semester_id)
		->where('combined_subject_name',$combined_subject_name)
		->where('subject_type','Theory')
		->where('external_marks','<',40)
		->get();
		foreach($results as $result){
			$subject = Subject::where('subjects.course_id',$course_id)
			->where('semester_id',$semester_id)
			->where('sub_code',$result->subject_code)
			->first();
			$maximum_mark = $subject->maximum_mark;
			$obtained_marks = $result->external_marks;
			$required_mark = (($maximum_mark*40)/100);

			if($obtained_marks >= $required_mark){
				return $grade = '*';
			}elseif($obtained_marks < $required_mark){
				return $grade = '#';
			}
		}
	}
	public function final_result_grade_single_practical($roll_no,$course_id,$semester_id,$combined_subject_name){
		$grade = '';
		$results = Result::select('results.*')
		->join('subjects','subjects.sub_code','results.subject_code')
		->where('roll_no',$roll_no)
		->whereNotNull('combined_subject_name')
		->where('subjects.course_id',$course_id)
		->where('semester',$semester_id)
		->where('combined_subject_name',$combined_subject_name)
		->where('subject_type','Practical')
		->where('external_marks','<',40)
		->get();
		foreach($results as $result){
			$subject = Subject::where('subjects.course_id',$course_id)
			->where('semester_id',$semester_id)
			->where('sub_code',$result->subject_code)
			->first();
			$maximum_mark = $subject->maximum_mark;
			$obtained_marks = $result->external_marks;
			$required_mark = (($maximum_mark*40)/100);

			if($obtained_marks >= $required_mark){
				return $grade = '*';
			}elseif($obtained_marks < $required_mark){
				return $grade = '#';
			}
		}
	}


	public function final_result($item_student){
		$grace_mark_status = $item_student->subjects_group_all->pluck('grace_mark')->toArray();
		$results = array_count_values($grace_mark_status);

		if(isset($results['#']) && $results['#'] > 0){
			return 'FAIL';
		}elseif(isset($results['*']) && $results['*'] > 1){
			return 'FAIL';
		}elseif(isset($results['*']) && $results['*'] == 1){
			return 'PASS WITH GRACE';
		}else{
			return 'PASS';
		}
	}

	public function getResult($cgpa){
		if($cgpa >= 7.5 ){
			return 'P';
			//return 'First Division with Distinction';
		}elseif($cgpa < 7.5 && $cgpa >= 6.5){
			return 'P';
			//return 'First Division';
		}elseif($cgpa < 6.5 && $cgpa >= 5.0){
			return 'P';
			//return 'Second Division';
		}elseif($cgpa < 5.0 && $cgpa >= 4.0){
			return 'P';
			//return 'Third Division';
		}else{
			return 'F';
			//return 'Fail';
		}
    }

	public function getResultFinal($getResult,$absent,$failed,$total_subject){
		$semester = Semester::where('course_id',$this->course_id)->orderBy('semester_number','DESC')->first();
		$pcp_text = 'PCP';
		$absent_text = 'F';
		if($semester->id==$this->semester){
			$pcp_text = 'F';
			$absent_text = 'F';
		}

		$absent = array_filter($absent);
		$counts = array_count_values($absent);

		if($failed == $total_subject){
			if( (isset($counts['ABSENT']) && ($counts['ABSENT']==$total_subject)) || (isset($counts['ABS']) && ($counts['ABS']==$total_subject))){
				return 'A';
			}else{
				return 'F';
			}
		}
		if($failed>=1 && $failed<=4){
			return $pcp_text;
		}
		if($failed > 4){
			return 'F';
		}

		if($getResult == 'F' ){
			if( (isset($counts['ABSENT']) && ($counts['ABSENT']==$total_subject)) || (isset($counts['ABS']) && ($counts['ABS']==$total_subject))){
				return 'A';
			}else{
				if($failed>=1 && $failed<=4){
					return $pcp_text;
				}else{
					return 'F';
				}
			}
		}else{
			return 'P';
		}
    }
	
	public function get_semester_result($status,$result_status=0){
		$get_semester_result_single = $this->get_semester_result_single();
		$results_query = Result::where('roll_no',$this->roll_no)
		->where('semester',$this->semester)
		// ->whereIn('exam_session',[$this->exam_session,$get_semester_result_single->exam_session])
		->whereIn('exam_session',[$get_semester_result_single->exam_session])
		->distinct()
		->orderBy('subject_code','ASC')
		->orderBy('back_status','DESC')
		->orderBy('exam_session','DESC');
		if($result_status==2){
			$results_query->where('status',2);
		}
		if($status==0){
			$results = $results_query->select('enrollment_no','roll_no','course_id','semester','subject_code','oral','internal_marks','external_marks','practical_marks','grade_letter')
			->distinct()->get();
			return $results;
		}
		$results = $results_query->get();
		$sub_code_array = [];
		$result_ids_array = [];
		$i = 0;
		foreach($results as $key=>$result_row){
			if(in_array($result_row->subject_code,$sub_code_array)==false){
				$sub_code_array[] = $result_row->subject_code;
				$result_ids_array[] = $result_row->id;
				$i++;
			}
		}
		$final_result = Result::select('results.*')
		// ->join('subjects',function($query){
		// 	$query->on('subjects.sub_code','results.subject_code');
		// 	$query->on('subjects.course_id','results.course_id');
		// 	$query->on('subjects.semester_id','results.semester');
		// })
		->whereIn('results.id',$result_ids_array)
		->orderBy('subject_position','ASC')
		->get();
		return $final_result;
	}
	public function get_semester_result_back($status,$result_status=0){
		$get_semester_result_single = $this->get_semester_result_single();
		$results_query = Result::where('roll_no',$this->roll_no)
		->where('semester',$this->semester)
		->whereIn('exam_session',[$this->exam_session,$get_semester_result_single->exam_session])
		->distinct()
		->orderBy('subject_code','ASC')
		->orderBy('back_status_text','ASC')
		->orderBy('exam_session','DESC');
		if($result_status==2){
			$results_query->where('status',2);
		}
		if($status==0){
			$results = $results_query->select('enrollment_no','roll_no','course_id','semester','subject_code','oral','internal_marks','external_marks','practical_marks','grade_letter')
			->distinct()->get();
			return $results;
		}
		$results = $results_query->get();
		$sub_code_array = [];
		$result_ids_array = [];
		$i = 0;
		foreach($results as $key=>$result_row){
			if(in_array($result_row->subject_code,$sub_code_array)==false){
				$sub_code_array[] = $result_row->subject_code;
				$result_ids_array[] = $result_row->id;
				$i++;
			}
		}
		$final_result = Result::select('results.*')
		// ->join('subjects',function($query){
		// 	$query->on('subjects.sub_code','results.subject_code');
		// 	$query->on('subjects.course_id','results.course_id');
		// 	$query->on('subjects.semester_id','results.semester');
		// })
		->whereIn('results.id',$result_ids_array)
		->orderBy('subject_position','ASC')
		->get();
		return $final_result;
	}
	public function get_semester_result_for_cgpa($status){
		$get_semester_result_single = $this->get_semester_result_single();

		// $enrollment = Enrollment::where('roll_number',$this->roll_no)->first();
		$results_query = Result::where('roll_no',$this->roll_no)
			->where('semester',$this->semester)
			->whereIn('exam_session',[$this->exam_session,$get_semester_result_single->exam_session])
			->distinct()
			->orderBy('subject_code','ASC')
			->orderBy('back_status','DESC')
			->orderBy('exam_session','DESC')
			->orderBy('back_status_text','ASC');
		if($status==0){
			$results = $results_query->select('enrollment_no','roll_no','course_id','semester','subject_code','oral','internal_marks','external_marks','practical_marks','grade_letter')
				->distinct()->get();
			return $results;
		}
		$results = $results_query->get();
		$sub_code_array = [];
		$result_ids_array = [];
		$i = 0;
		// remove duplicate subject from array
		foreach($results as $key=>$result_row){
			if(in_array($result_row->subject_code,$sub_code_array)==false){
				$sub_code_array[] = $result_row->subject_code;
				$result_ids_array[] = $result_row->id;
				$i++;
			}
		}
		$final_result = Result::select('results.*')
			->whereIn('results.id',$result_ids_array)
			->orderBy('subject_position','ASC')
			->get();
		return $final_result;
	}
	public function get_semester_result_single(){
		$where_array = [
			'roll_no' => $this->roll_no,
			'semester' => $this->semester,
		];
		$result = Result::where($where_array)
			->orderBy('exam_session','DESC')
			->first();
		return $result;
	}

	public function get_session_wise_result(){
		$where_array = [
			'roll_no' => $this->roll_no,
			'semester' => $this->semester,
			'exam_session' => $this->exam_session,
			'back_status_text' => $this->back_status_text,
		];
		$result = Result::where($where_array)
			->orderBy('exam_session','ASC')
			->get();
			return $result;
	}

	public function back_paper_marks(){
		$where_array = [
			'roll_no' => $this->roll_no,
			'semester' => $this->semester,
			'exam_session' => $this->exam_session,
			'back_status_text' => 'BACK',
			'subject_code' => $this->subject_code,
		];
		$result = Result::where($where_array)
			->orderBy('exam_session','ASC')
			->first();
		if($result){
			$back_paper = $result->external_marks;
		}else{
			$back_paper = '-';
		}
		$result = Result::where([
				'roll_no' => $this->roll_no,
				'semester' => $this->semester,
				'back_status_text' => 'REGULAR',
				'subject_code' => $this->subject_code,
			])
			->orderBy('exam_session','ASC')
			->first();
		if(!$result){
			echo 'back paper marks';
			dd($this);
		}
		if($result->external_marks == $back_paper){
			$back_paper = '-';
		}
		$final_data = [
			'internal_marks'=>$result->internal_marks,
			'external_marks'=>$result->external_marks,
			'back_paper'=>$back_paper,
		];
		return $final_data;
	}

	public function get_max_total_marks(){
		return Result::where(
			[
				'roll_no' => $this->roll_no,
				'course_id' => $this->course_id,
				'semester' => $this->semester,
				'exam_session' => $this->exam_session,
			]
		)->sum('max_total_marks');
	}
	public function get_max_total_marks_obtained(){
		return Result::where(
			[
				'roll_no' => $this->roll_no,
				'course_id' => $this->course_id,
				'semester' => $this->semester,
				'exam_session' => $this->exam_session,
			]
		)->sum('total_marks');
	}

	function get_last_semester(){
		return Semester::where('course_id',$this->course_id)
		->orderBy('semester_number','DESC')
		->first();
	}
	function get_last_semesters($session){
		$even_semesters = [2,4,6,8,10];
		if($this->course->semester_type=='year'){
			$even_semesters = [1,2,3,4,5];
		}
		$results = Result::where('roll_no',$this->roll_no)
        ->where('exam_session',$session)
        ->where('course_id',$this->course_id)
        ->whereIn('semester_number',$even_semesters)
        ->where('result_type','new')
        ->where('back_status_text','REGULAR')
		->where('credit','>',0)
        ->orderBy('roll_no')
        ->distinct('roll_no','course_id')
        ->get();
		return $results;
	}
	function get_second_last_semesters($session){
		$even_semesters = [1,3,5,7,9];
		if($this->course->semester_type=='year'){
			$session = previous_session($session);
			$even_semesters = [1,2,3,4,5];
		}
		$results = Result::where('roll_no',$this->roll_no)
        ->where('exam_session',$session)
        ->where('course_id',$this->course_id)
        ->whereIn('semester_number',$even_semesters)
        ->where('result_type','new')
        ->where('back_status_text','REGULAR')
		->where('credit','>',0)
        ->orderBy('roll_no')
        ->distinct('roll_no','course_id')
        ->get();
		return $results;
	}


	public function checkEligibilityForBack($data){

		$data = (object)$data;
		$exam_session = $data->exam_session;
	  	$allowspecialBack = ApprovalSystem::where('roll_number',$data->roll_number)
	  	->where('special_back',$data->back_papers)->first();
		if($allowspecialBack){
			return true;
		}

		$semester_last = Semester::where('course_id',$data->course)->orderBy('semester_number','DESC')->first();
		$checkResult = Result::where('course_id',$data->course)
			->where('semester',$semester_last->id)
			->where('roll_no',$data->roll_number)
			->first();
		if($checkResult){
			return false;
		}

		$semester = Semester::find($data->semester);
		if($semester->semester_number %2 == 0 ){
			$check_semester_id_for_year_back = $semester->id;
		}else{
			$next_sem_id = ($semester->semester_number+1);
			$semester = Semester::where('course_id',$data->course)->where('semester_number',$next_sem_id)->first();
			$check_semester_id_for_year_back = $semester->id;
		}

		$resultData_check = Result::where('course_id',$data->course)
		->where('semester',$check_semester_id_for_year_back)
		->where('exam_session',$exam_session)
		->where('result_type','new')
		->where('year_back',1)
		->where('roll_no',$data->roll_number)
		->first();
		if($resultData_check){
			return false;
		}
		$resultData = Result::where('course_id',$data->course)
		->where('semester',$data->semester)
		->where('exam_session',$exam_session)
		->where('result_type','new')
		->where('result','PCP')
		->where('roll_no',$data->roll_number)
		->first();
		if($resultData){
			return true;
		}else{
			return false;
		}
	}

	public function checkEligibilityForFinalBack($data){
		$data = (object)$data;
		$exam_session = '2021-2022';
		$last_semester = Semester::where('course_id',$data->course)->orderBy('semester_number','DESC')->first();
		$resultData = Result::where('course_id',$data->course)
			->where('semester',$last_semester->id)
			->where('exam_session',$exam_session)
			->where('roll_no',$data->roll_number)
			// ->where('semester_final',1)
			->where('result_overall','FAILED')
			->where('result_type','new')
			->first();
			if($resultData){
				return true;
			}else{
				return false;
			}
	}

	public function studentPhoto($roll_number){
		$student_details = Student::withTrashed()->where('roll_number',$roll_number)->first();
			if($student_details){
				return $student_details;
			}else{
				return false;
			}
	}
	public function examPhoto($roll_number){
		$examData = ExamFee::withTrashed()->where('roll_no',$roll_number)->first();
			if($examData){
				return $examData;
			}else{
				return false;
			}
	}

	public function backResult(){
		$result = Result::find($this->back_id);
		return $result;
	}


	public function special_back_table_details(){
		$special_back_table_details = BackPaper::select('*')
		->where('paper_type',$this->form_type)
		->where('roll_number',$this->roll_no)
		->where('semester_id',$this->semester)
		->where('sub_code',$this->subject_code)
		->where('academic_session',$this->exam_session)
		->first();
		return $special_back_table_details;
	}

	public function getExamStatus(){
		$semester_id = $this->semester;
		$semester = Semester::find($this->semester);
		$nextSem = $semester->getNextSemester();
		if($nextSem){
			$semester_id = $this->semester;
		}
		$exam = ExamFee::where('roll_no',$this->roll_no)
		->where('course_id',$this->course_id)
		->where('semester',$nextSem->id)
		->where('academic_session','2023-2024')
		->where('form_type','regular')
		->first();
		return $exam;
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
	public function mbbs_combined_subject_name($batch){
		return Subject::select('course_id','semester_id','combined_subject_name',DB::raw('count(1) as combined_count'))
            ->where(['course_id'=>$this->course_id,'semester_id'=>$this->semester])
            ->where('combined_subject_name','!=',null)
            ->where('batch',$batch)
            ->groupBy('combined_subject_name')
            ->orderBy('sub_code','asc')
            ->get();
	}

	public function getStatusWiseStudent($form_type,$type=1){
		$examType = ExamType::where('exam_type',$form_type)->first();
		if($examType){
		  $back_status_text = $examType->result_exam_type;
		}else{
		  $back_status_text = null;
		}
		$results_query = Result::select('results.roll_no')
		->join('student_subjects',function($query)use ($form_type){
			$query->on('student_subjects.roll_number','results.roll_no')
			->on('student_subjects.course_id','results.course_id')
			->on('student_subjects.semester_id','results.semester')
			->on('student_subjects.session','results.exam_session')
			->where('student_subjects.type',$form_type);
		  })
		->where('back_status_text',$back_status_text)
		->where('exam_session',$this->exam_session)
		->where('semester',$this->semester)
		->where('results.course_id',$this->course_id)
		->where('status',$this->status)
		->where('result_type','new')
		->distinct('results.roll_no')
		->orderBy('results.roll_no');
		if($type==1){
			$results = $results_query->count();
		}else{
			$results = $results_query->get();
		}
		return $results;
	}
	  
	public function getTrAllStudent($form_type,$type=1){
		$results_query = StudentSubject::select('roll_number','type','session','course_id','semester_id')
		->where('type',$form_type)
		->where('session',$this->exam_session)
		->where('semester_id',$this->semester)
		->where('course_id',$this->course_id)
		->distinct('roll_number')
		->orderBy('roll_number');
		if($type==1){
			$results = $results_query->count();
		}else{
			$results = $results_query->get();
		}
		return $results;
	}

	public function eligible_for_medal(){
		$result_back = Result::where('roll_no',$this->roll_no)
		->where('back_status_text','!=','REGULAR')
		->count();
		$result_failed = Result::where('roll_no',$this->roll_no)
		->whereNotNull('failed_semester_number')
		->count();
		if($result_back > 0 || $result_failed > 0){
			return false;
		}else{
			return true;
		}
	}

	public function mbbs_check_suppementary($results){
		$type = 'Regular';
		foreach($results as $result){
			if($result->scrutiny == 3){
				$type = 'Supplementary';
			}
		}
		return $type;
	}
	  
	function exam_filled_Status($session,$semester_type){
		$results = ExamFee::where('academic_session',$session)
		->join('semesters','semesters.id','exam_fees.semester')
		->where('roll_no',$this->roll_no)
		->where('exam_fees.course_id',$this->course_id)
		->where('form_type','regular')
		->whereNotNull('bank_name');
		if ($semester_type == 'even') {
			$results->whereIn('semester_number', [2, 4, 6, 8, 10, 12]);
		} else if ($semester_type == 'odd') {
			$results->whereIn('semester_number', [1, 3, 5, 7, 9, 11, 13]);
		}
		$results = $results->first();
		return $results;
	}

	function elegible_for_exam($semester_type){
		$semester = Semester::find($this->semester);
		// return  $semester->getNextSemester();
		if($this->new_student==1){
			if($semester_type=='even'){
				return  $semester->getNextSemester();
			}
			return $semester;
		}elseif($this->year_back==1){
			return $semester->getLastSemester();
		}else{
			return  $semester->getNextSemester();
		}
	}

	public function failed_in_semester(){
		$result = Result::where('roll_no',$this->roll_no)
		->where('course_id',$this->course_id)
		->where('semester_number',1)
		->first();
		if($result){
			return $result->failed_semester_number;
		}
		return null;
	}
	public function failed_semester_list_func(){
		$course = Course::find($this->course_id);
		if($course){
			$failed_semester_list_array = explode(',',$this->failed_semester_list);
			$failed_semester_list_array = array_filter($failed_semester_list_array);
			if(count($failed_semester_list_array)<=0){
				return '';
			}else if(count($failed_semester_list_array)>0){
				// return '';
				return ('(BACK IN '.$this->failed_semester_list.' '.strtoupper($course->semester_type).')');
			}
			// else{
			// 	if(count($failed_semester_list_array)==1){
			// 		return ('(BACK IN '.$this->failed_semester_list.' '.strtoupper($course->semester_type).')');
			// 	}else{
			// 		return ('(BACK IN '.$this->failed_semester_list.' '.strtoupper($course->semester_type).'S)');
			// 	}
			// }
		}else{
			return '';
		}
	}

	public function approval_date_latest(){
		$approval_date = Result::select('approval_date')
		->where('roll_no',$this->roll_no)
		->where('semester_number','<=',$this->semester_number)
		->whereNotNull('approval_date')
		->where('status',2)
		->distinct('approval_date')
		->orderBy('approval_date', 'DESC')
		->first();
		if($approval_date){
			return date('d-m-Y', strtotime($approval_date->approval_date));
		}else{
			return null;
		}
	}

}
