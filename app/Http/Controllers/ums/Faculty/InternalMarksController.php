<?php

namespace App\Http\Controllers\ums\Faculty;

use App\models\ums\User;
use App\models\ums\Semester;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;







use App\Http\Controllers\ums\AdminController;
use Illuminate\Http\Request;
use App\Models\ums\BackPaper;
use App\Models\ums\Application;
use App\Models\ums\Course;
use App\Models\ums\Category;
use App\Models\ums\Campuse;
use App\Models\ums\PermanentAddress;
use App\Models\ums\UploadDocuments;
use App\Models\ums\Icard;
use App\Models\ums\Result;
use App\Models\ums\Subject;
use App\Models\ums\Stream;
use App\Models\ums\MbbsExamForm;
use App\Models\ums\StudentSubject;
use App\Models\ums\StudentsemesterFee;
use App\Models\ums\InternalMark;
use App\Models\ums\PracticalMark;
use App\Models\ums\ExternalMark;
use App\Models\ums\InternalMarksMapping;
use App\Models\ums\AcademicSession;
use App\Models\ums\faculty;
use App\Models\ums\AwardSheetFile;
use Carbon\Carbon;
use App\Exports\InternalMarksExport;
use App\Models\ums\ExamFee;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use DB;

class InternalMarksController extends AdminController
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
		//$session=AcademicSession::get();		
		$internal_marks = InternalMark::select('internal_marks.roll_number','internal_marks.enrollment_number','internal_marks.mid_semester_marks','internal_marks.assignment_marks','internal_marks.total_marks','internal_marks.student_name','internal_marks.total_marks_words',)->join('internal_marks_mappings','internal_marks_mappings.sub_code','internal_marks.sub_code')->where('internal_marks.faculty_id',$faculty_id)->orderBy('internal_marks.id','DESC')->distinct()->paginate(10);
        //dd($internal_marks);

		return view('ums.master.faculty.internal_marks_filling', [
            'internal_marks' => $internal_marks
        ]);
    } 
	// public function internal(Request $request)
    // {
	// 	$data['sub_code']= $data['sub_name']= $data['date_of_semester']= $data['date_of_assign']= $data['assign_maximum']= $data['mapped_faculty']= $mapped_faculty =$data['mapped_Semesters']=$data['mapped_Subjects']= null;
	// 	$data['sub_code_name'] = '';
	// 	// $user=Auth::guard('faculty')->user();
	// 	$faculty_id = request()->get('faculty_id', 19); // Default to 46 if 'faculty_id' is not provided in the request
    //     $user = faculty::find($faculty_id);
	// 	$mapped_Subjects_query = InternalMarksMapping::select('subjects.name', 'subjects.sub_code', 'subjects.back_fees', 'subjects.scrutiny_fee', 'subjects.challenge_fee', 'subjects.status', 'subjects.subject_type', 'subjects.type', 'subjects.internal_maximum_mark', 'subjects.maximum_mark', 'subjects.minimum_mark', 'subjects.credit', 'subjects.internal_marking_type', 'subjects.combined_subject_name')
	// 	->join('subjects',function($join){
	// 		$join->on('subjects.sub_code','internal_marks_mappings.sub_code')
	// 		->on('subjects.semester_id','internal_marks_mappings.semester_id');
	// 	})
		
	// 	->distinct()
	// 	->orderBy('sub_code')
	// 	->where('subjects.deleted_at',null)
	// 	->where('faculty_id',$user->id);
	// 	//dd($mapped_Subjects_query);
	// 	if($request->semester){
	// 		$mapped_Subjects_query->where('subjects.semester_id',$request->semester);
	// 	}
	// 	$data['mapped_Subjects'] = $mapped_Subjects_query->get();
	// 	$data['mapped_Courses']=InternalMarksMapping::select('courses.name','courses.id','internal_marks_mappings.course_id','campuses.name as campus_name')
	// 	->join('courses','courses.id','internal_marks_mappings.course_id')
	// 	->join('campuses','campuses.id','courses.campus_id')
	// 	->distinct()
	// 	->where('faculty_id',$user->id)
	// 	->get();
	// 	$data['streams'] = Stream::whereIn('id',[50,3,4,5])->orderBy('name','ASC')->get();
	// 	$data['mapped_Semesters']=InternalMarksMapping::select('semesters.name','semesters.id','internal_marks_mappings.semester_id')
	// 	->join('semesters','semesters.id','internal_marks_mappings.semester_id')
	// 	->distinct()
	// 	->where('faculty_id',$user->id)
	// 	->where('internal_marks_mappings.course_id',$request->course)
	// 	->get();

	// 	// $mapping_details = InternalMarksMapping::where('faculty_id',$user->id)
	// 	// ->where('sub_code',$request->sub_code)
	// 	// ->where('session',$request->session)
	// 	// ->where('course_id',$request->course)
	// 	// ->where('semester_id',$request->semester)
	// 	// ->first();

	// 	$data['sessions']=AcademicSession::orderBy('id','asc')->get();
    // 	$msg=null;
    //     $students= [];
	// 	$data['examTypes'] = StudentSubject::distinct('type')->pluck('type')->toArray();
	// 	// $user = Auth::guard('faculty')->user();
	// 	$faculty_id = request()->get('faculty_id', 19); // Default to 46 if 'faculty_id' is not provided in the request
    //     $user = Faculty::find($faculty_id);
	// 	if($request->sub_code!=null)
    //     {
	// 		$duplicate_roll_no = InternalMark::where('session',$request->session)
	// 		->where('sub_code', $request->sub_code)
	// 		->where('course_id', $request->course)
	// 		->where('semester_id', $request->semester)
	// 		->where('type',$request->type)
	// 		->where('roll_number','LIKE',$request->batch.'%')
	// 		->pluck('roll_number')
	// 		->toArray();
    //         if($request->type!='regular' && $request->type!='compartment'){
	// 			$backPaperRollNos = BackPaper::join('exam_fees','exam_fees.id','special_back_table_details.exam_fee_id')
	// 			->where('exam_fees.academic_session',$request->session)
	// 			->where('sub_code', $request->sub_code)
	// 			->where('special_back_table_details.course_id', $request->course)
	// 			->where('special_back_table_details.semester_id', $request->semester)
	// 			->where('exam_fees.form_type',$request->type)
	// 			->where('mid',1)
	// 			->where('roll_number','LIKE',$request->batch.'%')
    //             ->distinct('roll_number')
	// 			->pluck('roll_number')
	// 			->toArray();
	// 		}
	// 		$students_query = StudentSubject::has('student')
	// 		->join('enrollments','enrollments.roll_number','student_subjects.roll_number')
	// 		->select('student_subjects.enrollment_number', 'student_subjects.roll_number', 'student_subjects.session', 'student_subjects.program_id', 'student_subjects.course_id', 'student_subjects.semester_id', 'student_subjects.sub_code', 'student_subjects.sub_name')
	// 		->where('student_subjects.sub_code',$request->sub_code)
	// 		->where('student_subjects.roll_number','LIKE',$request->batch.'%')
	// 		->whereNotIn('student_subjects.roll_number',$duplicate_roll_no);
    //         if($request->type!='regular' && $request->type!='compartment'){
	// 			$students_query->whereIn('student_subjects.roll_number',$backPaperRollNos);
	// 		}
	// 		$students = $students_query->where('student_subjects.session',$request->session)
	// 		->where('student_subjects.course_id',$request->course)
	// 		->where('student_subjects.semester_id',$request->semester)
	// 		->where('student_subjects.type',$request->type)
	// 		->distinct('student_subjects.enrollment_number', 'student_subjects.roll_number', 'student_subjects.session', 'student_subjects.program_id', 'student_subjects.course_id', 'student_subjects.semester_id', 'student_subjects.sub_code', 'student_subjects.sub_name')
	// 		->orderBy('student_subjects.roll_number','asc')
	// 		->paginate(40);

	// 		// if(count($students)==0){
	// 		// 	$students = MbbsExamForm::with('student')->has('student')
	// 		// 	->where('sub_code',$request->sub_code)
	// 		// 	->whereNotIn('rollno',$duplicate_roll_no)
	// 		// 	->where('rollno','LIKE',$request->batch.'%')
	// 		// 	->orderBy('rollno','ASC')
	// 		// 	->paginate(40);
							
	// 		// 	$mapped_faculty=InternalMarksMapping::where('faculty_id',$user->id)
	// 		// 	->where('sub_code',$request->sub_code)
	// 		// 	->where('course_id',$request->course)
	// 		// 	->first();

	// 		// 	$data['msg']=$msg;
	// 		// 	$data['mapped_faculty'] = $mapped_faculty;
	// 		// 	$data['students'] = $students;
	// 		// 	$sub_name=Subject::where('sub_code', $request->sub_code)->first();
	// 		// 	$data['sub_name']=$sub_name;
	// 		// 	$data['sub_code']=$request->sub_code;
	// 		// 	$data['date_of_semester']=$request->date_of_semester;
	// 		// 	$data['date_of_assign']=$request->date_of_semester;
	// 		// 	$data['internal_maximum']=$request->internal_maximum;
	// 		// 	$data['assign_maximum']=$request->assign_maximum;
	// 		// 	if($request->course==49){
	// 		// 		// return view('faculty.internal.add-mbbs',$data);
	// 		// 	}
	// 		// }
	// 		$mapped_faculty=InternalMarksMapping::where('faculty_id',$user->id)
	// 		->where('sub_code',$request->sub_code)
	// 		->where('course_id',$request->course)
	// 		->where('semester_id',$request->semester)
	// 		->first();
    //     }
	// 	$data['msg']=$msg;
    //     $data['mapped_faculty'] = $mapped_faculty;
    //     $data['students'] = $students;
	// 	$sub_name=Subject::where('sub_code', $request->sub_code)->first();
	// 	$data['sub_name']=$sub_name;
	// 	$data['sub_code']=$request->sub_code;
	// 	$data['date_of_semester']=$request->date_of_semester;
	// 	$data['date_of_assign']=$request->date_of_semester;
	// 	$data['internal_maximum']=$request->internal_maximum;
	// 	$data['assign_maximum']=$request->assign_maximum;
	// 	dd('mapped_semesters',$data);
    //     return view('ums.master.faculty.internal_marks_filling',$data);
    // }
	public function internal(Request $request)
{
    $data['sub_code'] = $data['sub_name'] = $data['date_of_semester'] = $data['date_of_assign'] = $data['assign_maximum'] = $data['mapped_faculty'] = $mapped_faculty = $data['mapped_Semesters'] = $data['mapped_Subjects'] = null;
    $data['sub_code_name'] = '';
    
    // Default faculty_id for testing (replace with actual logic if needed)
    $faculty_id = $request->get('faculty_id', 19); // Default to 19 if 'faculty_id' is not provided in the request
    $user = Faculty::find($faculty_id);

    // Get mapped subjects for the faculty
    $mapped_Subjects_query = InternalMarksMapping::select(
        'subjects.name', 'subjects.sub_code', 'subjects.back_fees', 'subjects.scrutiny_fee', 'subjects.challenge_fee', 'subjects.status', 
        'subjects.subject_type', 'subjects.type', 'subjects.internal_maximum_mark', 'subjects.maximum_mark', 'subjects.minimum_mark', 
        'subjects.credit', 'subjects.internal_marking_type', 'subjects.combined_subject_name')
        ->join('subjects', function($join) {
            $join->on('subjects.sub_code', '=', 'internal_marks_mappings.sub_code')
                ->on('subjects.semester_id', '=', 'internal_marks_mappings.semester_id');
        })
        ->distinct()
        ->orderBy('sub_code')
        ->whereNull('subjects.deleted_at')
        ->where('faculty_id', $user->id);

    if ($request->semester) {
        $mapped_Subjects_query->where('subjects.semester_id', $request->semester);
    }
    
    $data['mapped_Subjects'] = $mapped_Subjects_query->get();

    // Get mapped courses for the faculty
    $data['mapped_Courses'] = InternalMarksMapping::select(
        'courses.name', 'courses.id', 'internal_marks_mappings.course_id', 'campuses.name as campus_name')
        ->join('courses', 'courses.id', '=', 'internal_marks_mappings.course_id')
        ->join('campuses', 'campuses.id', '=', 'courses.campus_id')
        ->distinct()
        ->where('faculty_id', $user->id)
        ->get();

    // Get streams for the faculty
    $data['streams'] = Stream::whereIn('id', [50, 3, 4, 5])->orderBy('name', 'ASC')->get();

    // Get mapped semesters for the faculty and course
    $data['mapped_Semesters'] = InternalMarksMapping::select(
        'semesters.name', 'semesters.id', 'internal_marks_mappings.semester_id')
        ->join('semesters', 'semesters.id', '=', 'internal_marks_mappings.semester_id')
        ->distinct()
        ->where('internal_marks_mappings.faculty_id', $user->id)
        ->where('internal_marks_mappings.course_id', $request->course)
        ->get();

    // Get academic sessions
    $data['sessions'] = AcademicSession::orderBy('id', 'asc')->get();

    // Get exam types
    $data['examTypes'] = StudentSubject::distinct('type')->pluck('type')->toArray();

    // Get students for the given subject, course, semester, and type
    $students = [];
    if ($request->sub_code != null) {
        $duplicate_roll_no = InternalMark::where('session', $request->session)
            ->where('sub_code', $request->sub_code)
            ->where('course_id', $request->course)
            ->where('semester_id', $request->semester)
            ->where('type', $request->type)
            ->where('roll_number', 'LIKE', $request->batch . '%')
            ->pluck('roll_number')
            ->toArray();
        
        if ($request->type != 'regular' && $request->type != 'compartment') {
            $backPaperRollNos = BackPaper::join('exam_fees', 'exam_fees.id', '=', 'special_back_table_details.exam_fee_id')
                ->where('exam_fees.academic_session', $request->session)
                ->where('sub_code', $request->sub_code)
                ->where('special_back_table_details.course_id', $request->course)
                ->where('special_back_table_details.semester_id', $request->semester)
                ->where('exam_fees.form_type', $request->type)
                ->where('mid', 1)
                ->where('roll_number', 'LIKE', $request->batch . '%')
                ->distinct('roll_number')
                ->pluck('roll_number')
                ->toArray();
        }

        // Query for students
        $students_query = StudentSubject::has('student')
            ->join('enrollments', 'enrollments.roll_number', '=', 'student_subjects.roll_number')
            ->select(
                'student_subjects.enrollment_number', 'student_subjects.roll_number', 'student_subjects.session',
                'student_subjects.program_id', 'student_subjects.course_id', 'student_subjects.semester_id',
                'student_subjects.sub_code', 'student_subjects.sub_name')
            ->where('student_subjects.sub_code', $request->sub_code)
            ->where('student_subjects.roll_number', 'LIKE', $request->batch . '%')
            ->whereNotIn('student_subjects.roll_number', $duplicate_roll_no);

        if ($request->type != 'regular' && $request->type != 'compartment') {
            $students_query->whereIn('student_subjects.roll_number', $backPaperRollNos);
        }

        $students = $students_query->where('student_subjects.session', $request->session)
            ->where('student_subjects.course_id', $request->course)
            ->where('student_subjects.semester_id', $request->semester)
            ->where('student_subjects.type', $request->type)
            ->distinct('student_subjects.enrollment_number', 'student_subjects.roll_number', 'student_subjects.session')
            ->orderBy('student_subjects.roll_number', 'asc')
            ->paginate(40);

        // If no students found, you can add a fallback query for another data source
    }

    // Get the mapped faculty data
    $mapped_faculty = InternalMarksMapping::where('faculty_id', $user->id)
        ->where('sub_code', $request->sub_code)
        ->where('course_id', $request->course)
        ->where('semester_id', $request->semester)
        ->first();

    // Passing the data to the view
    $data['msg'] = null;  // Message if needed
    $data['mapped_faculty'] = $mapped_faculty;
    $data['students'] = $students;
    $sub_name = Subject::where('sub_code', $request->sub_code)->first();
    $data['sub_name'] = $sub_name;
    $data['sub_code'] = $request->sub_code;
    $data['date_of_semester'] = $request->date_of_semester;
    $data['date_of_assign'] = $request->date_of_semester;
    $data['internal_maximum'] = $request->internal_maximum;
    $data['assign_maximum'] = $request->assign_maximum;

    // For debugging: Check the data of mapped semesters
    // dd('mapped_semesters', $data);

    // Render the view
    return view('ums.master.faculty.add_internal_marks_filling', $data);
}

    


	public function internalMarksShow(Request $request)
    {
		$data['streams'] = Stream::whereIn('id',[50,3,4,5])->orderBy('name','ASC')->get();
		$data['examTypes'] = StudentSubject::distinct('type')->pluck('type')->toArray();
		if($request->faculty_id){
			$faculty_id = request()->get('faculty_id', 46); 
			$user = Faculty::find($faculty_id);
			// $user=Faculty::find($request->faculty_id);
			// Auth::guard('faculty')->login($user);
			// Default to 46 if 'faculty_id' is not provided in the request
            //  
			$retult_check = Result::where(['course_id'=>$request->course,'semester'=>$request->semester,'subject_code'=>$request->sub_code,'status'=>'1'])->get();
			//dd($retult_check->count());
			if($retult_check->count() > 0){
				Result::where(['course_id'=>$request->course,'semester'=>$request->semester,'subject_code'=>$request->sub_code,'status'=>'1'])->forceDelete();
				InternalMark::where(['faculty_id'=>$request->faculty_id,'course_id'=>$request->course,'semester_id'=>$request->semester,'sub_code'=>$request->sub_code])->update(['final_status'=>0]);
				ExternalMark::where(['faculty_id'=>$request->faculty_id,'course_id'=>$request->course,'semester_id'=>$request->semester,'sub_code'=>$request->sub_code])->update(['final_status'=>0]);
				PracticalMark::where(['faculty_id'=>$request->faculty_id,'course_id'=>$request->course,'semester_id'=>$request->semester,'sub_code'=>$request->sub_code])->update(['final_status'=>0]);
			}
		}else{
			// $user=Auth::guard('faculty')->user();
			$faculty_id = request()->get('faculty_id', 46); 
			$user = Faculty::find($faculty_id);
		}

		$mapped_Subjects_query =InternalMarksMapping::select('subjects.*')
		->join('subjects','subjects.sub_code','internal_marks_mappings.sub_code')
		->where('faculty_id',$user->id)
		->where('internal_marks_mappings.course_id',$request->course)
		->where('faculty_id',$user->id)
		->where('subjects.deleted_at',null);
		if($request->semester){
			$mapped_Subjects_query->where('subjects.semester_id',$request->semester);
		}
		$data['mapped_Subjects'] = $mapped_Subjects_query->get();
		$data['mapped_Courses']=InternalMarksMapping::select('courses.name','courses.id','internal_marks_mappings.course_id')
		->join('courses','courses.id','internal_marks_mappings.course_id')
		->distinct()
		->where('faculty_id',$user->id)
		->get();
		$mapped_faculty=InternalMarksMapping::where('faculty_id',$user->id)
		->where('sub_code',$request->sub_code)
		->where('course_id',$request->course)
		->first();
        $data['mapped_faculty'] = $mapped_faculty;
												
		$data['mapped_Semesters']=InternalMarksMapping::select('semesters.name','semesters.id','internal_marks_mappings.semester_id')
		->join('semesters','semesters.id','internal_marks_mappings.semester_id')
		->distinct()
		->where('faculty_id',$user->id)
		->where('internal_marks_mappings.course_id',$request->course)
		->get();
		$data['sub_code_name']='';
		$marks = [];
		if($request->course == 64){
			$marks = InternalMark::where('session',$request->session)->where(['sub_code'=>$request->sub_code,'course_id'=>$request->course,'semester_id'=>$request->semester])
			->where('faculty_id',$user->id)
			->where('type',$request->type)
			->select('internal_marks.*')
			->where('roll_number','LIKE',$request->batch.'%')
			->distinct()
			->orderBy('internal_marks.roll_number')
			->get();

		}else{
			if($request->month_year) {
				$marks = InternalMark::distinct('roll_number')
				->where('session', $request->session)
				->where('sub_code', $request->sub_code)
				->where('course_id', $request->course)
				->where('semester_id', $request->semester)
				->where('type', $request->type)
				->where('faculty_id', $user->id)
				->where('date_of_semester_exam', 'LIKE', $request->month_year.'%')
				->where('roll_number','LIKE',$request->batch.'%')
				->distinct()
				->orderBy('internal_marks.roll_number')
				->get();
			}
		}
		$data['marks'] = $marks;
		$data['details']= InternalMark::where('session',$request->session)
		->where('sub_code', $request->sub_code)
		->where('course_id', $request->course)
		->where('semester_id', $request->semester)
		->where('type',$request->type)
		->where('faculty_id',$user->id)
		->where('roll_number','LIKE',$request->batch.'%')
		->first();
		$data['campuses'] = Campuse::all();

		$data['sessions']=AcademicSession::all();
		$data['selected_course'] = Course::find($request->course);
        return view('ums.master.faculty.internal_marks_filling',$data);
    }

	public function internalMarksDelete(Request $request)
    {
		
		if(Auth::guard('admin')->check()==true){
			$user=Auth::guard('faculty')->user();
			InternalMark::where('session',$request->session)

			->where('sub_code', $request->sub_code)
			->where('course_id', $request->course)
			->where('semester_id', $request->semester)
			->where('faculty_id',$user->id)
			
			->where('type',$request->type)
			->whereIn('roll_number',$request->roll_number)
			->where('date_of_semester_exam','LIKE',$request->month_year.'%')
			
			->update(['sub_code'=>$request->sub_code.'_deleted','deleted_at'=>now()]);
		}
		return back()->with('success','Records deleted successfully');
	}



	public function preview_internal(Request $request){
		$user=Auth::guard('faculty')->user();
		$faculty=$request->all();
		$query=InternalMark::where(['sub_code'=>$request->sub_code,'course_id'=>$request->course,'semester_id'=>$request->semester])
						->where('faculty_id',$user->id);
		$internal_data=$query->get();
		$internal_data_first=$query->first();
		//dd($internal_data);
		return view('ums.master.faculty.internal_marks_filling',['faculty'=>$faculty,'internal_data'=>$internal_data,'internal_data_first'=>$internal_data_first]);
	}

	public function post_internal(Request $request){
		$validator = Validator::make($request->all(), [
            'enrollment_number' => 'required|array',
            'enrollment_number.*' => 'required',
            'internal_maximum' => 'required|numeric',
            'assign_maximum' => 'required|numeric',
            'type' => 'required',
            'mid_semester_marks' => 'required|array',
            'mid_semester_marks' => 'required|array',
            'mid_semester_marks.*' => 'required',
            'assingnment_mark' => 'required|array',
            'assingnment_mark.*' => 'required',
			'comment' => 'required',
		]);

		if ($validator->fails()) {    
			return back()->withErrors($validator)->withInput();
		}

		foreach($request->enrollment_number as $index=>$enrollment_no){
			$internal_mark = InternalMark::where('course_id',$request->course_id)
										->where('roll_number',$request->roll_number[$index])
										->where('semester_id',$request->semester_id)
										->where('sub_code',$request->sub_code)
										->where('session',$request->session)
										->where('type',$request->type)
										->first();
			if(!$internal_mark){
				$student_internal['absent_status'] = $request->absent_status[$index];
				$student_internal['campus_code'] = $request->campus_id;
				$student_internal['campus_name'] = $request->campus_name;
				$student_internal['program_id'] = $request->program_id;
				$student_internal['program_name'] = $request->program_name;
				$student_internal['course_id'] = $request->course_id;
				$student_internal['course_name'] = $request->course_name;
				$student_internal['semester_id'] = $request->semester_id;
				$student_internal['semester_name'] = $request->semester_name;
				$student_internal['session'] = $request->session;
				$student_internal['faculty_id'] = $request->faculty_id;
				$student_internal['sub_code'] = $request->sub_code;
				$student_internal['sub_name'] = $request->subject_name;
				$student_internal['date_of_semester_exam'] = $request->date_of_semester;
				$student_internal['date_of_assignment'] = $request->date_of_semester;
				$student_internal['maximum_mark'] = $request->internal_maximum;
				$student_internal['maximum_mark_assignment'] = $request->assign_maximum;
				$student_internal['student_name'] = $request->student_name[$index];
				$student_internal['enrollment_number'] = $request->enrollment_number[$index];
				$student_internal['roll_number'] = $request->roll_number[$index];
				$student_internal['mid_semester_marks'] = $request->mid_semester_marks[$index];
				$student_internal['assignment_marks'] = $request->assingnment_mark[$index];
				$student_internal['total_marks'] = $request->total_marks[$index];
				$student_internal['equivalent_internal_mark'] = '';
				$student_internal['total_marks_words'] = $request->total_marks_words[$index];
				$student_internal['comment'] = $request->comment[$index];
				$student_internal['type'] = $request->type;
				$student_internal['created_at'] = Carbon::now();
				InternalMark::insert($student_internal);
			}
		}

		return back()->with('success','Record Saved. Click on Show Internal Marks for View and Print');
	}

	public function internal_update(Request $request){
//		dd($request->all());
		$InternalMark = InternalMark::find($request->id);
		if($InternalMark){
			$InternalMark->mid_semester_marks = $request->mid_semester_marks;
			$InternalMark->assignment_marks = $request->assingnment_mark;
			$InternalMark->total_marks = $request->total_marks;
			if(is_numeric($request->total_marks)==true){
				$InternalMark->absent_status = 0;
			}else{
				$InternalMark->absent_status = 1;
			}
			$InternalMark->save();
			return back()->with('success','Saved');
		}
		return back()->with('error','Some Error Occurred');
	}

	public function get_number_in_works(Request $request){
		return numberFormat($request->numbers);
	}
	
	public function internal_submit(Request $request){
		$user=Auth::guard('faculty')->user();
		$marks = InternalMark::where('session',$request->session)
							->where('sub_code',$request->sub_code)
							->where('faculty_id',$user->id)
							->where('course_id',$request->course)
							->where('semester_id',$request->semester)
							->where('final_status',0)
							->get();
		//dd($marks);
			$student_internal_mark=[];
			if( $marks->count()>0 ){
				foreach($marks as $index=>$mark_row){

					$result = Result::where(['roll_no'=>$mark_row->roll_number,'exam_session'=>$mark_row->session,'semester'=> $mark_row->semester_id,'subject_code'=>$mark_row->sub_code])->orderBy('id','desc')->first();
				if(!$result){
					$enrollment_no = $mark_row->enrollment_number;
					$student_internal_mark[$index]['enrollment_no'] = $enrollment_no;
					$student_internal_mark[$index]['roll_no'] = $mark_row->roll_number;
					$student_internal_mark[$index]['exam_session'] = $mark_row->session;
					$student_internal_mark[$index]['semester'] = $mark_row->semester_id;
					$student_internal_mark[$index]['course_id'] = $mark_row->course_id;
					$student_internal_mark[$index]['subject_code'] = $mark_row->sub_code;
					$total = $mark_row->total_marks;
					if(is_numeric($total)){
						$student_internal_mark[$index]['internal_marks'] = $mark_row->assignment_marks;
						$student_internal_mark[$index]['oral'] = $mark_row->mid_semester_marks;
					}
					else{
						$student_internal_mark[$index]['internal_marks'] = 'ABSENT';
						$student_internal_mark[$index]['oral'] = 'ABSENT';
					}
					$student_internal_mark[$index]['total_marks'] = $mark_row->total_marks;
					$student_internal_mark[$index]['status'] = 1;
					$student_internal_mark[$index]['created_at'] = Carbon::now();
					Result::insert($student_internal_mark[$index]);
				}else{
					$result->internal_marks = $mark_row->assignment_marks;
					$result->oral = $mark_row->mid_semester_marks;
					$result->total_marks = ((integer)$mark_row->total_marks + (integer)$result->external_marks + (integer)$result->practical_marks);
					$result->status = 1;
					$result->updated_at = Carbon::now();
					$result->save();
				}
				InternalMark::where('roll_number',$mark_row->roll_number)
					->where('session',$mark_row->session)
					->where('semester_id',$mark_row->semester_id)
					->where('sub_code',$mark_row->sub_code)
					->where('faculty_id',$user->id)
					->update(['final_status'=>1]);
			}
		}
		return back()->with('success','Data Submitted Succesfully');
	}

	// public function get_semester(Request $request)
	// {
	// 	$html='<option value="">--Select Semester--</option>';
	// 	$user=Auth::guard('faculty')->user();
	// 	$mapped_Semester=InternalMarksMapping::select('semesters.name','semesters.id','internal_marks_mappings.semester_id')
	// 	->join('semesters','semesters.id','internal_marks_mappings.semester_id')
	// 	->where('faculty_id',$user->id)
	// 	->where('internal_marks_mappings.course_id',$request->course)
	// 	->distinct()
	// 	->orderBy('semesters.semester_number')
	// 	->get();
	// 	foreach($mapped_Semester as $sc){
	// 		$html.='<option value="'.$sc->id.'">'.$sc->name.'</option>';
	// 	}
	// 	return $html;
	// 	//dd($mapped_Semester);
	// }
	public function get_semester(Request $request,$id )
{
	// dd($request->all);
    try {
		// Check if 'course' is present in the request
        if (!$id) {
			return response()->json(['error' => 'Course parameter is missing'], 400);
        }
		
      
		$semesters = Semester::where('course_id', $id)->get();
		
		

        // Make sure the course exists in your database

        if ($semesters->isEmpty()) {
            return response()->json(['error' => 'No semesters found for this course'], 404);
        }

        // Generate HTML for options
        $html = '<option value="">--Select Semester--</option>';
        foreach ($semesters as $semester) {
            $html .= '<option value="' . $semester->id . '">' . $semester->name . '</option>';
        }

        return response()->json(['html' => $html]);

    } catch (\Exception $e) {
        // Log any exceptions
        \Log::error('Error fetching semesters: ' . $e->getMessage());
        return response()->json(['error' => 'An error occurred while processing your request'], 500);
    }
}
	public function get_subject(Request $request)
	{
		if($request->permissions){
			$permissions = $request->permissions;
		}else{
			$permissions = 0;
		}

		//dd($request->all());
		if($request->pr){
			//dd($request->pr);
		$html='<option value="">--Select Subject--</option>';
		$user=Auth::guard('faculty')->user();
		$mapped_Subjects=InternalMarksMapping::select('subjects.name', 'subjects.sub_code', 'subjects.back_fees', 'subjects.scrutiny_fee', 'subjects.challenge_fee', 'subjects.status', 'subjects.subject_type', 'subjects.type', 'subjects.internal_maximum_mark', 'subjects.maximum_mark', 'subjects.minimum_mark', 'subjects.credit', 'subjects.internal_marking_type', 'subjects.combined_subject_name')
				->join('subjects',function($join){
					$join->on('subjects.sub_code','internal_marks_mappings.sub_code')
					->on('subjects.semester_id','internal_marks_mappings.semester_id');
				})
				->distinct()
				->orderBy('sub_code')
				->where('faculty_id',$user->id)
				->where('subjects.subject_type',$request->pr)
				->where('subjects.deleted_at',null)
				->whereIn('permissions',[0,$permissions])
				->where(['internal_marks_mappings.course_id'=>$request->course,'internal_marks_mappings.semester_id'=>$request->semester])
				->get();
				foreach($mapped_Subjects as $sc){
					$html.='<option value="'.$sc->sub_code.'">'.$sc->sub_code.'('.$sc->name.')'.'('.$sc->subject_type.')'.'</option>';
				}
				return $html;
		}
		elseif($request->th){
			//dd($mapped_Subjects);
			$html='<option value="">--Select Subject--</option>';
			$user=Auth::guard('faculty')->user();
			$mapped_Subjects=InternalMarksMapping::select('subjects.name', 'subjects.sub_code', 'subjects.back_fees', 'subjects.scrutiny_fee', 'subjects.challenge_fee', 'subjects.status', 'subjects.subject_type', 'subjects.type', 'subjects.internal_maximum_mark', 'subjects.maximum_mark', 'subjects.minimum_mark', 'subjects.credit', 'subjects.internal_marking_type', 'subjects.combined_subject_name')
					->join('subjects',function($join){
						$join->on('subjects.sub_code','internal_marks_mappings.sub_code')
						->on('subjects.semester_id','internal_marks_mappings.semester_id');
					})
					->distinct()
					->orderBy('sub_code')
					->where('faculty_id',$user->id)
					->where('subjects.subject_type',$request->th)
					->where('subjects.deleted_at',null)
					->whereIn('permissions',[0,$permissions])
					->where(['internal_marks_mappings.course_id'=>$request->course,'internal_marks_mappings.semester_id'=>$request->semester])
					->get();
			foreach($mapped_Subjects as $sc){
				$html.='<option value="'.$sc->sub_code.'">'.$sc->sub_code.'('.$sc->name.')'.'('.$sc->subject_type.')'.'</option>';
			}
			return $html;
		}
		else{
		//dd($mapped_Subjects);
		$html='<option value="">--Select Subject--</option>';
		$user=Auth::guard('faculty')->user();
		$mapped_Subjects=InternalMarksMapping::select('subjects.name', 'subjects.sub_code', 'subjects.back_fees', 'subjects.scrutiny_fee', 'subjects.challenge_fee', 'subjects.status', 'subjects.subject_type', 'subjects.type', 'subjects.internal_maximum_mark', 'subjects.maximum_mark', 'subjects.minimum_mark', 'subjects.credit', 'subjects.internal_marking_type', 'subjects.combined_subject_name')
				->join('subjects',function($join){
					$join->on('subjects.sub_code','internal_marks_mappings.sub_code')
					->on('subjects.semester_id','internal_marks_mappings.semester_id');
				})
				->distinct()
				->orderBy('sub_code')
				->where('faculty_id',$user->id)
				->where('subjects.deleted_at',null)
				->whereIn('permissions',[0,$permissions])
				->where(['internal_marks_mappings.course_id'=>$request->course,'internal_marks_mappings.semester_id'=>$request->semester])
				->get();

		foreach($mapped_Subjects as $sc){
			$html.='<option value="'.$sc->sub_code.'">'.$sc->sub_code.'('.$sc->name.')'.'('.$sc->subject_type.')'.'</option>';
		}
		return $html;
	}
		//dd($mapped_Semester);
	}

	
	

	public function internalMarksExport(Request $request)
	{
		return Excel::download(new InternalMarksExport($request),'InternalMark.xlsx');
	}

	public function get_month_year(Request $request)
	{
		$html='';
		$type = $request->table_type;

		if($type=='InternalMark'){
			$month_years = InternalMark::select(DB::raw('MONTH(date_of_semester_exam) month'),DB::raw('YEAR(date_of_semester_exam) year'))
			->where('course_id',$request->course)
			->where('semester_id',$request->semester)
			->where('session',$request->session)
			->where('sub_code',$request->sub_code)
			->where('type',$request->type)
			->distinct()
			->get();
		}
		if($type=='ExternalMark'){
			$month_years = ExternalMark::select(DB::raw('MONTH(date_of_semester_exam) month'),DB::raw('YEAR(date_of_semester_exam) year'))
			->where('course_id',$request->course)
			->where('semester_id',$request->semester)
			->where('session',$request->session)
			->where('sub_code',$request->sub_code)
			->where('type',$request->type)
			->distinct()
			->get();
		}
		if($type=='PracticalMark'){
			$month_years = PracticalMark::select(DB::raw('MONTH(date_of_practical_exam) month'),DB::raw('YEAR(date_of_practical_exam) year'))
			->where('course_id',$request->course)
			->where('semester_id',$request->semester)
			->where('session',$request->session)
			->where('sub_code',$request->sub_code)
			->where('type',$request->type)
			->distinct()
			->get();
		}
		foreach($month_years as $month_year){
			$month_year_text = $month_year->year.'-'.sprintf('%02d', $month_year->month);
			$month_year_name = date('M',strtotime('01-'.$month_year->month.'-2023')).'-'.$month_year->year;
			$html .= '<option value="'.$month_year_text.'">'.$month_year_name.'</option>';
		}
		return $html;
	}

}