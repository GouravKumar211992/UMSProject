<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\ExamFee;
use App\Models\AdmitCard;
use App\Models\Subject;
use App\Models\Result;

class ResultController extends Controller
{
    public function index()
    {
		return view('admin/exam/result-entry');
    	
    }
    public function entry(Request $request){
      $admit_card = AdmitCard::whereId($request->id)->first();
      $data['subjects'] = Subject::whereIn('sub_code',explode(' ',$admit_card->examfee->subject))->get();
      $data['exam_data'] = $admit_card->examfee;
      $data['enrollment'] = $admit_card->examfee->enrollment;
      $data['application'] = $admit_card->examfee->enrollment->application;
      $data['admit_card'] = $admit_card;
    	return view('admin/result/result-form',$data);
    }

    public function entrySave(Request $request){
      $this->validate($request, [
       // 'enrollment_no' => 'required',
       // 'roll_no' => 'required',
       // 'admit_card_id' => 'required',
       // 'semester' => 'required',
        'subject_code.*' => 'required',
        'internal_marks.*' => 'required',
        'external_marks.*' => 'required',
        //'practical_marks.*' => 'required',
      ]); 

      $admit_card = AdmitCard::whereId($request->id)->first();
      //dd($admit_card->examfee->semester);
      $result_array = [];
      foreach($request->subject_code as $index=>$subject_code){
        $result_array[$index]['enrollment_no'] = $admit_card->enrollment_no;
        $result_array[$index]['roll_no'] = $admit_card->roll_no;
//        $result_array[$index]['exam_session'] = '2021-2022';
        $result_array[$index]['admit_card_id'] = $request->id;
        $result_array[$index]['semester'] = $admit_card->examfee->semester;
        $result_array[$index]['subject_code'] = $subject_code;
        $result_array[$index]['internal_marks'] = $request->internal_marks[$index];
        $result_array[$index]['external_marks'] = $request->external_marks[$index];
        $result_array[$index]['created_at'] = date('Y-m-d H:i:s');
      }

      Result::insert($result_array);
      return redirect('admin/master/admit-card-list')->with('success','Marks Saved Successfully');
    }

    public function schedule()
    {
    	return view('admin/exam/exam-schedule');
    }
    public function timetable()
    {
     return view('admin/exam/examtime-table');
    }
}
