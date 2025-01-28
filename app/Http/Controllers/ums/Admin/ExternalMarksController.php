<?php

namespace App\Http\Controllers\Admin;

use View;
use Auth;
use App\User;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Application;
use App\Models\Course;
use App\Models\Category;
use App\Models\PermanentAddress;
use App\Models\UploadDocuments;
use App\Models\Icard;
use App\Models\Result;
use App\Models\Subject;
use App\Models\StudentSubject;
use App\Models\StudentsemesterFee;
use App\Models\InternalMarksMapping;
use App\Models\AcademicSession;
use App\Models\Campuse;
use App\Models\ExamFee;
use App\Models\ExternalMark;
use App\Models\MbbsExamForm;
use App\Models\Semester;
use Carbon\Carbon;

class ExternalMarksController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        View::share('menu_id', 'menu_dashboard');
        View::share('submenu_id', 'NA');
    }
	 public function index()
    {
		$faculty_id=Auth::guard('faculty')->user()->id;
		$external_marks = ExternalMark::select('external_marks.roll_number','external_marks.sub_code','external_marks.enrollment_number','external_marks.semester_marks','external_marks.total_marks','external_marks.student_name','external_marks.total_marks_words',)
		->join('internal_marks_mappings','internal_marks_mappings.sub_code','external_marks.sub_code')
		->where('external_marks.faculty_id',$faculty_id)
		->orderBy('external_marks.id','DESC')
		->distinct()
		->paginate(50);
        //dd($external_marks);

		
		return view('faculty.external.index', [
            'external_marks' => $external_marks
        ]);
    } 
	public function external(Request $request)
    {

		$campuses = Campuse::all();
		$sessions = AcademicSession::all();
		$courses = [];
		$semesters = [];
		$subjects = [];
		$students = [];
		if($request->campus_id){
			$courses = Course::where('campus_id',$request->campus_id)->get();
		}
		if($request->course){
			$semesters = Semester::where('course_id',$request->course)->get();
		}
		if($request->semester && $request->course){
			$subjects = Subject::where('semester_id',$request->semester)
				->where('course_id',$request->course)
				->get();
		}

		if($request->semester && $request->course){
			$subject_query = ExamFee::select('enrollment_no','roll_no','form_type')
			->where('course_id',$request->course)
			->where('semester',$request->semester)
			->where('subject','LIKE','%'.$request->sub_code.'%');
			if($request->session){
				$subject_query->where('academic_session',$request->session);
			}
			$students = $subject_query->orderBy('roll_no')->distinct()->get();

		}
		dd($students);
	return view('admin.external.add',compact('students','campuses','sessions','courses','semesters','subjects'));
    }
	public function externalMarksShow(Request $request)
    {
		$user=Auth::guard('faculty')->user();
		$data['mapped_Subjects']=InternalMarksMapping::select('subjects.*')
				->join('subjects','subjects.sub_code','internal_marks_mappings.sub_code')
				->where('faculty_id',$user->id)->get();

	

			$data['marks']= ExternalMark::where('session',$request->session)->where('sub_code',$request->sub_code)
			->where('faculty_id',$user->id)->get();
			$data['details']= ExternalMark::where('session',$request->session)->where('sub_code',$request->sub_code)
			->where('faculty_id',$user->id)->first();
			$data['sessions']=AcademicSession::all();
			//dd($data);
        return view('faculty.external.show',$data);
    }
	public function external_submit(Request $request)
	{
		$user=Auth::guard('faculty')->user();
		//	dd($request->all());
		
		//dd($external);
		
		$marks = ExternalMark::where('session',$request->session)
		->where('sub_code',$request->sub_code)
		->where('faculty_id',$user->id)
		->get();

		if( $marks->count()>0 ){
			foreach($marks as $index=>$mark_row){
				
			//dd($request->semester_marks,$mark_row->enrollment_number,$mark_row->sub_code);
			$enrollment_no=$mark_row->enrollment_number;
			$result=Result::where([
				'enrollment_no'=>$mark_row->enrollment_number,
				'exam_session'=>$mark_row->session,
				'semester'=> $mark_row->semester_id,
				'subject_code'=>$mark_row->sub_code])
			->orderBy('id','desc')
			->first();
			  //dd($result);
			  if(!$result){
				
				$student_internal_mark['enrollment_no'] = $mark_row->enrollment_number;
				$student_internal_mark['roll_no'] = $mark_row->roll_number;
				$student_internal_mark['exam_session'] = $mark_row->session;
				$student_internal_mark['semester'] = $mark_row->semester_id;
				$student_internal_mark['course_id'] = $mark_row->course_id;
				$student_internal_mark['subject_code'] = $mark_row->sub_code;
				Result::insert($student_internal_mark);
				//dd($student_internal_mark);
				$result=Result::where([
					'enrollment_no'=>$mark_row->enrollment_number,
					'exam_session'=>$mark_row->session,
					'semester'=> $mark_row->semester_id,
					'course_id'=> $mark_row->course_id,
					'subject_code'=>$mark_row->sub_code])
					->orderBy('id','desc')
					->first();
			
			//dd($result);
		}
			
			   //dd($total);
			   if(is_numeric($result->internal_marks)){
				if(is_numeric($request->semester_marks[$index])){
				$total=$result->internal_marks+$request->semester_marks[$index];
			   }
			   else{
				   
				   $total=0+$request->semester_marks[$index];
			   }
			 }
			 elseif(is_numeric($request->semester_marks[$index])){
				   
				$total=0+$request->semester_marks[$index];
			}else{
				$total='ABSENT';
			}
			
			$subject= Subject::where(['course_id'=>$mark_row->course_id,'semester_id'=>$mark_row->semester_id,'sub_code'=>$mark_row->sub_code])->first();
			  $back=0;
			  if($total<$subject->minimum_mark){
				  $back=1;
			  }
			  
			 Result::where('enrollment_no',$enrollment_no)
			  ->where('exam_session',$mark_row->session)
			  ->where('semester', $mark_row->semester_id)
			  ->where('subject_code', $mark_row->sub_code)
			  ->update(['external_marks' => $request->semester_marks[$index],'total_marks'=>$total,'back_status'=>$back]);
			  $external=ExternalMark::where('sub_code',$request->sub_code)->where('faculty_id',$user->id)->update(['final_status'=>1]);
			//dd($re);
			
			
		}
	}
		return redirect('faculty/external-marks-show/?session='.$request->session.'&sub_code='.$request->sub_code);
	}
	public function post_external(Request $request){
		foreach($request->enrollment_number as $index=>$enrollment_no){
			$student_external[$index]['absent_status'] = $request->absent_status[$index];
			$student_external[$index]['campus_code'] = $request->campus_id[$index];
			$student_external[$index]['campus_name'] = $request->campus_name[$index];
			$student_external[$index]['program_id'] = $request->program_id[$index];
			$student_external[$index]['program_name'] = $request->program_name[$index];
			$student_external[$index]['course_id'] = $request->course_id[$index];
			$student_external[$index]['course_name'] = $request->course_name[$index];
			$student_external[$index]['semester_id'] = $request->semester_id[$index];
			$student_external[$index]['semester_name'] = $request->semester_name[$index];
			$student_external[$index]['session'] = $request->session[$index];
			$student_external[$index]['faculty_id'] = $request->faculty_id[$index];
			$student_external[$index]['sub_code'] = $request->subject_code[$index];
			$student_external[$index]['sub_name'] = $request->subject_name[$index];
			$student_external[$index]['date_of_semester_exam'] = $request->semester_date[$index];
			$student_external[$index]['maximum_mark'] = $request->maximum_external[$index];
			$student_external[$index]['student_name'] = $request->student_name[$index];
			$student_external[$index]['enrollment_number'] = $request->enrollment_number[$index];
			$student_external[$index]['roll_number'] = $request->roll_number[$index];
			$student_external[$index]['semester_marks'] = $request->external_marks[$index];
			$student_external[$index]['total_marks'] = $request->total_marks[$index];
			$student_external[$index]['total_marks_words'] = $request->total_marks_words[$index];
			$student_external[$index]['created_at'] = Carbon::now();

		}
		//dd($student_external);
		ExternalMark::insert($student_external);
		return redirect('faculty/external-marks-show/?session='.$request->session[0].'&sub_code='.$request->subject_code[0]);

		
	}
	public function preview_external(Request $request){
		$user=Auth::guard('faculty')->user();
		$faculty=$request->all();
		$query=EnternalMark::where('sub_code',$request->sub_code)
						->where('session',$request->session)
						->where('faculty_id',$user->id);
		$external_data=$query->get();
		$external_data_first=$query->first();
		
		
		
		//dd($external_data);
		return view('faculty.external.show',['faculty'=>$faculty,'external_data'=>$external_data,'external_data_first'=>$external_data]);
	}
	public function external_update(Request $request){
	//dd($request->all());
		$ExternalMark = ExternalMark::find($request->id);
		if($ExternalMark){
			//dd($ExternalMark);
			$ExternalMark->semester_marks = $request->semester_marks;
			$ExternalMark->total_marks = $request->total_marks;
			if(is_numeric($request->total_marks)==true){
				$ExternalMark->absent_status = 0;
			}else{
				$ExternalMark->absent_status = 1;
			}
			$ExternalMark->save();
			return back()->with('success','Saved');
		}
		return back()->with('error','Some Error Occurred');
	}
}
