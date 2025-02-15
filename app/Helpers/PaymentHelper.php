<?php

use App\Models\ums\Course;
use App\Models\ums\Enrollment;
use Illuminate\Support\Facades\DB;





if(!function_exists('paymentApi')){
	function paymentApi(){
		return 'paymentApi';
	}
}

if(!function_exists('createEnrollment')){
	function createEnrollment($campuse_id,$course_id){
        $course = Course::find($course_id);
		$campuse_code_data = DB::table('campuses')->whereId($campuse_id)->first();
		if($campuse_code_data){
			$campuse_code = ''.$campuse_code_data->campus_code.'';
		}else{
			$campuse_code = '000';
		}
		//$stream_code = DB::table('streams')->where('course_id',$course_id)->first()->stream_code;
		$check_serial_no = 'DSMNRU'.addmission_year().sprintf('%03d', $campuse_code).sprintf('%02d', $course->color_code);
		if($campuse_id!=1){
			$check_serial_no = 'DSMNRU'.addmission_year().'A'.sprintf('%03d', $campuse_code).sprintf('%02d', $course->color_code);
		}
		$serial_no = get_serial_no($check_serial_no,'enrollment');
		$enrollment_no = $check_serial_no.sprintf('%03d', $serial_no);
		return $enrollment_no;
	}
}

if(!function_exists('createRollNo')){
	function createRollNo($campuse_id,$course_id){
        $course = Course::find($course_id);
		$campuse_code_data = DB::table('campuses')->whereId($campuse_id)->first();
		if($campuse_code_data){
			$campuse_code = $campuse_code_data->campus_code;
		}else{
			$campuse_code = '000';
		}
		$faculty_code  = '0';
		$course_code  = '00';
		if($course){
			$faculty_code  = $course->faculty_code;
			$course_code  = $course->color_code;
		}
		if($campuse_id==1){
			$check_serial_no = addmission_year().$faculty_code.sprintf('%02d', $course_code);
			$serial_no = get_serial_no($check_serial_no,'roll_number');
			$roll_no = $check_serial_no.sprintf('%04d', $serial_no);
		}else{
			$check_serial_no = addmission_year().sprintf('%03d', $campuse_code).sprintf('%02d', $course_code);
			$serial_no = get_serial_no($check_serial_no,'roll_number');
			$roll_no = $check_serial_no.sprintf('%03d', $serial_no);
		}
		return $roll_no;
	}
}

if(!function_exists('get_serial_no')){
	function get_serial_no($check_serial_no,$type){
		if($type=='enrollment'){
			$enrollment = Enrollment::where('enrollment_no','like',$check_serial_no.'%')->orderby('id','desc')->first();
			$serial_no = 1;
			if($enrollment){
				$serial_no = (int)substr($enrollment->enrollment_no,-3,3);
				$serial_no = $serial_no + 1;
			}
		}
		if($type=='roll_number'){
			$enrollment = Enrollment::where('roll_number','like',$check_serial_no.'%')->orderby('id','desc')->first();
			$serial_no = 1;
			if($enrollment){
				$serial_no = (int)substr($enrollment->roll_number,-3,3);
				$serial_no = $serial_no + 1;
			}
		}
		return $serial_no;
	}
}

if(!function_exists('addmission_year')){
	function addmission_year(){
		return 23;
	}
}


if(!function_exists('current_session')){
	function current_session(){
		if (date('m') > 6) {
			$year = date('F').'-'.date('Y');
		} else {
			$year = date('F').'-'.(date('Y') - 1);
		}
		return $year;
	}
}

if(!function_exists('accademic_session')){
	function accademic_session(){
		if (date('m') > 6) {
			$year = date('Y').'-'.(date('Y')+1);
		} else {
			$year = (date('Y')-1).'-'.date('Y');
		}
		return $year;
	}
}
if(!function_exists('current_exam_session')){
	function current_exam_session(){
		return 'DEC-2023';
	}
}
