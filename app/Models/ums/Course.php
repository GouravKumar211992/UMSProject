<?php

namespace App\Models\ums;
// use Auth;
// use DB;

use App\Models\ums\Semester;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model 
{
	use SoftDeletes;

   
	protected $fillable = [
        'name',
		'total_no_of_seats',
		'course_description',
		'total_semester_number',
		'color_code',
		'campus_id',
		'category_id',
		'created_by',
		'updated_by',
		'semester_type',
		'visible_in_application',
		'required_qualification',
		'course_group',
		'roll_number',
		'entrance_sub_code',
		'course_late_fees',
		'cuet_status',
    ];
    protected $hidden = [
        'deleted_at',
    ];


	public function course_group_data(){
		$course_group = [];
		if($this->course_group==null){
			$course_group = [$this->id];
		}else{
			// dd($this->course_group);
			$course_group = explode(',',$this->course_group);
		}
		return Course::whereIn('id',$course_group)->get();
	}

	public function semesters() {
		return $this->hasMany(Semester::class)->orderBy('semester_number','ASC');
	}
	public function category() {
		return $this->belongsTo(Category::class, 'category_id')->withTrashed();
	}
	public function campuse() {
		return $this->belongsTo(Campuse::class, 'campus_id')->withTrashed();
	}

	public function stream() {
		return $this->hasOne(Stream::class,'course_id', 'id')->withTrashed();
	}
	public function required_qualifications() {
		$required_qualification = explode(',',$this->required_qualification);
		return RequiredQualification::whereIn('id',$required_qualification)->get();
	}

	public function subjects() {
		return $this->belongsToMany(Subject::class,'course_subjects')->withTrashed();
	}
	public function assessments() {
		return $this->hasMany(Assessment::class);
	}
	
	public function getThumbnailAttribute(){

		if($this->getMedia('thumbnail')->isEmpty()) {
			return '';
		}
		else {
			return $this->getMedia('thumbnail')->first()->getFullUrl();
		}
	}
	
	public function registerMediaCollections()
	{
		$this
			->addMediaCollection('thumbnail')
			->singleFile();
	}

	public function hasSubject($subjectId) {
		$courseSubject = CourseSubject::where('course_id', $this->id)
			->where('subject_id', $subjectId)
			->first();

		return $courseSubject ? true : false;
	}
	public function courseMappings() {
		return $this->hasMany(CourseMapping::class, 'course_id');
	}
	public function councelledData($status,$session) {
	    $applications = Application::where('academic_session',$session)
		->where('campuse_id',$this->campus_id)
		->where('course_id',$this->id)
		->whereIn('enrollment_status',[1,2])
		->orderBy('course_id','ASC')
		->get();
		if($status==1){
			return $applications;
		}else{
			return $applications->count();
		}
	}
	public function enrollmentData($status,$session) {
	    $applications = Application::where('academic_session',$session)
		->where('campuse_id',$this->campus_id)
		->where('course_id',$this->id)
		->where('enrollment_status',2)
		->orderBy('roll_number','ASC')
		->get();
		if($status==1){
			return $applications;
		}else{
			return $applications->count();
		}
	}
	public function examFormCount($status,$session) {
		// $status = 0 // for ALL students
		// $status = 1 // for NEW students
		// $status = 2 // for OLD students
	    $examCountQuery = ExamFee::join('semesters','semesters.id','exam_fees.semester')
		->join('courses','courses.id','exam_fees.course_id')
		->where('academic_session',$session)
		->where('exam_fees.course_id',$this->id)
		->where('campus_id',1)
		->where('form_type','regular');
		if($status==1){
			$examCountQuery->where('roll_no','LIKE',substr($session,2,2).'%');
		}else if($status==2){
			$examCountQuery->where('roll_no','NOT LIKE',substr($session,2,2).'%');
		}
		$examCount = $examCountQuery->distinct('roll_no')->count();
		return $examCount;
	}

	function course_wise_students($academic_session){
		$new_students = Enrollment::select('students.*')
		->join('students','students.roll_number','enrollments.roll_number')
		->where('course_id',$this->id)
		->where('students.roll_number', 'LIKE', substr($academic_session,-2) . '%')
		->distinct('roll_no');
		$result_query = Student::select('students.*')
		->join('results','students.roll_number','results.roll_no')
		->where('exam_session', $academic_session)
		->where('results.course_id', $this->id)
		// ->where('disabilty_category', $this->id)
		->where('semester_final', 0)
		->whereIn('semester_number',[2,4,6,8,10])
		->where('result_type', 'new')
		->where('back_status_text', 'REGULAR')
		->distinct('roll_no')
		->union($new_students);
		
		$results_clone = clone $result_query;
		$results = $results_clone->get();
		$all_students_clone = collect($results);
		$male_students_clone = collect($results);
		$within_state_students_clone = collect($results);
		$inside_country_students_clone = collect($results);
		$outside_country_students_clone = collect($results);
		$male_students_clone = collect($results);
		$female_students_clone = collect($results);
		$gen_students_clone = collect($results);
		$obc_students_clone = collect($results);
		$st_students_clone = collect($results);
		$ews_students_clone = collect($results);
		$socially_challenged_students_clone = collect($results);

		$all_students = $all_students_clone->count();
		//dd($all_students);
		$male_students = $male_students_clone->where('gender','MALE')->count();
		
		$female_students = $female_students_clone->where('gender','FEMALE')->count();
		$total_gender = $male_students + $female_students;
		
		$within_state_students = $within_state_students_clone->where('state_union_territory','Uttar Pradesh')->count();
		$inside_country_students = $inside_country_students_clone->where('nationality','=','Indian')->count();
		$outside_country_students = $outside_country_students_clone->where('nationality','!=','Indian')->count();
		$gen_students = $gen_students_clone->where('category','=','General')->count();
		$obc_students = $obc_students_clone->where('category','=','OBC')->count();
		$st_students = $st_students_clone->where('category','=','ST')->count();
		$ews_students = $ews_students_clone->where('ews_status','Yes')->count();
		$socially_challenged_students = $socially_challenged_students_clone->where('category','!=','General')->count();
		$total_category = $gen_students + $obc_students + $st_students + $ews_students + $socially_challenged_students;
		
		return [
			'all_students' => $all_students,
			'male_students' => $male_students,
			'female_students' => $female_students,
			'within_state_students' => $within_state_students,
			'outside_state_students' => ($all_students - $within_state_students) ,
			'inside_country_students' => $inside_country_students,
			'outside_country_students' => $outside_country_students,
			'gen_students' => $gen_students,
			'ews_students' => $ews_students,
			'obc_students' => $obc_students,
			'st_students' => $st_students,
			'socially_challenged_students' => $socially_challenged_students,
			'total_gender'=>$total_gender,
			'total_category'=>$total_category,

		];

	}	

	public function batchPrefixByBatch($batch)
{
    // Example logic to return batch prefixes based on the academic session
    $batchPrefix = [];
    
    switch ($batch) {
        case '2020-2021':
            $batchPrefix = ['2020-2021', '2021-2022'];
            break;
        case '2021-2022':
            $batchPrefix = ['2021-2022', '2022-2023'];
            break;
        case '2022-2023':
            $batchPrefix = ['2022-2023', '2023-2024'];
            break;
        default:
            $batchPrefix = ['2013-2014', '2014-2015', '2015-2016'];
            break;
    }
    
    return $batchPrefix;
}
	function studying_course_wise_students($batch){
		// if($batch!='All'){
		// 	$batchPrefixByBatch = batchPrefixByBatch($batch);
		// }
		// $result_query = Student::select('students.*')
		// ->join('enrollments','students.roll_number','enrollments.roll_number')
		// ->where('course_id',$this->id)
		// ->where('is_student_studing','Yes');
		// if($batch!='All'){
		// 	$result_query->where('students.roll_number', 'LIKE', $batchPrefixByBatch . '%');
		// }
		// $result_query->distinct('roll_no');

		// $results_clone = clone $result_query;
		// $results = $results_clone->get();
		// $all_students_clone = collect($results);
		// $male_students_clone = collect($results);
		// $female_students_clone = collect($results);
		// $gen_students_clone = collect($results);
		// $obc_students_clone = collect($results);
		// $st_students_clone = collect($results);
		// $sc_students_clone = collect($results);
		// $ews_students_clone = collect($results);
		// $vi_students_clone = collect($results);
		// $hi_students_clone = collect($results);
		// $ld_oh_ph_students_clone = collect($results);
		// $others_students_clone = collect($results);

		
    if($batch != 'All'){
        $batchPrefixByBatch = $this->batchPrefixByBatch($batch);  // Using the batchPrefixByBatch method
    }

    $result_query = Student::select('students.*')
        ->join('enrollments','students.roll_number','enrollments.roll_number')
        ->where('course_id',$this->id)
        ->where('is_student_studing','Yes');

    if($batch != 'All'){
        $result_query->where('students.roll_number', 'LIKE', implode(',', $batchPrefixByBatch) . '%');
    }

    $result_query->distinct('roll_no');
    $results_clone = clone $result_query;
    $results = $results_clone->get();
    
    // You can clone the result as needed
    $all_students_clone = collect($results);
    $male_students_clone = collect($results);
    $female_students_clone = collect($results);
    $gen_students_clone = collect($results);
    $obc_students_clone = collect($results);
    $st_students_clone = collect($results);
    $sc_students_clone = collect($results);
    $ews_students_clone = collect($results);
    $vi_students_clone = collect($results);
    $hi_students_clone = collect($results);
    $ld_oh_ph_students_clone = collect($results);
    $others_students_clone = collect($results);

   // dd($vi_students_clone);
		$all_students = $all_students_clone->count();
		
		$male_students_nonPwd = $male_students_clone->where('gender','MALE')->whereIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$female_students_nonPwd = $female_students_clone->where('gender','FEMALE')->whereIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$gen_nonPwd = $gen_students_clone->whereIn('category',['General','EWS'])->whereIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$obc_nonPwd = $obc_students_clone->where('category','=','OBC')->whereIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$st_nonPwd = $st_students_clone->where('category','=','ST')->whereIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$sc_nonPwd = $st_students_clone->where('category','=','SC')->whereIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		

		$ews_gen_nonPwd = $ews_students_clone->where('ews_status','Yes')->where('category','General')->whereIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$ews_obs_students_nonPwd = $ews_students_clone->where('ews_status','Yes')->where('category','OBC')->whereIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$ews_sc_students_nonPwd = $ews_students_clone->where('ews_status','Yes')->where('category','SC')->whereIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$ews_st_students_nonPwd = $ews_students_clone->where('ews_status','Yes')->where('category','ST')->whereIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		// $gen_students_nonPwd = ($gen_students_clone->whereIn('category',['General','EWS'])->whereIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count()-$ews_students_nonPwd);

		$gen_students_nonPwd = $gen_nonPwd - $ews_gen_nonPwd;

		 $obc_students_nonPwd = $obc_nonPwd - $ews_obs_students_nonPwd; 

		 $st_students_nonPwd = $st_nonPwd - $ews_st_students_nonPwd;

		 $sc_students_nonPwd = $sc_nonPwd - $ews_sc_students_nonPwd ;

		 $ews_students_nonPwd = $ews_gen_nonPwd + $ews_obs_students_nonPwd + $ews_st_students_nonPwd + $ews_sc_students_nonPwd ;


		$male_students_Pwd = $male_students_clone->where('gender','MALE')->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();
		$female_students_Pwd = $female_students_clone->where('gender','FEMALE')->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$obc_Pwd = $obc_students_clone->where('category','=','OBC')->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$st_Pwd = $st_students_clone->where('category','=','ST')->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$sc_Pwd = $sc_students_clone->where('category','=','SC')->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		// $ews_students_Pwd = $ews_students_clone->where('ews_status','Yes')->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$gen_Pwd = $gen_students_clone->whereIn('category',['General','EWS'])
		->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();


		$ews_gen_Pwd = $ews_students_clone->where('ews_status','Yes')->where('category','General')->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$ews_obs_Pwd = $ews_students_clone->where('ews_status','Yes')->where('category','OBC')->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$ews_sc_Pwd = $ews_students_clone->where('ews_status','Yes')->where('category','SC')->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$ews_st_Pwd = $ews_students_clone->where('ews_status','Yes')->where('category','ST')->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$obc_students_Pwd = $obc_Pwd - $ews_obs_Pwd;

		$st_students_Pwd =  $st_Pwd - $ews_st_Pwd;

		$sc_students_Pwd = $sc_Pwd - $ews_sc_Pwd;

		$gen_students_Pwd =  $gen_Pwd - $ews_gen_Pwd;

		$ews_students_Pwd =  $ews_obs_Pwd + $ews_st_Pwd + $ews_sc_Pwd + $ews_gen_Pwd ;
		
		$vi_students = $vi_students_clone->whereIn('disabilty_category', ['VI','Blindness','Blindness-BL'])->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])
		->count();
		$hi_students = $hi_students_clone
          ->whereIn('disabilty_category', ['HI', 'Hard of Hearing', 'HH','Deaf','DF','Deaf-DF']) 
          ->whereNotIn('disabilty_category', ['Not Applicable', 'Not Any', 'NA', 'Null', '', null])
          ->count();
		$ld_oh_ph_students = $ld_oh_ph_students_clone->whereIn('disabilty_category', ['Locomotor Disability','	
		OH','LD'])->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])
		->count();
		$others_students = $others_students_clone->whereNotIn('disabilty_category', ['Locomotor Disability','	
		OH','LD','HI','Hard of Hearing','HH','VI','Deaf','DF','Deaf-DF','Blindness','Blindness-BL','Not Applicable', 'Not Any', 'NA', 'Null', '', null])
		->count();

		$total_disable_students = $vi_students + $hi_students  + $ld_oh_ph_students + $others_students;

		
		return [
			'all_students' => $all_students,
			'male_students_nonPwd' => $male_students_nonPwd,
			'female_students_nonPwd' => $female_students_nonPwd,
			'gen_students_nonPwd' => $gen_students_nonPwd,
			'ews_students_nonPwd' => $ews_students_nonPwd,
			'obc_students_nonPwd' => $obc_students_nonPwd,
			'st_students_nonPwd' => $st_students_nonPwd,
			'sc_students_nonPwd' => $sc_students_nonPwd,
			'male_students_Pwd' => $male_students_Pwd,
			'female_students_Pwd' => $female_students_Pwd,
			'obc_students_Pwd' => $obc_students_Pwd,
			'st_students_Pwd' => $st_students_Pwd,
			'sc_students_Pwd' => $sc_students_Pwd,
			'ews_students_Pwd' => $ews_students_Pwd,
			'gen_students_Pwd' => $gen_students_Pwd,
			'vi_students' => $vi_students,
			'hi_students' => $hi_students,
			'ld_oh_ph_students' => $ld_oh_ph_students,
			'others_students' => $others_students,
			'total_disable_students' => $total_disable_students
		];

	}

//update this funtion//

	function all_course_wise_students($academic_session, $type) {
		// Define the batchPrefixByBatch function inside your main function
		$batchPrefixByBatch = [];
		
		// Example logic to return batch prefixes based on the academic session
		switch ($academic_session) {
			case '2020-2021':
				$batchPrefixByBatch = ['2020-2021', '2021-2022'];
				break;
			case '2021-2022':
				$batchPrefixByBatch = ['2021-2022', '2022-2023'];
				break;
			case '2022-2023':
				$batchPrefixByBatch = ['2022-2023', '2023-2024'];
				break;
			default:
				$batchPrefixByBatch = ['2013-2014', '2014-2015', '2015-2016'];
				break;
		}
	
		// Use the batchPrefixByBatch logic in your query
		$results = Result::where('back_status_text', 'REGULAR')
			->where('roll_no', 'NOT LIKE', implode(',', $batchPrefixByBatch) . '%') // Implode to use the array
			->where('semester_final', 1)
			->where('course_id', $this->id)
			->where('exam_session', $academic_session)
			->where('result_overall', 'PASS')
			->distinct('semester')
			->pluck('roll_no')
			->toArray();
	
		$result_query = Student::select('students.*')
			->join('enrollments', 'students.roll_number', 'enrollments.roll_number')
			->whereNotIn('students.roll_number', $results)
			->where('course_id', $this->id);
	
		if ($type == 'old') {
			$exam_roll_no_array = ExamFee::join('semesters', 'semesters.id', 'exam_fees.semester')
				->where('academic_session', $academic_session)
				->where('exam_fees.course_id', $this->id)
				->where('semester_number', '>', 1)
				->where('roll_no', 'NOT LIKE', implode(',', $batchPrefixByBatch) . '%') // Implode to use the array
				->where('form_type', 'regular')
				->distinct('semester')
				->pluck('roll_no')
				->toArray();
	
			// Apply the condition for "old" type
			$result_query->whereIn('students.roll_number', $exam_roll_no_array);
		} else {
			// Apply the condition for non-"old" type
			$result_query->where('students.roll_number', 'LIKE', implode(',', $batchPrefixByBatch) . '%');
		}
	
		// Final query for distinct roll numbers
		$result_query->distinct('roll_no');
	
		// Continue your logic...
	

		$results_clone = clone $result_query;
		$results = $results_clone->get();
		$all_students_clone = collect($results);
		$male_students_clone = collect($results);
		$female_students_clone = collect($results);
		$gen_students_clone = collect($results);
		$obc_students_clone = collect($results);
		$st_students_clone = collect($results);
		$sc_students_clone = collect($results);
		$ews_students_clone = collect($results);
		$vi_students_clone = collect($results);
		$hi_students_clone = collect($results);
		$ld_oh_ph_students_clone = collect($results);
		$others_students_clone = collect($results);
		$all_students = $all_students_clone->count();
		
		$male_students_nonPwd = $male_students_clone->where('gender','MALE')->whereIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$female_students_nonPwd = $female_students_clone->where('gender','FEMALE')->whereIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$gen_nonPwd = $gen_students_clone->whereIn('category',['General','EWS'])->whereIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$obc_nonPwd = $obc_students_clone->where('category','=','OBC')->whereIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$st_nonPwd = $st_students_clone->where('category','=','ST')->whereIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$sc_nonPwd = $st_students_clone->where('category','=','SC')->whereIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		

		$ews_gen_nonPwd = $ews_students_clone->where('ews_status','Yes')->where('category','General')->whereIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$ews_obs_students_nonPwd = $ews_students_clone->where('ews_status','Yes')->where('category','OBC')->whereIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$ews_sc_students_nonPwd = $ews_students_clone->where('ews_status','Yes')->where('category','SC')->whereIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$ews_st_students_nonPwd = $ews_students_clone->where('ews_status','Yes')->where('category','ST')->whereIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		// $gen_students_nonPwd = ($gen_students_clone->whereIn('category',['General','EWS'])->whereIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count()-$ews_students_nonPwd);

		$gen_students_nonPwd = $gen_nonPwd - $ews_gen_nonPwd;

		 $obc_students_nonPwd = $obc_nonPwd - $ews_obs_students_nonPwd; 

		 $st_students_nonPwd = $st_nonPwd - $ews_st_students_nonPwd;

		 $sc_students_nonPwd = $sc_nonPwd - $ews_sc_students_nonPwd ;

		 $ews_students_nonPwd = $ews_gen_nonPwd + $ews_obs_students_nonPwd + $ews_st_students_nonPwd + $ews_sc_students_nonPwd ;


		$male_students_Pwd = $male_students_clone->where('gender','MALE')->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();
		$female_students_Pwd = $female_students_clone->where('gender','FEMALE')->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$obc_Pwd = $obc_students_clone->where('category','=','OBC')->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$st_Pwd = $st_students_clone->where('category','=','ST')->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$sc_Pwd = $sc_students_clone->where('category','=','SC')->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		// $ews_students_Pwd = $ews_students_clone->where('ews_status','Yes')->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$gen_Pwd = $gen_students_clone->whereIn('category',['General','EWS'])
		->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();


		$ews_gen_Pwd = $ews_students_clone->where('ews_status','Yes')->where('category','General')->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$ews_obs_Pwd = $ews_students_clone->where('ews_status','Yes')->where('category','OBC')->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$ews_sc_Pwd = $ews_students_clone->where('ews_status','Yes')->where('category','SC')->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$ews_st_Pwd = $ews_students_clone->where('ews_status','Yes')->where('category','ST')->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])->count();

		$obc_students_Pwd = $obc_Pwd - $ews_obs_Pwd;

		$st_students_Pwd =  $st_Pwd - $ews_st_Pwd;

		$sc_students_Pwd = $sc_Pwd - $ews_sc_Pwd;

		$gen_students_Pwd =  $gen_Pwd - $ews_gen_Pwd;

		$ews_students_Pwd =  $ews_obs_Pwd + $ews_st_Pwd + $ews_sc_Pwd + $ews_gen_Pwd ;
		
		$vi_students = $vi_students_clone->whereIn('disabilty_category', ['VI','Blindness','Blindness-BL'])->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])
		->count();
		$hi_students = $hi_students_clone
          ->whereIn('disabilty_category', ['HI', 'Hard of Hearing', 'HH','Deaf','DF','Deaf-DF']) 
          ->whereNotIn('disabilty_category', ['Not Applicable', 'Not Any', 'NA', 'Null', '', null])
          ->count();
		$ld_oh_ph_students = $ld_oh_ph_students_clone->whereIn('disabilty_category', ['Locomotor Disability','	
		OH','LD'])->whereNotIn('disabilty_category',['Not Applicable','Not Any','NA','Null','',null])
		->count();
		$others_students = $others_students_clone->whereNotIn('disabilty_category', ['Locomotor Disability','	
		OH','LD','HI','Hard of Hearing','HH','VI','Deaf','DF','Deaf-DF','Blindness','Blindness-BL','Not Applicable', 'Not Any', 'NA', 'Null', '', null])
		->count();

		$total_disable_students = $vi_students + $hi_students  + $ld_oh_ph_students + $others_students;

		
		return [
			'all_students' => $all_students,
			'male_students_nonPwd' => $male_students_nonPwd,
			'female_students_nonPwd' => $female_students_nonPwd,
			'gen_students_nonPwd' => $gen_students_nonPwd,
			'ews_students_nonPwd' => $ews_students_nonPwd,
			'obc_students_nonPwd' => $obc_students_nonPwd,
			'st_students_nonPwd' => $st_students_nonPwd,
			'sc_students_nonPwd' => $sc_students_nonPwd,
			'male_students_Pwd' => $male_students_Pwd,
			'female_students_Pwd' => $female_students_Pwd,
			'obc_students_Pwd' => $obc_students_Pwd,
			'st_students_Pwd' => $st_students_Pwd,
			'sc_students_Pwd' => $sc_students_Pwd,
			'ews_students_Pwd' => $ews_students_Pwd,
			'gen_students_Pwd' => $gen_students_Pwd,
			'vi_students' => $vi_students,
			'hi_students' => $hi_students,
			'ld_oh_ph_students' => $ld_oh_ph_students,
			'others_students' => $others_students,
			'total_disable_students' => $total_disable_students
		];


	}

	public function getRegularExamData($session,$type='LAST'){
		// $type = 'FIRST';
		// $type = 'ALL';
		// $type = 'LAST';
		$semester = Semester::where('course_id',$this->id)
		->orderBy('semester_number','DESC')
		->first();
		if($semester){
			$exam = ExamFee::where('academic_session',$session)
			->where('semester',$semester->id)
			->where('form_type','regular')
			->whereNotNull('bank_name')
			->pluck('roll_no')
			->toArray();
			$result = Result::select('roll_no')
			->where('exam_session',$session)
			->where('semester',$semester->id)
			->where('back_status_text','regular')
			->where('result_overall','PASS')
			->distinct('roll_no')
			->get();
			$data = [
				'exam_count' => count($exam),
				'result_count' => $result->count(),
			];
			return (object)$data;
		}
		else{
			$data = [
				'exam_count' => 0,
				'result_count' => 0,
			];
			return (object)$data;
		}
	}
	  
	
}
