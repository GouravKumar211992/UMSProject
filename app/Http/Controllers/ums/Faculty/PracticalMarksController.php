<?php

namespace App\Http\Controllers\UMS\Faculty;

use View;
use Auth;
use App\User;

use App\Http\Controllers\Controller;
// use App\Http\Controllers\ums\AdminController;
use Illuminate\Http\Request;
use App\Models\ums\BackPaper;
use App\Models\ums\Stream;
use App\Models\ums\Course;
use App\Models\ums\Category;
use App\Models\ums\PermanentAddress;
use App\Models\ums\UploadDocuments;
use App\Models\ums\Icard;
use App\Models\ums\Result;
use App\Models\ums\Subject;
use App\Models\ums\Campuse;
use App\Models\ums\ExamForm;
use App\Models\ums\StudentSubject;
use App\Models\ums\StudentsemesterFee;
use App\Models\ums\InternalMarksMapping;
use App\Models\ums\AcademicSession;
use App\Models\ums\PracticalMark;
use App\Models\ums\MbbsExamForm;
use App\Models\ums\AwardSheetFile;
use App\Models\ums\Faculty;
use Validator;
use DB;

use Carbon\Carbon;

class PracticalMarksController extends Controller
{
     public function index()
    {
        return view('ums.master.faculty.practical_marks');
    }
    public function practical(Request $request)
    {
		$data['streams'] = Stream::whereIn('id',[50,3,4,5])->orderBy('name','ASC')->get();
		$data['examTypes'] = StudentSubject::distinct('type')->pluck('type')->toArray();    
        $data['sub_code']= $data['sub_name']= $data['date_of_practical_exam']=$data['assign_maximum']=$data['mapped_Semesters']= $data['mapped_faculty']= $mapped_faculty= $data['mapped_Subjects'] =$data['sub_code_name'] = null;
        // $user=Auth::guard('faculty')->user();
        $faculty_id = request()->get('faculty_id', 46); // Default to 46 if 'faculty_id' is not provided in the request
        $user = Faculty::find($faculty_id);
        $mapped_Subjects_query = Subject::select('subjects.name', 'subjects.sub_code', 'subjects.back_fees', 'subjects.scrutiny_fee', 'subjects.challenge_fee', 'subjects.status', 'subjects.subject_type', 'subjects.type', 'subjects.internal_maximum_mark', 'subjects.maximum_mark', 'subjects.minimum_mark', 'subjects.credit', 'subjects.internal_marking_type', 'subjects.combined_subject_name')
				->join('internal_marks_mappings',function($join){
					$join->on('subjects.sub_code','internal_marks_mappings.sub_code')
					//->on('subjects.course_id','internal_marks_mappings.course_id')
					->on('subjects.semester_id','internal_marks_mappings.semester_id');
				})
//				->join('subjects','subjects.sub_code','internal_marks_mappings.sub_code')
				->distinct()
				->orderBy('sub_code')
				->where('faculty_id',$user->id)
                ->where('subjects.subject_type','Practical');
				if($request->semester){
					$mapped_Subjects_query->where('subjects.semester_id',$request->semester);
				}
			$data['mapped_Subjects'] = $mapped_Subjects_query->get();
			//dd($data['mapped_Subjects']);	
			$data['mapped_Courses']=InternalMarksMapping::select('courses.name','courses.id','internal_marks_mappings.course_id')
		->join('courses','courses.id','internal_marks_mappings.course_id')
		->distinct()
		->where('faculty_id',$user->id)
		->get();
        $data['sessions']=AcademicSession::all();
         if($request->course!=null)
		$data['mapped_Semesters']=InternalMarksMapping::select('semesters.name','semesters.id','internal_marks_mappings.semester_id')
		->join('semesters','semesters.id','internal_marks_mappings.semester_id')
		->distinct()
		->where('faculty_id',$user->id)
		->where('internal_marks_mappings.course_id',$request->course)
		->orderBy('semesters.semester_number')
		->get();
        $msg=null;
        $subject=null;
        $students= [];
       if($request->sub_code!=null)
        {
			$duplicate_roll_no = PracticalMark::where('session',$request->session)
            ->where('sub_code', $request->sub_code)
            ->where('course_id', $request->course)
            ->where('semester_id', $request->semester)
            ->where('type', $request->type)
			->where('roll_number','LIKE',$request->batch.'%')
            ->pluck('roll_number')
            ->toArray();

            if($request->type!='regular' && $request->type!='compartment'){
                $backPaperRollNos = BackPaper::join('exam_fees','exam_fees.id','special_back_table_details.exam_fee_id')
                ->where('exam_fees.academic_session',$request->session)
                ->where('sub_code', $request->sub_code)
                ->where('special_back_table_details.course_id', $request->course)
                ->where('special_back_table_details.semester_id', $request->semester)
                ->where('exam_fees.form_type',$request->type)
                ->where('roll_number','LIKE',$request->batch.'%')
                ->where('viva',1)
                ->orWhere('p_internal',1)
                ->distinct('roll_number')
                ->pluck('roll_number')
                ->toArray();
            }
            $students_query = StudentSubject::has('student')
            // ->join('enrollments','enrollments.roll_number','student_subjects.roll_number')
            // ->join('applications','applications.id','enrollments.application_id')
            ->select('student_subjects.enrollment_number', 'student_subjects.roll_number', 'student_subjects.session', 'student_subjects.program_id', 'student_subjects.course_id', 'student_subjects.semester_id', 'student_subjects.sub_code', 'student_subjects.sub_name')
            ->where('student_subjects.sub_code',$request->sub_code)
			->where('roll_number','LIKE',$request->batch.'%')
            ->whereNotIn('student_subjects.roll_number',$duplicate_roll_no);
            if($request->type!='regular' && $request->type!='compartment'){
                $students_query->whereIn('student_subjects.roll_number',$backPaperRollNos);
            }
            // if($request->course == 37){
            //     $students_query->,$request->strean_id);
            // }
            $students = $students_query->where('student_subjects.session',$request->session)
            ->where('student_subjects.course_id',$request->course)
            ->where('student_subjects.semester_id',$request->semester)
            ->where('student_subjects.type',$request->type)
            // ->where('applications.campuse_id',$mapping_details->campuse_id)
            ->distinct('student_subjects.enrollment_number', 'student_subjects.roll_number', 'student_subjects.session', 'student_subjects.program_id', 'student_subjects.course_id', 'student_subjects.semester_id', 'student_subjects.sub_code', 'student_subjects.sub_name')
            ->orderBy('student_subjects.roll_number','asc')
            ->paginate(40);

            // if(count($students)==0)
            // {
            //     $students = MbbsExamForm::with('student')->has('student')
            //                     ->where('sub_code',$request->sub_code)
            //                     ->whereNotIn('rollno',$duplicate_roll_no)
            //                     ->orderBy('rollno','ASC')
            //                     ->paginate(40);
			// 	$mapped_faculty=InternalMarksMapping::where('faculty_id',$user->id)
			// 	->where('sub_code',$request->sub_code)
			// 	->first();
            //     $data['msg']=$msg;
            //     $data['mapped_faculty'] = $mapped_faculty;
            //     $data['students'] = $students;
            //     $sub_name=Subject::where('sub_code', $request->sub_code)->first();
            //     $data['sub_name']=$sub_name;
            //     $data['sub_code']=$request->sub_code;
            //     $data['date_of_practical_exam']=$request->date_of_practical_exam;
                
            //     $data['practical_maximum']=$request->practical_maximum;
            //     //dd($data);
            //     return view('faculty.practical.add',$data);
            //     // return view('faculty.practical.add-mbbs',$data);
            // }

			$mapped_faculty=InternalMarksMapping::with('Course')
            ->where('faculty_id',$user->id)
            ->where('sub_code',$request->sub_code)
            ->where('course_id',$request->course)
            ->first();
        }
        //dd($subjects);
        $data['msg']=$msg;
        $data['mapped_faculty'] = $mapped_faculty;
        $data['students'] = $students;
        $sub_name=Subject::where('sub_code', $request->sub_code)->first();
        $data['sub_name']=$sub_name;
        $data['sub_code']=$request->sub_code;
        $data['date_of_practical_exam']=$request->date_of_practical_exam;
        
        $data['practical_maximum']=$request->practical_maximum;
        dd($data['mapped_Subjects']);
        return view('ums.master.faculty.practical_marks',$data);
    }
    
