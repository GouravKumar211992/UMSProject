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
use App\Models\MbbsExamForm;
use App\Models\StudentSubject;
use App\Models\StudentsemesterFee;
use App\Models\InternalMarksMapping;
use App\Models\AcademicSession;
use App\Models\InternalMark;
use Carbon\Carbon;
use App\Exports\InternalMarksExport;
use Maatwebsite\Excel\Facades\Excel;

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

		return view('faculty.internal.index', [
            'internal_marks' => $internal_marks
        ]);
    } 
	public function internal(Request $request)
    {	
		$data['sub_code']= $data['sub_name']= $data['date_of_semester']= $data['date_of_assign']= $data['assign_maximum']= $data['mapped_faculty']= $mapped_faculty =$data['mapped_Semesters']=$data['mapped_Subjects']= null;
		$user=Auth::guard('faculty')->user();
		$data['mapped_Subjects']=InternalMarksMapping::select('subjects.name', 'subjects.sub_code', 'subjects.back_fees', 'subjects.scrutiny_fee', 'subjects.challenge_fee', 'subjects.status', 'subjects.subject_type', 'subjects.type', 'subjects.internal_maximum_mark', 'subjects.maximum_mark', 'subjects.minimum_mark', 'subjects.credit', 'subjects.internal_marking_type', 'subjects.combined_subject_name')
				->join('subjects',function($join){
					$join->on('subjects.sub_code','internal_marks_mappings.sub_code')
					//->on('subjects.course_id','internal_marks_mappings.course_id')
					->on('subjects.semester_id','internal_marks_mappings.semester_id');
				})
				->distinct()
				->orderBy('sub_code')
				->where('subjects.deleted_at',null)
				->where('faculty_id',$user->id)
				->get();
		//dd($data['mapped_Subjects']);
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
		
		//dd($data['mapped_Semester']);
		$data['sessions']=AcademicSession::all();
		
    	$msg=null;
        $subject=null;
        $students= [];
	   if($request->sub_code!=null)
        {
			$duplicate_roll_no = InternalMark::where('session',$request->session)->where('sub_code',$request->sub_code)->pluck('roll_number')->toArray();
			//dd($duplicate_roll_no);
			$students = StudentSubject::has('student')
							->join('enrollments','enrollments.roll_number','student_subjects.roll_number')
							->join('applications','applications.id','enrollments.application_id')
							->select('student_subjects.enrollment_number', 'student_subjects.roll_number', 'student_subjects.session', 'student_subjects.program_id', 'student_subjects.course_id', 'student_subjects.semester_id', 'student_subjects.sub_code', 'student_subjects.sub_name')
							->where('student_subjects.sub_code',$request->sub_code)
							->whereNotIn('student_subjects.roll_number',$duplicate_roll_no)
							->where('student_subjects.session',$request->session)
							->where('student_subjects.course_id',$request->course)
							->where('student_subjects.semester_id',$request->semester)
							->where('applications.campuse_id',Auth::guard('faculty')->user()->campuse_id)
							->distinct('student_subjects.enrollment_number', 'student_subjects.roll_number', 'student_subjects.session', 'student_subjects.program_id', 'student_subjects.course_id', 'student_subjects.semester_id', 'student_subjects.sub_code', 'student_subjects.sub_name')
							->orderBy('student_subjects.roll_number','asc')
							->paginate(40);
			//dd($students);
			if(count($students)==0){
			$students = MbbsExamForm::with('student')->has('student')
							->where('sub_code',$request->sub_code)
							->whereNotIn('rollno',$duplicate_roll_no)
							->orderBy('rollno','ASC')
							->paginate(40);
							//dd($students->get());
						
			$mapped_faculty=InternalMarksMapping::where('faculty_id',$user->id)
												->where('sub_code',$request->sub_code)
												->first();
		$data['msg']=$msg;
        $data['mapped_faculty'] = $mapped_faculty;
        $data['students'] = $students;
		$sub_name=Subject::where('sub_code', $request->sub_code)->first();
		$data['sub_name']=$sub_name;
		$data['sub_code']=$request->sub_code;
		$data['date_of_semester']=$request->date_of_semester;
		$data['date_of_assign']=$request->date_of_assign;
		$data['internal_maximum']=$request->internal_maximum;
		$data['assign_maximum']=$request->assign_maximum;
		if($request->course==49){

		return view('ums.master.faculty.internal_marks_filling',$data);
			}
		}
		//dd($students);
			$mapped_faculty=InternalMarksMapping::where('faculty_id',$user->id)
												->where('sub_code',$request->sub_code)
												->first();
        
        }
		//dd($students->get());
		$data['msg']=$msg;
        $data['mapped_faculty'] = $mapped_faculty;
        $data['students'] = $students;
		$sub_name=Subject::where('sub_code', $request->sub_code)->first();
		$data['sub_name']=$sub_name;
		$data['sub_code']=$request->sub_code;
		$data['date_of_semester']=$request->date_of_semester;
		$data['date_of_assign']=$request->date_of_assign;
		$data['internal_maximum']=$request->internal_maximum;
		$data['assign_maximum']=$request->assign_maximum;
    	//dd($students);
        return view('faculty.internal.add',$data);
    }
    


	public function internalMarksShow(Request $request)
    {
		$user=Auth::guard('faculty')->user();
		$data['mapped_Subjects']=InternalMarksMapping::select('subjects.*')
				->join('subjects','subjects.sub_code','internal_marks_mappings.sub_code')
				->where('faculty_id',$user->id)
				->where('subjects.deleted_at',null)
				->get();

	

		$data['marks']= InternalMark::where('session',$request->session)->where('sub_code',$request->sub_code)
		->where('faculty_id',$user->id)->get();
		$data['details']= InternalMark::where('session',$request->session)->where('sub_code',$request->sub_code)
		->where('faculty_id',$user->id)->first();
		$data['sessions']=AcademicSession::all();
    	 //dd($data);
        return view('faculty.internal.show',$data);
    }

	public function preview_internal(Request $request){
		$user=Auth::guard('faculty')->user();
		$faculty=$request->all();
		$query=InternalMark::where('sub_code',$request->sub_code)
						->where('session',$request->session)
						->where('faculty_id',$user->id);
		$internal_data=$query->get();
		$internal_data_first=$query->first();
		
		
		
		//dd($internal_data);
		return view('faculty.internal.internal-mark-preview',['faculty'=>$faculty,'internal_data'=>$internal_data,'internal_data_first'=>$internal_data_first]);
	}
	public function post_internal(Request $request){
		
		foreach($request->enrollment_number as $index=>$enrollment_no){
			$internal_mark=InternalMark::where(['sub_code'=> $request->subject_code[$index],'roll_number'=> $request->roll_number[$index],'session'=>$request->session[$index]])->first();
			if(!$internal_mark){
			$student_internal[$index]['absent_status'] = $request->absent_status[$index];
			$student_internal[$index]['campus_code'] = $request->campus_id[$index];
			$student_internal[$index]['campus_name'] = $request->campus_name[$index];
			$student_internal[$index]['program_id'] = $request->program_id[$index];
			$student_internal[$index]['program_name'] = $request->program_name[$index];
			$student_internal[$index]['course_id'] = $request->course_id[$index];
			$student_internal[$index]['course_name'] = $request->course_name[$index];
			$student_internal[$index]['semester_id'] = $request->semester_id[$index];
			$student_internal[$index]['semester_name'] = $request->semester_name[$index];
			$student_internal[$index]['session'] = $request->session[$index];
			$student_internal[$index]['faculty_id'] = $request->faculty_id[$index];
			$student_internal[$index]['sub_code'] = $request->subject_code[$index];
			$student_internal[$index]['sub_name'] = $request->subject_name[$index];
			$student_internal[$index]['date_of_semester_exam'] = $request->semester_date[$index];
			$student_internal[$index]['date_of_assignment'] = $request->assign_date[$index];
			$student_internal[$index]['maximum_mark'] = $request->maximum_internal[$index];
			$student_internal[$index]['maximum_mark_assignment'] = $request->maximum_assign[$index];
			$student_internal[$index]['student_name'] = $request->student_name[$index];
			$student_internal[$index]['enrollment_number'] = $request->enrollment_number[$index];
			$student_internal[$index]['roll_number'] = $request->roll_number[$index];
			$student_internal[$index]['mid_semester_marks'] = $request->mid_semester_marks[$index];
			$student_internal[$index]['assignment_marks'] = $request->assingnment_mark[$index];
			$student_internal[$index]['total_marks'] = $request->total_marks[$index];
			$total=$request->total_marks[$index];
			if(is_numeric($total)){
				$max_total=$request->maximum_internal[$index]+$request->maximum_assign[$index];
				$percentage =($total/$max_total)*100;
				$percentage =number_format((float)$percentage, 2, '.', '');
				$internal_max=$request->max_internal[$index];
				$equivalent_interanl=($percentage*$internal_max)/100;
				$equivalent_interanl=ceil($equivalent_interanl);
				$student_internal[$index]['equivalent_internal_mark'] = $equivalent_interanl;
				$student_internal[$index]['total_marks_words'] = $request->total_marks_words[$index];
			}else{
				$student_internal[$index]['equivalent_internal_mark'] = '';
				$student_internal[$index]['total_marks_words'] = $request->total_marks_words[$index];
			}
			$student_internal[$index]['created_at'] = Carbon::now();

		}
	}
	if(count($student_internal)>0){
		InternalMark::insert($student_internal);
	}else{
		return back()->with('error','Data Already Exists.');
	}
		
		return redirect('faculty/internal-marks-show/?session='.$request->session[0].'&sub_code='.$request->subject_code[0]);
		
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
							->where('course_id','!=',49)
							->get();
			$student_internal_mark=[];
			if( $marks->count()>0 ){
				foreach($marks as $index=>$mark_row){
					
				$result=Result::where(['enrollment_no'=>$mark_row->enrollment_number,'exam_session'=>$mark_row->session,'semester'=> $mark_row->semester_id,'subject_code'=>$mark_row->sub_code])->orderBy('id','desc')->first();
				if(!$result){
						
					$enrollment_no = $mark_row->enrollment_number;
					$student_internal_mark[$index]['enrollment_no'] = $enrollment_no;
					$student_internal_mark[$index]['roll_no'] = $mark_row->roll_number;
					$student_internal_mark[$index]['exam_session'] = $mark_row->session;
					$student_internal_mark[$index]['semester'] = $mark_row->semester_id;
					$student_internal_mark[$index]['course_id'] = $mark_row->course_id;
					$student_internal_mark[$index]['subject_code'] = $mark_row->sub_code;
					$student_internal_mark[$index]['status'] = 1;
					$total = $mark_row->total_marks;

					if(is_numeric($total)){
						$max_total = $mark_row->maximum_mark + $mark_row->maximum_mark_assignment;
						$percentage =($total/$max_total)*100;
						$percentage =number_format((float)$percentage, 2, '.', '');
						$internal_max=$mark_row->maximum_mark+$mark_row->maximum_mark_assignment;
						//dd($internal_max);
						if($max_total==30)
						{
							$equivalent_interanl=$total;	
						}
						else{
						$equivalent_interanl=($percentage*$internal_max)/100;
						$equivalent_interanl=ceil($equivalent_interanl);
						}
						//dd($equivalent_interanl);
						$student_internal[$index]['equivalent_internal_mark'] = $equivalent_interanl;
						$student_internal_mark[$index]['internal_marks'] = $equivalent_interanl;
						
					}
					else{
						$student_internal[$index]['equivalent_internal_mark'] = 'ABSENT';
						$student_internal_mark[$index]['internal_marks'] = 'ABSENT';
					}
					$student_internal_mark[$index]['created_at'] = Carbon::now();
				}
			}
			//dd($student_internal_mark);
			if( COUNT($student_internal_mark) > 0 ){
				Result::insert($student_internal_mark);
				InternalMark::where('sub_code',$request->sub_code)->where('faculty_id',$user->id)->update(['final_status'=>1]);
			}else{
				return back()->with('error','Data Already Exists.');
			}

		}

		return redirect('faculty/internal-marks-show/?session='.$request->session.'&sub_code='.$request->sub_code)->with('Success','Data Submitted Succesfully');
	}

	public function get_semester(Request $request)
{
    $html = '<option value="">--Select Semester--</option>';
    
    $mapped_Semester = InternalMarksMapping::select('semesters.name', 'semesters.id', 'internal_marks_mappings.semester_id')
        ->join('semesters', 'semesters.id', '=', 'internal_marks_mappings.semester_id')
        ->distinct()
        ->where('internal_marks_mappings.course_id', $request->course)
        ->get();

    foreach ($mapped_Semester as $sc) {
        $html .= '<option value="' . $sc->id . '">' . $sc->name . '</option>';
    }

    return $html;
}

	public function get_subject(Request $request)
	{
		//dd($request->all());
		if($request->pr){
			//dd($request->pr);
		$html='<option value="">--Select Subject--</option>';
		$mapped_Subjects=InternalMarksMapping::select('subjects.name', 'subjects.sub_code', 'subjects.back_fees', 'subjects.scrutiny_fee', 'subjects.challenge_fee', 'subjects.status', 'subjects.subject_type', 'subjects.type', 'subjects.internal_maximum_mark', 'subjects.maximum_mark', 'subjects.minimum_mark', 'subjects.credit', 'subjects.internal_marking_type', 'subjects.combined_subject_name')
				->join('subjects',function($join){
					$join->on('subjects.sub_code','internal_marks_mappings.sub_code')
					->on('subjects.semester_id','internal_marks_mappings.semester_id');
				})
				->distinct()
				->orderBy('sub_code')
				->where('subjects.subject_type',$request->pr)
				->where('subjects.deleted_at',null)
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
			$mapped_Subjects=InternalMarksMapping::select('subjects.name', 'subjects.sub_code', 'subjects.back_fees', 'subjects.scrutiny_fee', 'subjects.challenge_fee', 'subjects.status', 'subjects.subject_type', 'subjects.type', 'subjects.internal_maximum_mark', 'subjects.maximum_mark', 'subjects.minimum_mark', 'subjects.credit', 'subjects.internal_marking_type', 'subjects.combined_subject_name')
					->join('subjects',function($join){
						$join->on('subjects.sub_code','internal_marks_mappings.sub_code')
						->on('subjects.semester_id','internal_marks_mappings.semester_id');
					})
					->distinct()
					->orderBy('sub_code')
					->where('subjects.subject_type',$request->th)
					->where('subjects.deleted_at',null)
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
		$mapped_Subjects=InternalMarksMapping::select('subjects.name', 'subjects.sub_code', 'subjects.back_fees', 'subjects.scrutiny_fee', 'subjects.challenge_fee', 'subjects.status', 'subjects.subject_type', 'subjects.type', 'subjects.internal_maximum_mark', 'subjects.maximum_mark', 'subjects.minimum_mark', 'subjects.credit', 'subjects.internal_marking_type', 'subjects.combined_subject_name')
				->join('subjects',function($join){
					$join->on('subjects.sub_code','internal_marks_mappings.sub_code')
					->on('subjects.semester_id','internal_marks_mappings.semester_id');
				})
				->distinct()
				->orderBy('sub_code')
				->where('subjects.deleted_at',null)
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

}