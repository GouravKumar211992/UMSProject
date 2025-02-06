<?php

namespace App\Http\Controllers\ums\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ums\Campuse;
use App\Models\ums\Course;
use App\Models\ums\AcademicSession;
use App\Models\ums\ExamType;
use App\Models\ums\ExamFee;

class RegularMarkFillingController extends Controller
{
    public function regularMarkFilling(Request $request){
        $course_id_array = [];
        if($request->course){
            $course_id_array = $request->course;
        }
        $campuses = Campuse::all();
        $courses = Course::where('campus_id',$request->campus_id)->get();
        $academic_session = AcademicSession::select('academic_session')
        ->distinct('academic_session')
        ->orderBy('academic_session', 'DESC')
        ->get();
        $examType = ExamType::all();
        $form_form_data = ExamFee::where('form_type',$request->form_type)
        ->where('academic_session',$request->academic_session)
        ->whereIn('course_id',$course_id_array)
        ->orderBy('course_id','ASC')
        ->orderBy('roll_no','ASC')
        ->limit(10)
        ->get();
        // dd($form_form_data);
// 
        return view('ums.exam.regular_mark_filling',compact('academic_session','examType','form_form_data','campuses','courses'));
    }
}