    public function post_practical(Request $request){
		
        $AwardSheetFile = new AwardSheetFile;
		$AwardSheetFile->type = 'Internal';
		$AwardSheetFile->save();
		$file_id = $AwardSheetFile->id;
       
        foreach($request->enrollment_number as $index=>$enrollment_no){
            $practical_mark = PracticalMark::where(['sub_code'=> $request->subject_code[$index],'roll_number'=> $request->roll_number[$index],'session'=>$request->session[$index],'type'=>$request->type])->first();
            if(!$practical_mark){
                $student_practical[$index]['award_sheet_file_id'] = $file_id;
                $student_practical[$index]['absent_status'] = $request->absent_status[$index];
                $student_practical[$index]['campus_code'] = $request->campus_id[$index];
                $student_practical[$index]['campus_name'] = $request->campus_name[$index];
                $student_practical[$index]['program_id'] = $request->program_id[$index];
                $student_practical[$index]['program_name'] = $request->program_name[$index];
                $student_practical[$index]['course_id'] = $request->course_id[$index];
                $student_practical[$index]['course_name'] = $request->course_name[$index];
                $student_practical[$index]['semester_id'] = $request->semester_id[$index];
                $student_practical[$index]['semester_name'] = $request->semester_name[$index];
                $student_practical[$index]['session'] = $request->session[$index];
                $student_practical[$index]['faculty_id'] = $request->faculty_id[$index];
                $student_practical[$index]['sub_code'] = $request->subject_code[$index];
                $student_practical[$index]['sub_name'] = $request->subject_name[$index];
                $student_practical[$index]['date_of_practical_exam'] = $request->semester_date[$index];
                $student_practical[$index]['maximum_mark'] = $request->maximum_practical[$index];
                $student_practical[$index]['student_name'] = $request->student_name[$index];
                $student_practical[$index]['enrollment_number'] = $request->enrollment_number[$index];
                $student_practical[$index]['roll_number'] = $request->roll_number[$index];
                $student_practical[$index]['practical_marks'] = $request->practical_mark[$index];
                $student_practical[$index]['total_marks'] = $request->total_marks[$index];
                $student_practical[$index]['comment'] = $request->comment[$index];
                $student_practical[$index]['type'] = $request->type;
                $total=$request->total_marks[$index];
                if(is_numeric($total)){
                    $max_total=$request->maximum_practical[$index];
                    $percentage =($total/$max_total)*100;
                    $percentage =number_format((float)$percentage, 2, '.', '');
                    $student_practical[$index]['equivalent_practical_mark'] = $total;
                    $student_practical[$index]['total_marks_words'] = $request->total_marks_words[$index];
                }else{
                    $student_practical[$index]['equivalent_practical_mark'] = '';
                    $student_practical[$index]['total_marks_words'] = $request->total_marks_words[$index];
                }
                $student_practical[$index]['created_at'] = Carbon::now();
    
            }
			$check_dup = PracticalMark::where('course_id',$request->course_id[$index])
										->where('roll_number',$request->roll_number[$index])
										->where('semester_id',$request->semester_id[$index])
										->where('sub_code',$request->subject_code[$index])
										->where('session',$request->session[$index])
										->where('type',$request->type)
										->first();
			if(!$check_dup){
				PracticalMark::insert($student_practical[$index]);
			}
        }

        return back()->with('success','Record Saved. Click on Show External Marks for View and Print');
    }
// delete practical delete
    public function practicalMarksDelete(Request $request)
    {
		
		if(Auth::guard('admin')->check()==true){
			$user=Auth::guard('faculty')->user();
			PracticalMark::where('session',$request->session)

			->where('sub_code', $request->sub_code)
			->where('course_id', $request->course)
			->where('semester_id', $request->semester)
			->where('faculty_id',$user->id)
			->where('type',$request->type)
			->whereIn('roll_number',$request->roll_number)
			// ->where('date_of_semester_exam','LIKE',$request->month_year.'%')
			
			->update(['sub_code'=>$request->sub_code.'_deleted','deleted_at'=>now()]);
		}
		return back()->with('success','Records deleted successfully');
	}


