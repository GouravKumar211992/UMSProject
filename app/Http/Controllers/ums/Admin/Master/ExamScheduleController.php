<?php

namespace App\Http\Controllers\ums\Admin\Master;

use App\Http\Controllers\ums\AdminController;
use Illuminate\Http\Request;
use App\Models\ums\Course;
use App\Models\ums\Student;
use App\Models\ums\Campuse;
use App\Models\ums\Semester;
use App\Models\ums\AcademicSession;
use App\Models\ums\Subject;
use App\Models\ums\ExamSchedule;
use Illuminate\Support\Facades\Validator;


use App\Imports\ExamScheduleImport;
use Maatwebsite\Excel\Facades\Excel;

class ExamScheduleController extends AdminController
{
    public function schedule_show(Request $request)
    {  
    	$data['sessions']=AcademicSession::all();
    	$data['campuses']=Campuse::all();
    	$data['courses']=Course::where('campus_id',$request->campus_id)->orderBy('name','asc')->get();
    	$data['semesters']=Semester::where('course_id',$request->course)->orderBy('id','asc')->get();
        $course=null;
        $subjects= [];
	   if( $request->campus_id && $request->course  && $request->semester && $request->session && $request->schedule_count ){
          $duplicate_schedule=ExamSchedule::where(['courses_id'=>$request->course,'semester_id'=>$request->semester,'year'=>$request->session,'schedule_count'=>$request->schedule_count])->pluck('paper_code')->toArray();
          $subjects=Subject::where(['course_id'=>$request->course,'semester_id'=>$request->semester])
            ->whereNotIn('sub_code',$duplicate_schedule)
            ->orderBy('id','asc')->paginate(10);
        }
        $data['subjects'] = $subjects;
    	  return view('ums.exam.Exam_Schedule',$data);
    }


      public function schedule_post(Request $request)
    {  
         $validator = Validator::make($request->all(),[
        'date.*' => 'required',
        'shift.*' => 'required',
        ]);

        if ($validator->fails()) {    
          // dd($request->all());
          return back()->withErrors($validator)->withInput($request->all());
      }
        $array_data = [];
        foreach($request->paper_code as $index=>$paper_code){
          $duplicate_schedule = ExamSchedule::where(['courses_id'=>$request->course_id,'semester_id'=>$request->semester_id,'paper_code'=>$paper_code,'year'=>$request->year,'schedule_count'=>$request->schedule_count])->first();
          if(!$duplicate_schedule){
            $array_data[$index]['courses_id']    = $request->course_id;
            $array_data[$index]['courses_name']    = $request->courses_name;
            $array_data[$index]['semester_id']    = $request->semester_id;
            $array_data[$index]['semester_name']    = $request->semester_name;
            $array_data[$index]['date']             = $request->date[$index];
            $array_data[$index]['shift']            = $request->shift[$index];
            $array_data[$index]['paper_code']       = $paper_code;
            $array_data[$index]['paper_name']       = $request->paper_name[$index];
            $array_data[$index]['year']       = $request->year;
            $array_data[$index]['schedule_count']       = $request->schedule_count;
          }
        }
        ExamSchedule::insert($array_data);
        return back()->with('message','time Table Created Succesfully'); 
    }


    public function schedule_bulk_uploading(Request $request){
    	$request->validate([
        'impport_file' => 'required',
    ]);
    if($request->hasFile('impport_file')){
      Excel::import(new ExamScheduleImport, $request->file('impport_file'));
    }
    return back()->with('success','Records Saved!');
}

     
   
     public function timetable(Request $request)
    {
      // dd($request->all());
    	$data['sessions'] = AcademicSession::all();
    	$data['campuses'] = Campuse::all();
    	$data['courses'] = Course::where('campus_id',$request->campus_id)->orderBy('name','asc')->get();
    	$data['semesters'] = Semester::where('course_id',$request->course)->orderBy('id','asc')->get();
      $data['exams'] = [];
      if($request->course!= null)
      {
        $data['course_id'] = $request->course;
        $data['semester_id'] = $request->semester;
    	  $data['exams'] = ExamSchedule::where(['courses_id'=>$request->course,'semester_id'=>$request->semester,'year'=>$request->session,'schedule_count'=>$request->schedule_count])->get();
      }
      // dd($data);
     return view('ums.exam.view_time_tables',$data);
    }
    public function get_Semester(Request $request)
	{
		// dd($request->all());
		$html='<option value="">--Select Semester--</option>';
		$query= Semester::where(['course_id' => $request->course])->get();
   // dd($query);
		foreach($query as $sc){
			$html.='<option value="'.$sc->id.'">'.$sc->name.'</option>';
		}
	
		return $html;
	}
  public function schedule_update(Request $request){
    // dd($request->all());
        $Schedule = ExamSchedule::find($request->id);
        if($Schedule){
          $Schedule->date = $request->date;
          $Schedule->shift = $request->shift;
          $Schedule->save();
          return back()->with('success','Saved');
        }
        return back()->with('error','Some Error Occurred');
      }
}
