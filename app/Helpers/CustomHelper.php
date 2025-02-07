<?php
<<<<<<< HEAD

use App\Helpers;
use App\models\ums\Result;



use Illuminate\Support\Facades\Auth;


class HelpeFunctions
{

  public static function numberFormat($number)
  {
    if (!function_exists('numberFormat')) {
      function numberFormat($number)
      {
        if (is_numeric($number) == false) {
          return '';
        }
      }

      $no = floor($number);
      $point = round($number - $no, 2) * 100;
      $hundred = null;
      $digits_1 = strlen($no);
      $i = 0;
      $str = array();
      $words = array(
        '0' => '',
        '1' => 'one',
        '2' => 'two',
        '3' => 'three',
        '4' => 'four',
        '5' => 'five',
        '6' => 'six',
        '7' => 'seven',
        '8' => 'eight',
        '9' => 'nine',
        '10' => 'ten',
        '11' => 'eleven',
        '12' => 'twelve',
        '13' => 'thirteen',
        '14' => 'fourteen',
        '15' => 'fifteen',
        '16' => 'sixteen',
        '17' => 'seventeen',
        '18' => 'eighteen',
        '19' => 'nineteen',
        '20' => 'twenty',
        '30' => 'thirty',
        '40' => 'forty',
        '50' => 'fifty',
        '60' => 'sixty',
        '70' => 'seventy',
        '80' => 'eighty',
        '90' => 'ninety'
      );
      $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
      while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
          $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
          $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
          $str[] = ($number < 21) ? $words[$number] .
=======
namespace App\Helpers;
use App\Models\Result;
class CustomHelper{
function sdfg(){
if(!function_exists('numberFormat')){
	function numberFormat($number){
    if(is_numeric($number)==false){
      return '';
    }

    $no = floor($number);
    $point = round($number - $no, 2) * 100;
   $hundred = null;
   $digits_1 = strlen($no);
   $i = 0;
   $str = array();
   $words = array('0' => '', '1' => 'one', '2' => 'two',
    '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
    '7' => 'seven', '8' => 'eight', '9' => 'nine',
    '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
    '13' => 'thirteen', '14' => 'fourteen',
    '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
    '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
    '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
    '60' => 'sixty', '70' => 'seventy',
    '80' => 'eighty', '90' => 'ninety');
   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
   while ($i < $digits_1) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($no % $divider);
     $no = floor($no / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {
        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
        $str [] = ($number < 21) ? $words[$number] .
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
<<<<<<< HEAD
        } else $str[] = null;
      }
      $str = array_reverse($str);
      $result = implode('', $str);
      $points = ($point) ?
        "." . $words[$point / 10] . " " .
        $words[$point = $point % 10] : '';
      echo ucwords($result);
    }
  }


  function campus_name($enrollment)
  {
    $enrollment_format = $enrollment;
    $SA = affiliated($enrollment_format, 'SA'); //returns true
    $DSMNRU21A = affiliated($enrollment_format, 'DSMNRU21A'); //returns true

    if ($SA) {
      $collage_code = substr($enrollment_format, 4, 3);
      //dd($collage_code);
    } elseif ($DSMNRU21A) {
      $collage_code = substr($enrollment_format, 9, 3);
    } else {
      $collage_code = 000;
    }
    $collage = App\Models\ums\Campuse::where('campus_code', $collage_code)->first();
    return $collage['id'];
  }
}

function campus_name_text($enrollment)
{
  $enrollment_format = $enrollment;
  $SA = affiliated($enrollment_format, 'SA'); //returns true
  $DSMNRU21A = affiliated($enrollment_format, 'DSMNRU21A'); //returns true

  if ($SA) {
    $collage_code = substr($enrollment_format, 4, 3);
    //dd($collage_code);
  } elseif ($DSMNRU21A) {
    $collage_code = substr($enrollment_format, 9, 3);
  } else {
    $collage_code = 000;
  }
  $collage = App\Models\ums\Campuse::where('campus_code', $collage_code)->first();
  return $collage['name'];
}

function affiliated($str, $searchTerm)
{
  $searchTerm = strtolower($searchTerm);
  $str = strtolower($str);
  $pos = strpos($str, $searchTerm);
  if ($pos === false)
    return false;
  else
    return true;
}

function grade_old_allowed_semester($semester_id, $academic_session)
{
  // $array_semesters = ['35','45','46','67','68','83','85','91','93','45','101','107','109','115','117','203','205','207','217','231','515'];
  $array_semesters = \App\Models\ums\GradeOldAllowedSemester::where('academic_session', $academic_session)
=======
     } else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);
  $points = ($point) ?
    "." . $words[$point / 10] . " " . 
          $words[$point = $point % 10] : '';
  echo ucwords($result) ;
	}
}
}
function campus_name($enrollment){
  $enrollment_format=$enrollment;
  $SA = affiliated($enrollment_format, 'SA'); //returns true
$DSMNRU21A = affiliated($enrollment_format, 'DSMNRU21A'); //returns true

if($SA){
  $collage_code=substr($enrollment_format,4,3);
  //dd($collage_code);
}
elseif($DSMNRU21A){
  $collage_code=substr($enrollment_format,9,3);
}
 else{
  $collage_code=000;
 }
  $collage=App\Models\Campuse::where('campus_code',$collage_code)->first();
  return $collage['id'];
  }

