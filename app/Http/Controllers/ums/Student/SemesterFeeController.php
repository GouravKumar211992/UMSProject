<?php

namespace App\Http\Controllers\ums\Student;


use App\Http\Controllers\ums\UmsController;
use Illuminate\Http\Request;
use App\Http\Controllers\ums\AdminController;

use App\Models\ums\SemesterFee;
use App\Models\ums\StudentSemesterFee;
use App\Models\ums\Semester;
use App\Models\ums\Subject;
use App\Models\ums\Enrollment;
use App\Models\ums\Course;
use App\Models\ums\Icard;
use App\Models\ums\Category;
use App\Exports\ums\FeeExport;
use App\Models\ums\Application;
use App\Models\ums\CourseFee;
use App\Models\ums\ExamFee;
use App\Models\ums\Result;
use App\Models\ums\StudentSubject;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Validator;



class SemesterFeeController extends AdminController
{
     public function __construct()
    {
        parent::__construct();
    }
	
	
     public function addfee(Request $request)
    {
		
		$semesterfee=StudentSemesterFee::where('enrollment_no',Auth::guard('student')
			->user()
			->enrollment_no)
			->latest()
			->first();
		if(!$semesterfee){
			return redirect()->route('student-dashboard')->with('message','your semester is not created please contact Administration');
		}
		$enrollment=Enrollment::where('enrollment_no',$semesterfee->enrollment_no)
			->first();
		$application=Application::where('id',$enrollment->application_id)
			->first();
		//dd($semesterfee);
		if($semesterfee){
		
        $student=Icard::where('enrolment_number',Auth::guard('student')->user()->enrollment_no)
			->first();
		$exam=ExamFee::where('enrollment_no',$student->enrolment_number)
			->latest()
			->first();
		$course=Course::where('name',$student->program)
			->first();
		$category=Category::where('id',$course->category_id)
			->first();
		$oldsemester =StudentSemesterFee::where(['course_id'=>$course->id,'semester_id'=>$semesterfee->semester_id])
			->latest()
			->first();
		
		if(!$oldsemester){
			return redirect()->route('student-dashboard')->with('error','Semester Not Created');
		}
		$semester =Semester::where(['course_id'=>$oldsemester->course_id])
			->where('id','>',$oldsemester->semester_id)
			->orderBy('id')
			->limit(1)
			->first();
		//dd($oldsemester->semester_id);
		$subjectList =Subject::where(['course_id'=>$oldsemester->course_id])
			->where('semester_id',$semester->id)
			->get();
		//dd($newsemester);
		if(($semester->semester_number%2)==0){
		$coursefees_data =CourseFee::where('course_id',$course->id)
			->where('fees_type','Per Semester');
		}
		elseif(($semester->semester_number%2)!=0){
			$coursefees_data =CourseFee::where('course_id',$course->id)
			->where('fees_type','Per Semester')
			->orWhere('fees_type','Per Year');
		}
		$coursefees = $coursefees_data->get();
		$total_non_disabled_fees = $coursefees_data->sum('non_disabled_fees');
		$total_disabled_fees = $coursefees_data->sum('disabled_fees');
		
		}
		
		//dd($subjectList);
		$disability_category=$application->disability_category;
		//dd($exam->semester,$semesterfee->semester_id);
		if(!$exam){
			return redirect()->route('student-dashboard')->with('message','Your  Semester Form already filled at university end . Please Fill Your Exam Form');
		}
		$result=Result::where(['enrollment_no'=>$exam->enrollment_no,'exam_session'=>$exam->academic_session,'semester'=>$exam->semester])->first();
		dd($exam);
		//dd($exam->semester,$semesterfee->semester_id);
		if($exam->semester==$semesterfee->semester_id){ 
			if(!$result){
				return redirect()->route('student-dashboard')->with('message','Please Wait For Result To Be Declared To Fill Your Semester Form');
			}
			
		return view('ums..studentfees.add_semesterfee', [
            'page_title' => "Fill Semester Form",
            'sub_title' => "Semester Form",
			'student'=>$student,
			'course'=>$course,
			'category'=>$category,
			'semester'=>$semester,
			'subjectList'=>$subjectList,
			'disability_category'=>$disability_category,
			'total_non_disabled_fees'=>$total_non_disabled_fees,
			'total_disabled_fees'=>$total_disabled_fees,
			'coursefees'=>$coursefees,
			'enrollment'=>$enrollment,
        ]);
		}
		return redirect()->route('student-dashboard')->with('message','already filled your semester form please wait for next semester');
		
    }

    public function addsemesterFee(Request $request)
    {
		$validator = Validator::make($request->all(),[
            
            'subject'=>'required|array',
			'subject.*' => 'required',
			'receipt_date' => 'required',
			'receipt_number' => 'required',
			
        ]
		);
		if ($validator->fails()) {    
			return back()->withErrors($validator);
		}
			
		$student_subjects = [];
		//dd($request->subjectname);
		
		//dd($student_subjects);
		
        $data = $request->all();
		
		foreach($data['subject'] as $key => $value){
			$enrollmentSubject[]=$value;
		}

		//dd(implode(" ",$enrollmentSubject));
		$data['enrollmentSubject']=implode(" ",$enrollmentSubject);
		//dd($data);
		$studentsemester=new StudentSemesterFee;
		$studentsemester->enrollment_no=$data['student_id'];
		$studentsemester->course_id=$data['course_code'];
		$studentsemester->semester_fee=$data['semester_fee'];
		$studentsemester->program_id=$data['program'];
		$studentsemester->semester_id=$data['semester'];
		$studentsemester->subjects=$data['enrollmentSubject'];
		$studentsemester->receipt_number=$data['receipt_number'];
		$studentsemester->receipt_date=$data['receipt_date'];
		$studentsemester->save();
		$studentsemester->id;
		
        $studentsemester_id = $studentsemester->id;
		
		foreach($request->subject as $index=>$subject_row){
			$student_subjects[$index]['student_semester_fee_id'] = $studentsemester_id;
			$student_subjects[$index]['sub_code'] = $subject_row;
			$student_subjects[$index]['enrollment_number'] = $request->student_id;
			$student_subjects[$index]['roll_number'] = $request->roll_no;
			$student_subjects[$index]['session'] = $request->academic_session;
			$student_subjects[$index]['program_id'] = $request->program;
			$student_subjects[$index]['course_id'] = $request->course_code;
			$student_subjects[$index]['semester_id'] = $request->semester;
			$student_subjects[$index]['sub_name'] = Subject::where('sub_code',$subject_row)->first()->name;
			
		}
		//dd($student_subjects);
		StudentSubject::insert($student_subjects);
        return redirect()->route('student-dashboard')->with('message','Form Submitted Successfully');
    }

    public function create(array $data)
    {
      return StudentSemesterFee::create([
        'enrollment_no' => $data['student_id'],
        'course_id' => $data['course_code'],
        'semester_fee' => $data['semester_fee'],
        'program_id' => $data['program'],
        'semester_id' => $data['semester'],
        'subjects' => $data['enrollmentSubject'],
        'receipt_number' => $data['receipt_number'],
        'receipt_date' => $data['receipt_date'],
        
        
      ]);
    }
}
