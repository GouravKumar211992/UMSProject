<?php

namespace App\Http\Controllers\ums\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\ums\Result;
use App\Models\ums\Course;
use App\Models\ExamFee;
use App\Models\Enrollment;
use App\Models\Stream;
use App\Models\ums\Campuse;
use App\Models\ExamForm;
use App\Models\Subject;
use App\Models\ums\Semester;
use App\Models\Icard;
use App\Models\Grade;
use App\Models\GradeOld;
use App\Models\ums\AcademicSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Artisan;

class ResultController extends Controller
{
    public function index()
    {
		$roll_number=Auth::
		
		
		uard('student')->user()->roll_number;
		$semesters=Semester::select('semesters.*')->join('results','results.semester','semesters.id')
						->where('roll_no',$roll_number)
						->groupBy('semester')
						->get();
		//dd(count($semesters));
    	return view('student.result.result-login',['semesters'=>$semesters]);
    }
	
	public function view(Request $request)
	{
		$roll_number = base64_decode($request->roll_number);
		$semester_id = $request->id;
		$result_data = Result::where('roll_no',$roll_number)
		->where('semester',$semester_id)
		->orderBy('id','DESC')
		->first();
		if(!$result_data){
			return back()->with('error','Result is not generated');
		}
		if($result_data->status==1){
			return back()->with('error','Result is generated but not approved');
		}

		// Artisan::call('command:ResultCGPAUpdateByRollNumber', [
		// 	'roll_no' => $roll_number,
		// ]);
		$result_single = Result::where('roll_no',$roll_number)
		->where('semester',$semester_id)
		->where('status',2)
		->distinct()
		->orderBy('back_status','DESC')
		->orderBy('exam_session','DESC')
		->orderBy('back_status_text','ASC')
		->first();
		$results = $result_single->get_semester_result_for_cgpa(1);
		if($request->approval_date){
			foreach($results as $result){
				$result->approval_date = $request->approval_date;
				$result->cgpa = $request->cgpa;
				Result::where('roll_no',$roll_number)->where('semester',$semester_id)->update(['session_name'=>$request->session_name]);
				$result->save();
			}
			return redirect('admin/view-results?id='.$request->id.'&roll_number='.$request->roll_number.'');
		}
		$exam_session_details = Result::where('semester',$semester_id)
		->where('roll_no',$roll_number)
		->where('back_status_text','REGULAR')
		->first();
		// dd($exam_session_details);
		return view('student.result.semester-result',compact('results','result_single','exam_session_details'));
	}

// SINGLE BSC NURSING RESULT VIEW
	public function viewBscNursing(Request $request)
	{
		// dd($request->all());
		$roll_number = base64_decode($request->roll_number);
		$semester_id = $request->id;
		$result_data = Result::where('roll_no',$roll_number)->where('semester',$semester_id)->first();
		if(!$result_data){
			return back()->with('error','Result is not generated');
		}
		if(Auth::guard('student')->check()==true && $result_data->status==1){
			return back()->with('error','Result is generated but not approved');
		}
		$result_data = $result_data->get_semester_result_single();
		$students = Result::select('roll_no','semester')
		->where('exam_session',$result_data->exam_session)
		->where('course_id',$result_data->course_id)
		->where('semester',$result_data->semester)
		->where('roll_no',$result_data->roll_no)
		->distinct()
		->orderBy('roll_no')
		->get();
		foreach($students as $student){
			$roll_number = $student->roll_no;
			$semester_id = $student->semester;
			$exam_year_array = $result_data;
			$result_single = $result_data;
			$results = $result_single->get_semester_result(1);
			$student->exam_year_array = $exam_year_array;
			$student->result_single = $result_single;
			$student->results = $results;
		}
		return view('student.result.bsc-nursing-result',compact('students'));

	}

	public function semesterResultBulk(Request $request)
	{
        $campuses = Campuse::all();
		$courses = Course::withoutTrashed()->where('campus_id',$request->campus_id)->orderBy('name')->get();
		$semesters = Semester::withoutTrashed()->where('course_id',$request->course_id)->orderBy('semester_number')->get();
		if(Auth::guard('admin')->check()==false){
			return back()->with('error','Not Allowed');
		}
		$course_id = $request->course_id;
		$semester = $request->semester_id;
		$batchPrefixByBatch = batchPrefixByBatch($request->batch);
		$students_query = Result::select('roll_no','semester')
		->has('student')
		->where('roll_no','like',$batchPrefixByBatch.'%')
		->where('course_id',$course_id)
		->where('semester',$semester)
		->where('status',2);
		// ->where('result_type','new');
		if($request->roll_no){
			$students_query->where('roll_no',$request->roll_no);
		}
		if($request->back_status_text!='REGULAR'){
			$students_query->where('back_status_text',$request->back_status_text);
		}
		$students = $students_query->distinct()
		->orderBy('roll_no')
		->get();
		foreach($students as $student){
			// Artisan::call('command:ResultCGPAUpdateByRollNumber', [
            //     'roll_no' => $student->roll_no,
            // ]);
			$roll_number = $student->roll_no;
			$semester_id = $student->semester;
			$result_single = Result::where('roll_no',$roll_number)
			->where('semester',$semester_id)
			->where('status',2)
			->orderBy('back_status','DESC')
			->orderBy('exam_session','DESC')
			->orderBy('back_status_text','ASC')
			->distinct()
			->first();
			$results = $result_single->get_semester_result_for_cgpa(1);
			$exam_session_details = Result::where('semester',$semester_id)
			->where('roll_no',$roll_number)
			->where('back_status_text','REGULAR')
			->first();

			$student->result_single = $result_single;
			$student->results = $results;
			$student->exam_session_details = $exam_session_details;
		}
		return view('student.result.semester-result-bulk',compact('students','campuses','courses','semesters'));
	}

