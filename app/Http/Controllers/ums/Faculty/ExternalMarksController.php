<?php

namespace App\Http\Controllers\Faculty;

use View;
use Auth;
use App\User;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use App\Models\Campuse;
use App\Models\BackPaper;
use App\Models\Stream;
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
use App\Models\ExternalMark;
use App\Models\MbbsExamForm;
use App\Models\AwardSheetFile;
use Carbon\Carbon;
use Validator;

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
		$data['streams'] = Stream::whereIn('id',[50,3,4,5])->orderBy('name','ASC')->get();
		$data['examTypes'] = StudentSubject::distinct('type')->pluck('type')->toArray();
		$data['sub_code']= $data['sub_name']= $data['date_of_semester']= $data['date_of_assign']= $data['external_maximum']= $data['mapped_faculty']= $mapped_faculty =$data['mapped_Semesters']= null;
		$user=Auth::guard('faculty')->user();
		$mapped_Subjects_query = InternalMarksMapping::select('subjects.name', 'subjects.sub_code', 'subjects.back_fees', 'subjects.scrutiny_fee', 'subjects.challenge_fee', 'subjects.status', 'subjects.subject_type', 'subjects.type', 'subjects.internal_maximum_mark', 'subjects.maximum_mark', 'subjects.minimum_mark', 'subjects.credit', 'subjects.internal_marking_type', 'subjects.combined_subject_name')
		->join('subjects',function($join){
			$join->on('subjects.sub_code','internal_marks_mappings.sub_code')
			->on('subjects.course_id','internal_marks_mappings.course_id')
			->on('subjects.semester_id','internal_marks_mappings.semester_id');
		})
		->distinct()
		->orderBy('sub_code')
		->where('faculty_id',$user->id)
		->where('subjects.subject_type','Theory');
		if($request->semester){
			$mapped_Subjects_query->where('subjects.semester_id',$request->semester);
		}
		$data['mapped_Subjects'] = $mapped_Subjects_query->get();
		$data['mapped_Courses']=InternalMarksMapping::select('courses.name','courses.id','internal_marks_mappings.course_id')
		->join('courses','courses.id','internal_marks_mappings.course_id')
		->distinct()
		->where('faculty_id',$user->id)
		->get();
		$data['selected_course'] = Course::find($request->course);
		$data['mapped_Semesters']=InternalMarksMapping::select('semesters.name','semesters.id','internal_marks_mappings.semester_id')
		->join('semesters','semesters.id','internal_marks_mappings.semester_id')
		->distinct()
		->where('faculty_id',$user->id)
		->where('internal_marks_mappings.course_id',$request->course)
		->get();
		
		$data['sessions']=AcademicSession::all();
		
    	$msg=null;
        $subject=null;
        $students= [];
	   	if($request->sub_code!=null)
        {
            $duplicate_roll_no = ExternalMark::where('session',$request->session)
			->where('sub_code',$request->sub_code)
			->where('semester_id',$request->semester)
			->where('type',$request->type)
			->where('roll_number','LIKE',$request->batch.'%')
			->pluck('roll_number')
			->toArray();

			if($request->type!='regular'){
				$backPaperRollNos = BackPaper::join('exam_fees','exam_fees.id','special_back_table_details.exam_fee_id')
				->where('exam_fees.academic_session',$request->session)
				->where('sub_code', $request->sub_code)
				->where('special_back_table_details.course_id', $request->course)
				->where('special_back_table_details.semester_id', $request->semester)
				->where('exam_fees.form_type',$request->type)
				->where('external',1)
                ->where('roll_number','LIKE',$request->batch.'%')
				->distinct('roll_number')
				->pluck('roll_number')
				->toArray();
			}
			$students_query =  StudentSubject::has('student')
			->join('enrollments','enrollments.roll_number','student_subjects.roll_number')
			->select('student_subjects.enrollment_number', 'student_subjects.roll_number', 'student_subjects.session', 'student_subjects.program_id', 'student_subjects.course_id', 'student_subjects.semester_id', 'student_subjects.sub_code', 'student_subjects.sub_name')
			->where('student_subjects.sub_code',$request->sub_code)
			->where('student_subjects.roll_number','LIKE',$request->batch.'%')
			->whereNotIn('student_subjects.roll_number',$duplicate_roll_no);
			if($request->type!='regular' && $request->type!='compartment'){
				$students_query->whereIn('student_subjects.roll_number',$backPaperRollNos);
			}
			$students = $students_query->where('student_subjects.session',$request->session)
			->where('student_subjects.course_id',$request->course)
			->where('student_subjects.semester_id',$request->semester)
			->where('student_subjects.type',$request->type)
			->distinct('student_subjects.enrollment_number', 'student_subjects.roll_number', 'student_subjects.session', 'student_subjects.program_id', 'student_subjects.course_id', 'student_subjects.semester_id', 'student_subjects.sub_code', 'student_subjects.sub_name')
			->orderBy('student_subjects.roll_number','asc')
			->paginate(40);
			$mapped_faculty=InternalMarksMapping::with('Course')
			->where('faculty_id',$user->id)
			->where('sub_code',$request->sub_code)
			->where('course_id',$request->course)
			->first();
        }
		$data['msg']=$msg;
        $data['students'] = $students;
		$data['sub_code']=$request->sub_code;
		$data['subject']=$subject;
		$sub_name = Subject::withTrashed()->where('sub_code', $request->sub_code)->first();
		$data['sub_name']=$sub_name;
		$data['external_maximum']=$request->external_maximum;
		$data['date_of_semester']=$request->date_of_semester;
		$data['mapped_faculty'] = $mapped_faculty;
		$data['campuses'] = Campuse::all();
        return view('faculty.external.add',$data);
    }
	public function externalMarksShow(Request $request)
    {
		$data['streams'] = Stream::whereIn('id',[50,3,4,5])->orderBy('name','ASC')->get();
		$data['examTypes'] = StudentSubject::distinct('type')->pluck('type')->toArray();
		$user=Auth::guard('faculty')->user();
		$data['sub_code_name']=null;
		$mapped_Subjects_query = InternalMarksMapping::select('subjects.*')
		->join('subjects','subjects.sub_code','internal_marks_mappings.sub_code')
		->where('faculty_id',$user->id)
		->where('internal_marks_mappings.course_id',$request->course);
		// ->where('subjects.deleted_at',null);
		if($request->semester){
			$mapped_Subjects_query->where('subjects.semester_id',$request->semester);
		}
		$data['mapped_Subjects'] = $mapped_Subjects_query->get();
		$data['mapped_Courses']=InternalMarksMapping::select('courses.name','courses.id','internal_marks_mappings.course_id')
		->join('courses','courses.id','internal_marks_mappings.course_id')
		->distinct()
		->where('faculty_id',$user->id)
		->get();
		$data['mapped_Semesters']=InternalMarksMapping::select('semesters.name','semesters.id','internal_marks_mappings.semester_id')
		->join('semesters','semesters.id','internal_marks_mappings.semester_id')
		->distinct()
		->where('faculty_id',$user->id)
		->where('internal_marks_mappings.course_id',$request->course)
		->get();
		$mapped_faculty=InternalMarksMapping::with('Course')
		->where('faculty_id',$user->id)
		->where('sub_code',$request->sub_code)
		->where('course_id',$request->course)
		->first();
		$data['mapped_faculty'] = $mapped_faculty;
		$marks = [];
		if($request->course == 64){
			$marks = ExternalMark::where('session',$request->session)
			->where('sub_code', $request->sub_code)
			->where('course_id', $request->course)
			->where('semester_id', $request->semester)
			->where('faculty_id',$user->id)
			->where('type',$request->type)
			->where('roll_number','LIKE',$request->batch.'%')
			->distinct()
			->orderBy('roll_number')
			->get();
		}else{
			$marks = ExternalMark::where('session',$request->session)
			->where('sub_code', $request->sub_code)
			->where('course_id', $request->course)
			->where('semester_id', $request->semester)
			->where('faculty_id',$user->id)
			->where('type',$request->type)
			->where('roll_number','LIKE',$request->batch.'%')
			->where('date_of_semester_exam','LIKE',$request->month_year.'%')
			->distinct()
			->orderBy('roll_number')
			->get();
		}
		$data['marks'] = $marks;
		$data['marks_count']= ExternalMark::where('session',$request->session)
		->where('sub_code',$request->sub_code)
		->where('type',$request->type)
		->where('faculty_id',$user->id)
		->where('final_status',0)
		->where('roll_number','LIKE',$request->batch.'%')
		->get()
		->count();
			

		$data['details']= ExternalMark::where('session',$request->session)
		->where('sub_code',$request->sub_code)
		->where('faculty_id',$user->id)
		->where('type',$request->type)
		->where('roll_number','LIKE',$request->batch.'%')
		->first();
		$data['campuses'] = Campuse::all();
		$data['sessions']=AcademicSession::all();
		$data['selected_course'] = Course::find($request->course);
		$data['campuses_single'] = Campuse::where('id', $request->campuse_id)->first();
        return view('faculty.external.show',$data);
    }
	public function externalMarksDelete(Request $request)
    {
		if(Auth::guard('admin')->check()==true){
			$user=Auth::guard('faculty')->user();
			ExternalMark::where('session',$request->session)
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
	public function external_submit(Request $request)
	{
		$user=Auth::guard('faculty')->user();
		//	dd($request->course);
		//$count=0;
		//dd($external);
		$student_internal_mark=[];

		$marks = ExternalMark::where('session',$request->session)
			->where('sub_code',$request->sub_code)
			->where('course_id',$request->course)
			->where('faculty_id',$user->id)
			->where('final_status',0)
			->get();

		if( $marks->count()>0 ){
		$count=0;
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
					$student_internal_mark[$index]['external_marks'] = $mark_row->semester_marks;
				}
				else{
					$student_internal_mark[$index]['external_marks'] = 'ABSENT';
				}
				$student_internal_mark[$index]['total_marks'] = $mark_row->total_marks;
				$student_internal_mark[$index]['status'] = 1;
				$student_internal_mark[$index]['created_at'] = Carbon::now();
				Result::insert($student_internal_mark[$index]);
			}else{
				$result->external_marks = $mark_row->semester_marks;
				$result->total_marks = ((integer)$mark_row->total_marks + (integer)$result->internal_marks + (integer)$result->practical_marks + (integer)$result->oral);
				$result->status = 1;
				$result->updated_at = Carbon::now();
				$result->save();
			}
			ExternalMark::where('roll_number',$mark_row->roll_number)
				->where('session',$mark_row->session)
				->where('semester_id',$mark_row->semester_id)
				->where('sub_code',$mark_row->sub_code)
				->where('faculty_id',$user->id)
				->update(['final_status'=>1]);
		}
	}
		//dd($request->course_id,$request->semester_id,$request->sub_code);
		return back()->with('success','Marks Filled Successfully');
	}
	public function post_external(Request $request){
		
		$validator = Validator::make($request->all(), [
            'enrollment_number' => 'required|array',
            'enrollment_number.*' => 'required',
            'type' => 'required',
			'comment' => 'required',
            'external_marks' => 'required|array',
            'external_marks.*' => 'required',
		]);

		if ($validator->fails()) {    
			return back()->withErrors($validator)->withInput();
		}
       
		$student_external = array();
		foreach($request->enrollment_number as $index=>$enrollment_no){

			$check_duplicate = ExternalMark::where('course_id',$request->course_id)
			->where('roll_number',$request->roll_number[$index])
			->where('semester_id',$request->semester)
			->where('sub_code',$request->sub_code)
			->where('session',$request->session)
			->where('type',$request->type)
			->first();
			// $student_external[$index]['award_sheet_file_id'] = $file_id;
			if(!$check_duplicate){
				$student_external[$index]['absent_status'] = $request->absent_status[$index];
				$student_external[$index]['campus_code'] = $request->campus_id[$index];
				$student_external[$index]['campus_name'] = $request->campus_name[$index];
				$student_external[$index]['program_id'] = $request->program_id[$index];
				$student_external[$index]['program_name'] = $request->program_name[$index];
				$student_external[$index]['course_id'] = $request->course;
				$student_external[$index]['course_name'] = $request->course_name[$index];
				$student_external[$index]['semester_id'] = $request->semester;
				$student_external[$index]['semester_name'] = $request->semester_name[$index];
				$student_external[$index]['session'] = $request->session;
				$student_external[$index]['faculty_id'] = $request->faculty_id[$index];
				$student_external[$index]['sub_code'] = $request->sub_code;
				$student_external[$index]['sub_name'] = $request->subject_name[$index];
				$student_external[$index]['date_of_semester_exam'] = $request->semester_date[$index];
				$student_external[$index]['maximum_mark'] = $request->maximum_external[$index];
				$student_external[$index]['student_name'] = $request->student_name[$index];
				$student_external[$index]['enrollment_number'] = $request->enrollment_number[$index];
				$student_external[$index]['roll_number'] = $request->roll_number[$index];
				$student_external[$index]['semester_marks'] = $request->external_marks[$index];
				$student_external[$index]['total_marks'] = $request->total_marks[$index];
				$student_external[$index]['total_marks_words'] = $request->total_marks_words[$index];
				$student_external[$index]['type'] = $request->type;
				$student_external[$index]['comment'] = $request->comment[$index];
				$student_external[$index]['created_at'] = Carbon::now();
			}
		}
		if(count($student_external)>0){
			ExternalMark::insert($student_external);
		}
		return back()->with('success','Record Saved. Click on Show External Marks for View and Print');
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