function campus_name_text($enrollment){
  $enrollment_format=$enrollment;
  $SA = affiliated($enrollment_format, 'SA'); //returns true
$DSMNRU21A = affiliated($enrollment_format, 'DSMNRU21A'); //returns true

if($SA){
  $collage_code=substr($enrollment_format,4,3);
  //dd($collage_code);
}
elseif($DSMNRU21A){
  $collage_code=substr($enrollment_format,9,3);
}
 else{
  $collage_code=000;
 }
  $collage=App\Models\Campuse::where('campus_code',$collage_code)->first();
  return $collage['name'];
  }

  function affiliated($str, $searchTerm) {
    $searchTerm = strtolower($searchTerm);
    $str = strtolower($str);
    $pos = strpos($str, $searchTerm);
    if ($pos === false)
        return false;
    else
        return true;
}

function grade_old_allowed_semester($semester_id,$academic_session) {
  // $array_semesters = ['35','45','46','67','68','83','85','91','93','45','101','107','109','115','117','203','205','207','217','231','515'];
  $array_semesters = \App\Models\GradeOldAllowedSemester::where('academic_session',$academic_session)
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
    ->pluck('semester_id')
    ->toArray();
  return in_array($semester_id, $array_semesters);
}

<<<<<<< HEAD
function checkAdminRoll()
{
  $user = Auth::guard('admin')->user();
  // dd($user);
  if (!$user) {
=======
function checkAdminRoll() {
  $user = Auth::guard('admin')->user();
  // dd($user);
  if(!$user){
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
    return false;
  }
  $role_id = $user->role;
  $checkRollWise = ['2'];
<<<<<<< HEAD
  if (in_array($role_id, $checkRollWise)) {
    return false;
  } else {
=======
  if(in_array($role_id,$checkRollWise)){
    return false;
  }else{
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
    return true;
  }
}

<<<<<<< HEAD
function batchPrefixByBatch($batch)
{
  return substr($batch, 2, 2);
}
function batchFunction($roll_no)
{
  $student = App\Models\ums\Student::where('roll_number', $roll_no)->first();
  $match_rollno = substr($student->roll_number, 0, 2);
  if ($match_rollno == '16') {
    echo '2016-2017';
  } elseif ($match_rollno == 17) {
    echo '2017-2018';
  } elseif ($match_rollno == 18) {
    echo '2018-2019';
  } elseif ($match_rollno == 19) {
    echo '2019-2020';
  } elseif ($match_rollno == 20) {
    echo '2020-2021';
  } elseif ($match_rollno == 21) {
    echo '2021-2022';
  } elseif ($match_rollno == 22) {
    echo '2022-2023';
  } elseif ($match_rollno == 23) {
    echo '2023-2024';
  } elseif ($match_rollno == 24) {
    echo '2024-2025';
  }
}
function batchFunctionReturn($roll_no)
{
  $match_rollno = substr($roll_no, 0, 2);
  if ($match_rollno == '16') {
    return '2016-2017';
  } elseif ($match_rollno == 17) {
    return '2017-2018';
  } elseif ($match_rollno == 18) {
    return '2018-2019';
  } elseif ($match_rollno == 19) {
    return '2019-2020';
  } elseif ($match_rollno == 20) {
    return '2020-2021';
  } elseif ($match_rollno == 21) {
    return '2021-2022';
  } elseif ($match_rollno == 22) {
    return '2022-2023';
  } elseif ($match_rollno == 23) {
    return '2023-2024';
  } elseif ($match_rollno == 24) {
    return '2024-2025';
  }
}
function batchFunctionMbbs($roll_no)
{
  $student = App\Models\ums\Student::where('roll_number', $roll_no)->first();
  $match_rollno = substr($student->roll_number, 0, 2);
  if ($match_rollno == '16') {
    return '2016-2017';
  } elseif ($match_rollno == 17) {
    return '2017-2018';
  } elseif ($match_rollno == 18) {
    return '2018-2019';
  } elseif ($match_rollno == 19) {
    return '2019-2020';
  } elseif ($match_rollno == 20) {
    return '2020-2021';
  } elseif ($match_rollno == 21) {
    return '2021-2022';
  } elseif ($match_rollno == 22) {
    return '2022-2023';
  } elseif ($match_rollno == 23) {
    return '2023-2024';
  } elseif ($match_rollno == 24) {
    return '2024-2025';
  }
}

function batchArray()
{
=======
function batchPrefixByBatch($batch) {
    return substr($batch,2,2);
}
function batchFunction($roll_no) {
  $student = App\Models\Student::where('roll_number',$roll_no)->first();
    $match_rollno = substr($student->roll_number,0,2);
    if($match_rollno == '16'){
      echo '2016-2017';
    }elseif($match_rollno == 17){
      echo '2017-2018';
    }elseif($match_rollno == 18){
      echo '2018-2019';
    }elseif($match_rollno == 19){
      echo '2019-2020';
    }elseif($match_rollno == 20){
      echo '2020-2021';
    }elseif($match_rollno == 21){
      echo '2021-2022';
    }elseif($match_rollno == 22){
      echo '2022-2023';
    }elseif($match_rollno == 23){
      echo '2023-2024';
    }elseif($match_rollno == 24){
      echo '2024-2025';
    }
}
function batchFunctionReturn($roll_no) {
    $match_rollno = substr($roll_no,0,2);
    if($match_rollno == '16'){
      return '2016-2017';
    }elseif($match_rollno == 17){
      return '2017-2018';
    }elseif($match_rollno == 18){
      return '2018-2019';
    }elseif($match_rollno == 19){
      return '2019-2020';
    }elseif($match_rollno == 20){
      return '2020-2021';
    }elseif($match_rollno == 21){
      return '2021-2022';
    }elseif($match_rollno == 22){
      return '2022-2023';
    }elseif($match_rollno == 23){
      return '2023-2024';
    }elseif($match_rollno == 24){
      return '2024-2025';
    }
}
function batchFunctionMbbs($roll_no) {
  $student = App\Models\Student::where('roll_number',$roll_no)->first();
    $match_rollno = substr($student->roll_number,0,2);
    if($match_rollno == '16'){
      return '2016-2017';
    }elseif($match_rollno == 17){
    return '2017-2018';
    }elseif($match_rollno == 18){
      return '2018-2019';
    }elseif($match_rollno == 19){
      return '2019-2020';
    }elseif($match_rollno == 20){
      return '2020-2021';
    }elseif($match_rollno == 21){
      return '2021-2022';
    }elseif($match_rollno == 22){
      return '2022-2023';
    }elseif($match_rollno == 23){
      return '2023-2024';
    }elseif($match_rollno == 24){
      return '2024-2025';
    }
}

function batchArray(){
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
  return [
    '2023-2024AUG',
    '2023-2024JUL',
    '2023-2024',
    '2022-2023',
    '2021-2022',
    '2020-2021',
    '2019-2020',
    '2018-2019',
    '2017-2018',
    '2016-2017',
    '2015-2016',
    '2014-2015',
  ];
<<<<<<< HEAD
}


function allowRegularStudents($roll_number)
{
  $allow_regular_students = ['221050018'];
  return in_array($roll_number, $allow_regular_students);
}
function allowRegularStudentsMbbs($roll_number, $form_type)
{
  $permission = false;
  if ($form_type == 'regular') {
    $allow_regular_students = \App\Models\ums\StudentAllFromOldAgency::where('roll_no', $roll_number)
      ->where('regular_permission', 'Allowed')
      ->first();
    if ($allow_regular_students) {
      $permission = true;
    }
  }
  if ($form_type == 'compartment') {
    $allow_regular_students = \App\Models\ums\StudentAllFromOldAgency::where('roll_no', $roll_number)
      ->where('supplementary_permission', 'Allowed')
      ->first();
    if ($allow_regular_students) {
      $permission = true;
    }
  }
  if ($form_type == 'scrutiny') {
    $allow_regular_students = \App\Models\ums\StudentAllFromOldAgency::where('roll_no', $roll_number)
      ->where('scrutiny_permission', 'Allowed')
      ->first();
    if ($allow_regular_students) {
      $permission = true;
    }
  }
  if ($form_type == 'challenge') {
    $allow_regular_students = \App\Models\ums\StudentAllFromOldAgency::where('roll_no', $roll_number)
      ->where('challenge_permission', 'Allowed')
      ->first();
    if ($allow_regular_students) {
=======

}


function allowRegularStudents($roll_number) {
  $allow_regular_students = ['221050018'];
  return in_array($roll_number, $allow_regular_students);
  
}
function allowRegularStudentsMbbs($roll_number, $form_type) {
  $permission = false;
  if($form_type=='regular'){
    $allow_regular_students = \App\Models\StudentAllFromOldAgency::where('roll_no',$roll_number)
    ->where('regular_permission','Allowed')
    ->first();
    if($allow_regular_students){
      $permission = true;
    }
  }
  if($form_type=='compartment'){
    $allow_regular_students = \App\Models\StudentAllFromOldAgency::where('roll_no',$roll_number)
    ->where('supplementary_permission','Allowed')
    ->first();
    if($allow_regular_students){
      $permission = true;
    }
  }
  if($form_type=='scrutiny'){
    $allow_regular_students = \App\Models\StudentAllFromOldAgency::where('roll_no',$roll_number)
    ->where('scrutiny_permission','Allowed')
    ->first();
    if($allow_regular_students){
      $permission = true;
    }
  }
  if($form_type=='challenge'){
    $allow_regular_students = \App\Models\StudentAllFromOldAgency::where('roll_no',$roll_number)
    ->where('challenge_permission','Allowed')
    ->first();
    if($allow_regular_students){
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
      $permission = true;
    }
  }
  return $permission;
}

<<<<<<< HEAD
function getStudentCourseDurationEligibility($roll_no, $course_id)
{
  // getting the enrollment year
  $match_rollno = substr($roll_no, 0, 2);
  $enrollment_year = '20' . $match_rollno;
  $current_year = date('Y');
  // getting course data for maximum course duration
  $course = \App\Models\ums\Course::find($course_id);
  if (!$course || $course->course_duration == null || $course->max_course_duration == null) {
    dd('Invalid Course ID or Course Duration is null', $course->name);
  }
  // checking the conditions that student course maximum duration is expired or not
  $student_course_duration = ($current_year - $enrollment_year);
  $max_course_duration = $course->max_course_duration;
  if ($student_course_duration <= $max_course_duration) {
    return true;
  } else {
=======
function getStudentCourseDurationEligibility($roll_no,$course_id){
  // getting the enrollment year
  $match_rollno = substr($roll_no,0,2);
  $enrollment_year = '20'.$match_rollno;
  $current_year = date('Y');
  // getting course data for maximum course duration
  $course = \App\Models\Course::find($course_id);
  if(!$course || $course->course_duration==null || $course->max_course_duration==null){
    dd('Invalid Course ID or Course Duration is null',$course->name);
  }
  // checking the conditions that student course maximum duration is expired or not
  $student_course_duration = ($current_year-$enrollment_year);
  $max_course_duration = $course->max_course_duration;
  if($student_course_duration <= $max_course_duration){
    return true;
  }else{
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
    return false;
  }
}

<<<<<<< HEAD
function getExamSettingLatest($form_type, $semester_id)
{
  $semester = \App\Models\ums\Semester::find($semester_id);
  $setting = \App\Models\ums\ExamSetting::where('form_type', $form_type)
    ->where('campus_id', $semester->course->campus_id)
    ->where('course_id', $semester->course_id)
    ->where('semester_id', $semester_id)
    ->orderBy('id', 'DESC')
    ->first();
  if (!$setting) {
    $setting = \App\Models\ums\ExamSetting::where('form_type', $form_type)
      ->where('campus_id', $semester->course->campus_id)
      ->where('course_id', $semester->course_id)
      ->whereNull('semester_id')
      ->orderBy('id', 'DESC')
      ->first();
  }
  if (!$setting) {
    $setting = \App\Models\ums\ExamSetting::where('form_type', $form_type)
      ->where('campus_id', $semester->course->campus_id)
      ->whereNull('course_id')
      ->whereNull('semester_id')
      ->orderBy('id', 'DESC')
      ->first();
  }
  if (!$setting) {
    $setting = \App\Models\ums\ExamSetting::where('form_type', $form_type)
      ->whereNull('course_id')
      ->whereNull('semester_id')
      ->orderBy('id', 'DESC')
      ->first();
=======
function getExamSettingLatest($form_type,$semester_id){
  $semester = \App\Models\Semester::find($semester_id);
  $setting = \App\Models\ExamSetting::where('form_type',$form_type)
  ->where('campus_id',$semester->course->campus_id)
  ->where('course_id',$semester->course_id)
  ->where('semester_id',$semester_id)
  ->orderBy('id','DESC')
  ->first();
  if(!$setting){
    $setting = \App\Models\ExamSetting::where('form_type',$form_type)
    ->where('campus_id',$semester->course->campus_id)
    ->where('course_id',$semester->course_id)
    ->whereNull('semester_id')
    ->orderBy('id','DESC')
    ->first();
  }
  if(!$setting){
    $setting = \App\Models\ExamSetting::where('form_type',$form_type)
    ->where('campus_id',$semester->course->campus_id)
    ->whereNull('course_id')
    ->whereNull('semester_id')
    ->orderBy('id','DESC')
    ->first();
  }
  if(!$setting){
    $setting = \App\Models\ExamSetting::where('form_type',$form_type)
    ->whereNull('course_id')
    ->whereNull('semester_id')
    ->orderBy('id','DESC')
    ->first();
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
  }
  return $setting;
}

<<<<<<< HEAD
function getEvenSemesters($course_id, $type = 0)
{
  // $type = 0 (for semester array)
  // $type = 1 (for semester ids)
  $semesters_id = [2, 4, 6, 8, 10, 12, 14];
  $semester = \App\Models\ums\Semester::where('course_id', $course_id)
    ->whereIn('semester_number', $semesters_id)
    ->get();
  if ($type == 1) {
    return $semester->pluck('id')->toArray();
  } else {
    return $semester;
  }
}
function getOddSemesters($course_id, $type = 0)
{
  // $type = 0 (for semester array)
  // $type = 1 (for semester ids)
  $semesters_id = [1, 3, 5, 7, 9, 11, 13, 15];
  $semester = \App\Models\ums\Semester::where('course_id', $course_id)
    ->whereIn('semester_number', $semesters_id)
    ->get();
  if ($type == 1) {
    return $semester->pluck('id')->toArray();
  } else {
=======
function getEvenSemesters($course_id,$type=0){
  // $type = 0 (for semester array)
  // $type = 1 (for semester ids)
  $semesters_id = [2,4,6,8,10,12,14];
  $semester = \App\Models\Semester::where('course_id',$course_id)
  ->whereIn('semester_number',$semesters_id)
  ->get();
  if($type==1){
    return $semester->pluck('id')->toArray();
  }else{
    return $semester;
  }
}
function getOddSemesters($course_id,$type=0){
  // $type = 0 (for semester array)
  // $type = 1 (for semester ids)
  $semesters_id = [1,3,5,7,9,11,13,15];
  $semester = \App\Models\Semester::where('course_id',$course_id)
  ->whereIn('semester_number',$semesters_id)
  ->get();
  if($type==1){
    return $semester->pluck('id')->toArray();
  }else{
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
    return $semester;
  }
}

<<<<<<< HEAD
function admission_open_couse_wise($course_id, $type = 1, $academic_session = null)
{
  $current_date = date('Y-m-d');
  $course = \App\Models\ums\Course::find($course_id);
  if (!$course) {
    return false;
  }
  $admissionSetting = \App\Models\ums\AdmissionSetting::select('*')
    ->where('action_type', $type)
    ->where('course_id', $course_id)
    ->where(function ($query) use ($current_date) {
      $query->where('from_date', '<=', $current_date);
      $query->where('to_date', '>=', $current_date);
    })->first();
  if (!$admissionSetting) {
    $admissionSetting = \App\Models\ums\AdmissionSetting::select('*')
      ->where('action_type', $type)
      ->where('category_id', $course->category_id)
      ->whereNull('course_id')
      ->where(function ($query) use ($current_date) {
        $query->where('from_date', '<=', $current_date);
        $query->where('to_date', '>=', $current_date);
      })->first();
  }
  if (!$admissionSetting) {
    $admissionSetting = \App\Models\ums\AdmissionSetting::select('*')
      ->where('action_type', $type)
      ->where('campus_id', $course->campus_id)
      ->whereNull('course_id')
      ->whereNull('category_id')
      ->where(function ($query) use ($current_date) {
        $query->where('from_date', '<=', $current_date);
        $query->where('to_date', '>=', $current_date);
      })->first();
  }
  if ($type == 2) {
    if ($admissionSetting && $admissionSetting->session == $academic_session) {
      return true;
    } else {
      return false;
    }
  } else {
    if ($admissionSetting) {
      return true;
    } else {
=======
function admission_open_couse_wise($course_id,$type=1,$academic_session=null){
  $current_date = date('Y-m-d');
  $course = \App\Models\Course::find($course_id);
  if(!$course){
    return false;
  }
  $admissionSetting = \App\Models\AdmissionSetting::select('*')
  ->where('action_type',$type)
  ->where('course_id',$course_id)
  ->where(function ($query) use ($current_date){
    $query->where('from_date','<=',$current_date);
    $query->where('to_date','>=',$current_date);
  })->first();
  if(!$admissionSetting){
    $admissionSetting = \App\Models\AdmissionSetting::select('*')
    ->where('action_type',$type)
    ->where('category_id',$course->category_id)
    ->whereNull('course_id')
    ->where(function ($query) use ($current_date){
      $query->where('from_date','<=',$current_date);
      $query->where('to_date','>=',$current_date);
    })->first();
  }
  if(!$admissionSetting){
    $admissionSetting = \App\Models\AdmissionSetting::select('*')
    ->where('action_type',$type)
    ->where('campus_id',$course->campus_id)
    ->whereNull('course_id')
    ->whereNull('category_id')
    ->where(function ($query) use ($current_date){
      $query->where('from_date','<=',$current_date);
      $query->where('to_date','>=',$current_date);
    })->first();
  }
  if($type==2){
    if($admissionSetting && $admissionSetting->session == $academic_session){
      return true;
    }else{
      return false;
    }
  }else{
    if($admissionSetting){
      return true;
    }else{
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
      return false;
    }
  }
}

<<<<<<< HEAD
function get_last_semester($course_id)
{
  $semester = \App\Models\ums\Semester::where('course_id', $course_id)
    ->orderBy('semester_number', 'DESC')
    ->first();
  return $semester;
}

function previous_session($session)
{
  $current_year = substr($session, 0, 4);
  $previous_year = ($current_year - 1);
  return $previous_year . '-' . $current_year;
}

function current_semester_studing($roll_no)
{
  $student = \App\Models\ums\Enrollment::where('roll_number', $roll_no)->first();
  $result = \App\Models\ums\Result::where('roll_no', $student->roll_number)->orderBy('semester_number', 'DESC')->first();

  if ($result) {
    if ($result->year_back == 1 && $result->result_type == 'new') {
      $next_semester = ($result->semester_number - 1);
    } else {
      $next_semester = ($result->semester_number + 1);
      $last_semester = get_last_semester($result->course_id);
      if ($last_semester->semester_number == $result->semester_number) {
        $next_semester = 'COMPLETED ALL SEMESTERS';
      }
    }
  } else {
    if ($student->is_lateral == 1) {
      $next_semester = 3;
    } else {
=======
function get_last_semester($course_id){
  $semester = \App\Models\Semester::where('course_id',$course_id)
  ->orderBy('semester_number','DESC')
  ->first();
      return $semester;
}

function previous_session($session){
  $current_year = substr($session,0,4);
  $previous_year = ($current_year-1);
  return $previous_year.'-'.$current_year;
}

function current_semester_studing($roll_no){
  $student = \App\Models\Enrollment::where('roll_number',$roll_no)->first();
  $result = \App\Models\Result::where('roll_no',$student->roll_number)->orderBy('semester_number','DESC')->first();

  if($result){
    if($result->year_back==1 && $result->result_type=='new'){
      $next_semester = ($result->semester_number-1);
    }else{
      $next_semester = ($result->semester_number+1);
      $last_semester = get_last_semester($result->course_id);
      if($last_semester->semester_number==$result->semester_number){
        $next_semester = 'COMPLETED ALL SEMESTERS';
      }
    }
  }else{
    if($student->is_lateral == 1){
      $next_semester = 3;
    }else{
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
      $next_semester = 1;
    }
  }
  return $next_semester;
}

<<<<<<< HEAD
function get_semester_by_number($course_id, $semester_number)
{
  return \App\Models\ums\Semester::where('course_id', $course_id)
    ->where('semester_number', $semester_number)
    ->first();
}

function mbbs_mark_40_percentage($required_marks, $obtained_marks)
{
  $required_marks = (($required_marks * 40) / 100);
  if ($obtained_marks >= $required_marks) {
    return '';
  } else {
    return '*';
  }
}
function md_min_marks_40_percentage($required_marks)
{
  $required_marks = (int)$required_marks;
  return (($required_marks * 40) / 100);
}
function md_min_marks_50_percentage($required_marks)
{
  $required_marks = (int)$required_marks;
  return (($required_marks * 50) / 100);
}
function mbbs_mark_50_percentage($required_marks, $obtained_marks)
{
  $required_marks = (($required_marks * 50) / 100);
  if ($obtained_marks >= $required_marks) {
=======
function get_semester_by_number($course_id,$semester_number){
  return \App\Models\Semester::where('course_id',$course_id)
  ->where('semester_number',$semester_number)
  ->first();
}

function mbbs_mark_40_percentage($required_marks,$obtained_marks){
  $required_marks = (($required_marks*40)/100);
  if($obtained_marks >= $required_marks){
    return '';
  }else{
    return '*';
  }
}
function md_min_marks_40_percentage($required_marks){
  $required_marks = (int)$required_marks;
  return (($required_marks*40)/100);
}
function md_min_marks_50_percentage($required_marks){
  $required_marks = (int)$required_marks;
  return (($required_marks*50)/100);
}
function mbbs_mark_50_percentage($required_marks,$obtained_marks){
  $required_marks = (($required_marks*50)/100);
  if($obtained_marks >= $required_marks){
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
    return '';
  }
  // adding 5 numbers for pass with grace marks
  $obtained_marks = ($obtained_marks + 5);
<<<<<<< HEAD
  if (($obtained_marks >= $required_marks)) {
    return '#';
  } else {
=======
  if(($obtained_marks >= $required_marks)){
    return '#';
  }else{
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
    return '*';
  }
}

<<<<<<< HEAD
function mbbsUpdateTrResult($roll_no, $semester_id, $session, $total_required_marks, $total_obtained_marks, $result)
{
  //$result = FAIL,PASS,PASS WITH GRACE
  Result::select('*')
    ->where('roll_no', $roll_no)
    ->where('course_id', 49)
    ->where('semester', $semester_id)
    ->where('exam_session', $session)
    ->where('status', 1)
    ->update([
      'required_marks' => $total_required_marks,
      'obtained_marks' => $total_obtained_marks,
      'result' => $result,
      'result_overall' => $result,
      'failed_semester_number' => null,
    ]);
}

function get_distinction($max_mark, $obtained_mark, $percentage)
{
  $division = ($obtained_mark * 100) / $max_mark;
  if ($division >= $percentage) {
    return 'D';
  } else {
    return '';
  }
}
=======
function mbbsUpdateTrResult($roll_no,$semester_id,$session,$total_required_marks,$total_obtained_marks,$result){
  //$result = FAIL,PASS,PASS WITH GRACE
  Result::select('*')
  ->where('roll_no',$roll_no)
  ->where('course_id',49)
  ->where('semester',$semester_id)
  ->where('exam_session',$session)
  ->where('status',1)
  ->update([
    'required_marks'=> $total_required_marks,
    'obtained_marks'=> $total_obtained_marks,
    'result'=> $result,
    'result_overall'=> $result,
    'failed_semester_number'=> null,
  ]);
}

function get_distinction($max_mark,$obtained_mark,$percentage){
  $division = ($obtained_mark*100)/$max_mark;
  if($division>=$percentage){
    return 'D';
  }else{
    return '';
  }
}
}
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