	public function resultDateCorrection(Request $request)
	{
		if(Auth::guard('admin')->check()==false){
			return back()->with('error','Not Allowed');
		}

		$campuses = Campuse::all();
		$courses = Course::withoutTrashed()->where('campus_id',$request->campus_id)->orderBy('name')->get();
		$semesters = Semester::withoutTrashed()->where('course_id',$request->course_id)->orderBy('semester_number')->get();
		$batchPrefixByBatch = batchPrefixByBatch($request->batch);

		$exam_sessions = Result::select('exam_session')
		->where('roll_no','like',$batchPrefixByBatch.'%')
		->where('course_id',$request->course_id)
		->where('semester',$request->semester_id)
		->where('back_status_text',$request->back_status_text)
		->where('status',2)
		->distinct()
		->pluck('exam_session')
		->toArray();
		$students = Result::select('roll_no','semester')
		->where('roll_no','like',$batchPrefixByBatch.'%')
		->where('course_id',$request->course_id)
		->where('semester',$request->semester_id)
		->where('back_status_text',$request->back_status_text)
		->where('exam_session',$request->exam_session)
		->where('status',2)
		->distinct()
		->orderBy('roll_no')
		->get();
		return view('student.result.result-date-correction',compact('students','campuses','courses','semesters','exam_sessions'));
	}
	public function resultDateCorrectionSave(Request $request)
	{
		if(Auth::guard('admin')->check()==false){
			return back()->with('error','Not Allowed');
		}

		$this->validate($request, [
            'batch' => ['required'],
            'course_id' => ['required'],
            'semester_id' => ['required'],
            'back_status_text' => ['required'],
            'exam_session' => ['required']
        ]);

		$batchPrefixByBatch = batchPrefixByBatch($request->batch);
		if(!$request->result_date && !$request->session_name){
			return back()->with('error','Please provide approval date or session name');
		}
		if($request->result_date){
			$result_date = date('Y-m-d',strtotime($request->result_date));
			Result::where('roll_no','like',$batchPrefixByBatch.'%')
			->where('course_id',$request->course_id)
			->where('semester',$request->semester_id)
			->where('back_status_text',$request->back_status_text)
			->where('exam_session',$request->exam_session)
			->where('status',2)
			->update(['approval_date'=>$result_date]);
		}
		if($request->session_name){
			Result::where('roll_no','like',$batchPrefixByBatch.'%')
			->where('course_id',$request->course_id)
			->where('semester',$request->semester_id)
			->where('back_status_text',$request->back_status_text)
			->where('exam_session',$request->exam_session)
			->where('status',2)
			->update(['session_name'=>$request->session_name]);
		}
		return back()->with('success','Date is updated successfully.');
	}

	public function nursingResultBulk(Request $request)
	{
		// dd($request->all());
        $campuses = Campuse::whereIn('id',[6,23])->get();
		$courses = Course::withoutTrashed()->where('campus_id',$request->campus_id)->orderBy('name')->get();
		$semesters = Semester::withoutTrashed()->where('course_id',$request->course_id)->orderBy('semester_number')->get();
        $sessions = AcademicSession::all();
		if(Auth::guard('admin')->check()==false){
			return back()->with('error','Not Allowed');
		}
		$students = Result::select('roll_no','semester')
			->where('exam_session',$request->academic_session)
			->where('course_id',$request->course_id)
			->where('semester',$request->semester_id)
			->distinct()
			->orderBy('roll_no')
			->get();
		foreach($students as $student){
			$result_data = $student->get_semester_result_single();
			$roll_number = $student->roll_no;
			$semester_id = $student->semester;
			$exam_year_array = $result_data;
			$result_single = $result_data;
			$results = $result_single->get_semester_result(1);
			$student->exam_year_array = $exam_year_array;
			$student->result_single = $result_single;
			$student->results = $results;
		}
		return view('student.result.nursing-result-bulk',compact('students','campuses','courses','semesters','sessions'));
	}


	public function resultApproved(Request $request){	
		$results = Result::where('roll_no',$request->roll_number)
						->where('semester',$request->id)
						->where('exam_session',$request->session)
						->update(['status'=>2]);
		if($results){
			return back()->with('success','Result Approved Successfully');
		}else{
			return back()->with('error','Some Error Occurred');
		}
    }


}