    public function practical_update(Request $request){
        $PracticalMark = PracticalMark::find($request->id);
        if($PracticalMark){
            $PracticalMark->practical_marks = $request->practical_marks;
            $PracticalMark->total_marks = $request->total_marks;
            if(is_numeric($request->total_marks)==true){
                $PracticalMark->absent_status = 0;
            }else{
                $PracticalMark->absent_status = 1;
            }
            $PracticalMark->save();
            return back()->with('success','Saved');
        }
        return back()->with('error','Some Error Occurred');
    }

    public function practicalMarksShow(Request $request)
    {

        $data['streams'] = Stream::whereIn('id',[50,3,4,5])->orderBy('name','ASC')->get();
        $data['examTypes'] = StudentSubject::distinct('type')->pluck('type')->toArray();
        // $user = Auth::guard('faculty')->user();
        $faculty_id = request()->get('faculty_id', 46); // Default to 46 if 'faculty_id' is not provided in the request
        $user = Faculty::find($faculty_id);
        $data['sub_code_name'] = null;
        $mapped_Subjects_query = InternalMarksMapping::select('subjects.*')
        ->join('subjects','subjects.sub_code','internal_marks_mappings.sub_code')
        ->where('faculty_id',$user->id)
        ->where('internal_marks_mappings.course_id',$request->course)
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
        $mapped_faculty = InternalMarksMapping::with('Course')
        ->where('faculty_id',$user->id)
        ->where('sub_code',$request->sub_code)
        ->where('course_id',$request->course)
        ->first();
        $data['mapped_faculty'] = $mapped_faculty;
        $data['mapped_Semesters'] = InternalMarksMapping::select('semesters.name','semesters.id','internal_marks_mappings.semester_id')
        ->join('semesters','semesters.id','internal_marks_mappings.semester_id')
        ->distinct()
        ->where('faculty_id',$user->id)
        ->where('internal_marks_mappings.course_id',$request->course)
        ->get();

        $marks = [];
        if($request->course == 64){
            $marks = PracticalMark::where('session',$request->session)->where(['sub_code'=>$request->sub_code,'course_id'=>$request->course,'semester_id'=>$request->semester,'type'=>$request->type])
            ->where('faculty_id',$user->id)
			->where('roll_number','LIKE',$request->batch.'%')
            ->distinct('roll_number')
            ->orderByDESC('roll_number')
            ->get();
        }else{
            if($request->month_year){
                $marks = PracticalMark::where('session',$request->session)
                ->where('sub_code', $request->sub_code)
                ->where('course_id', $request->course)
                ->where('semester_id', $request->semester)
                ->where('type',$request->type)
                ->where('faculty_id',$user->id)
                ->where('date_of_practical_exam','LIKE',$request->month_year.'%')
                ->where('roll_number','LIKE',$request->batch.'%')
                ->distinct()
                ->orderBy('roll_number')
                ->get();
            }
        }
        $data['marks'] = $marks;
        $data['details'] = ($marks)?$marks->first():[];
        $data['sessions']=AcademicSession::all();
        $data['campuses'] = Campuse::all();
		$data['selected_course'] = Course::find($request->course);
        return view('ums.master.faculty.practical_marks',$data);
    }

