<?php

namespace App\Http\Controllers\ums\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ums\AdminController;

use App\Models\SemesterFee;
use App\Models\ums\StudentSemesterFee;
use App\Models\ums\Semester;
use App\Models\ums\Subject;
use App\Models\ums\Enrollment;
use App\Models\ums\Course;
use App\Models\ums\Icard;
use App\Models\ums\Category;
use App\Exports\FeeExport;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use Auth;

class SemesterController extends AdminController
{
	 public function __construct()
    {
        parent::__construct();
    }
	
    public function index(Request $request){
        return view('ums.student.semester-form');
    
}
	
     public function add(Request $request)
    {
		$semesterfee=StudentSemesterFee::where('student_id',Auth::guard('student')->user()->enrollment_no)->latest()->first();
		//dd($semesterfee);
		if($semesterfee){
        $student=Icard::where('enrolment_number',Auth::guard('student')->user()->enrollment_no)->first();
		$course=Course::where('name',$student->program)->first();
		$category=Category::where('id',$course->category_id)->first();
		$oldsemester =StudentSemesterFee::where(['course_id'=>$course->id,'id'=>$semesterfee->semester_id])->first();
		$semester =StudentSemesterFee::where(['course_id'=>$oldsemester->course_id])->where('id','>',$oldsemester->id)->orderBy('id')->limit(1)->first();
		$subjectList =Subject::where(['course_id'=>$oldsemester->course_id])->where('semester_id',$oldsemester->semester_id)->get();
		//dd($newsemester);
		}
		if(!$semesterfee){
		 $student=Icard::where('enrolment_number',Auth::guard('student')->user()->enrollment_no)->first();
		$course=Course::where('name',$student->program)->first();
		
		$category=Category::where('id',$course->category_id)->first();
		$semester =Semester::where('course_id',$course->id)->first();
		$subjectList =Subject::where(['course_id'=>$course->id])->get();
		//dd($category);
		}
        return view('student.semester.addfee', [
            'page_title' => "Pay Semester Fees",
            'sub_title' => "Semester Fee",
			'student'=>$student,
			'course'=>$course,
			'category'=>$category,
			'semester'=>$semester,
			'subjectList'=>$subjectList,
        ]);
    }

    public function addFee(Request $request)
    {
		dd($request->all());
		$validator = Validator::make($request->all(),[
            
            'semester_fee' => 'required',
            'subjects' => 'required'
        ]);
		if ($validator->fails()) {    
			return back()->withErrors($validator);
		}
		
        $data = $request->all();
		dd($data);
        $fee = $this->create($data);
        return redirect()->route('student-dashboard')->with('message','Fee Submitted Successfully');
    }

    public function create(array $data)
    {
      return StudentSemesterFee::create([
        'student_id' => $data['student_id'],
        'course_id' => $data['course_code'],
        'program_id' => $data['program'],
        'semester_id' => $data['semester'],
        'semeser_fee' => $data['semester_fee'],
        'subjects' => $data['subjects'],
        
        
      ]);
    }
}