    public function practical_submit(Request $request){
        $user=Auth::guard('faculty')->user();
        $marks = PracticalMark::where('session',$request->session)
        ->where('sub_code',$request->sub_code)
        ->where('faculty_id',$user->id)
        ->where('course_id',$request->course)
        ->where('final_status',0)
        ->get();
        $student_practical_mark=[];
        if( $marks->count()>0 ){
            foreach($marks as $index=>$mark_row){
                $result = Result::where(['enrollment_no'=>$mark_row->enrollment_number,'exam_session'=>$mark_row->session,'semester'=> $mark_row->semester_id,'subject_code'=>$mark_row->sub_code])->first();
                if($result){
                    $result->practical_marks = $mark_row->practical_marks;
                    $result->total_marks = ((integer)$mark_row->total_marks + (integer)$result->internal_marks + (integer)$result->oral);
                    $result->save();
                }else{
                    $result = new Result;
                    $result->enrollment_no= $mark_row->enrollment_number;
                    $result->roll_no = $mark_row->roll_number;
                    $result->exam_session = $mark_row->session;
                    $result->semester = $mark_row->semester_id;
                    $result->course_id = $mark_row->course_id;
                    $result->subject_code = $mark_row->sub_code;
                    $result->practical_marks = $mark_row->practical_marks;
                    $result->total_marks = $mark_row->total_marks;
                    $result->created_at = date('Y-m-d h:i:s');
                }
                $result->save();
                $mark_row->final_status = 1;
                $mark_row->save();
            }
            return back()->with('success','Data Submitted Succesfully');
        }
    }




    public function internalMarksExport(Request $request)
    {
        return Excel::download(new InternalMarksExport($request),'InternalMark.xlsx');
    }
   
}
