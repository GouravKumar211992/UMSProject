<?php

namespace App\Http\Controllers\ums\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\ums\AdminController;

use App\User;
use App\Models\ums\Application;
use App\Models\ums\CourseType;
use App\Models\ums\EducationDetails;
use App\Models\ums\StudentDetails;
use App\Models\ums\PermanentAddress;
use App\Models\ums\UploadDocuments;
use App\Models\ums\PersonalInformations;
use App\Models\ums\PaymentDetails;
use App\Models\ums\Course;
use App\Models\ums\CourseFee;
use App\Models\ums\Category;
use App\Models\ums\Student;
use App\Models\ums\Subject;
use App\Models\ums\Semester;
use App\Models\ums\Enrollment;
use App\Models\ums\AcademicSession;
use App\Models\ums\EnrollmentSubject;
use App\Exports\AdmissionExport;
use App\Models\ums\Campuse;
use App\Models\ums\Icard;
use App\Models\ums\StudentSemesterFee;
use App\Models\ums\StudentSubject;
use App\Models\ums\CourseSubject;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use Auth;

class AdmissionController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {

        $applications = Application::with(['user', 'categories', 'course'])
            ->orderBy('id', 'DESC');

        if ($request->search) {
            $keyword = $request->search;
            $applications->where(function ($q) use ($keyword) {

                $q->where('application_no', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('first_Name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('email', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('mobile', 'LIKE', '%' . $keyword . '%');
            });
        }
        if (!empty($request->application_for)) {

            $applications->where('application_for', $request->application_for);
        }
        if (!empty($request->course_id)) {
            $applications->where('course_id', $request->course_id);
        }
        if (!empty($request->category_id)) {

            $applications->where('category_id', $request->category_id);
        }
        if (!empty($request->status)) {

            $applications->where('status', $request->status);
        }

        $applications = $applications->paginate(10);

        $categories = Category::all();
        $courses = Course::all();

        return view('admin.admission.index', [
            'page_title' => "User",
            'sub_title' => "records",
            'categories' => $categories,
            'applications' => $applications,
            'courses'  => $courses
        ]);
    }


    public function addUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:admins',
            'password' => 'required',
            'mobile' => 'required|numeric',
        ]);
        $data = $request->all();

        $user = $this->create($data);
        return redirect()->route('get-user');
    }

    public function view($id)
    {
        $applications = Application::with([
            'user',
            'categories',
            'course',
            'addresses',
            'educations',
            'payments'
        ])
            ->where('id', $id)
            ->first();
		
        $campuses = Campuse::all();

        return view('admin.admission.view', [
            'page_title' => "Admission",
            'sub_title' => "records",
            'applications' => $applications,
            'campuses' => $campuses
        ]);
    }

    public function updateStatus(Request $request, $id)
    {

        $request->validate([
            'status' => 'required',
        ]);
        $application = Application::find($id);

        $application->status = $request->status;
        if (!empty($request->counselling_date)) {
            $application->counselling_date = $request->counselling_date;
        }
        if (!empty($request->counselling_vanue)) {
            $application->counselling_vanue = $request->counselling_vanue;
        }
        if (!empty($request->counselling_time)) {
            $application->counselling_time = $request->counselling_time;
        }
        $application->remarks = $request->remarks;
        $application->save();

        if ($application->status == 'Approved') {

            // return redirect()->intended('/admin/students');
        }
        return redirect()->intended('/admin/admission/view/' . $id);
    }
    public function enrollment($id)
    {

        $applications = Application::with([
            'user',
            'categories',
            'course',
            'addresses',
            'educations',
            'payments'
        ])
            ->where('id', $id)
            ->first();
        $campuse = Campuse::find($applications->course->campus_id);
        $enrollment = Enrollment::latest()->first();
        $enrollment_id = 1;
        if($enrollment){
            $enrollment_id = $enrollment_id+Enrollment::latest()->first()->id;
        }
		
        $last_digit = $this->countdigits($enrollment_id);
		
        $enrollment_no = $roll_number = '';
/*
        if(!empty($campuse->type) && $campuse->type == 'DSMNRU CAMPUS'){
            $enrollment_no = 'SU'.date("y").'000'.$last_digit;
            $last_digit_roll_num = $this->countForRollNumberSU($enrollment_id);
            $roll_number = date("y").''.strtoupper($applications->course->color_code).$last_digit_roll_num;
        }
		
        else{
            $enrollment_no = 'SA'.date("y").''.strtoupper($campuse->short_name).''.$last_digit;
            $last_digit_roll_num = $this->countForRollNumberSA($enrollment_id);
            $roll_number = date("y").''.strtoupper($campuse->short_name).''.strtoupper($applications->course->color_code).$last_digit_roll_num;
        }
*/

        $campuse_id = $campuse->id;
        $course_id = $applications->course->id;
        $enrollment_no = createEnrollment($campuse_id,$course_id);
        $roll_number = createRollNo($campuse_id,$course_id);

        //dd($roll_number);
        $subjectList = StudentSubject::select('subjects.*')
		->join('subjects','subjects.sub_code','student_subjects.sub_code')
		->where('enrollment_number', '=', $applications->application_no)->get();
        $courseTypes = CourseType::all();
       // $subject = Subject::where('course_id', '=', $applications->course->id)->first();
		$semester = Semester::where('course_id',$applications->course->id)->first();
		//dd($subjectList);
	   
		//dd($semester);

        return view('admin.admission.enrollment', [
            'page_title' => "Enrollment",
            'sub_title' => "records",
            'applications' => $applications,
            'subjectList' => $subjectList,
			'semester_data'=>$semester,
			'enrollment_no'=>$enrollment_no,
			'roll_number'=>$roll_number,
            'courseTypes' => $courseTypes,
        ]);
    }

    public function countdigits($enrollment_id)
    {
        $enrollmentId = (int)$enrollment_id;
        $count = $num = 0;

        while($enrollmentId != 0){
            $enrollmentId = (int)($enrollmentId / 10);
            $count++;
        }
        if($count == 1){
            $num = '0000'.$enrollment_id;
        }
        else if($count == 2){
            $num = '000'.$enrollment_id;
        }
        else if($count == 3){
            $num = '00'.$enrollment_id;
        }
        else if($count == 4){
            $num = '0'.$enrollment_id;
        }
        else if($count == 5){
            $num = $enrollment_id;
        }
        return $num;         
    }
    public function countForRollNumberSU($enrollment_id)
    {
        $enrollmentId = (int)$enrollment_id;
        $count = $num = 0;

        while($enrollmentId != 0){
            $enrollmentId = (int)($enrollmentId / 10);
            $count++;
        }
        if($count == 1){
            $num = '000'.$enrollment_id;
        }
        else if($count == 2){
            $num = '00'.$enrollment_id;
        }
        else if($count == 3){
            $num = '0'.$enrollment_id;
        }
        else if($count == 4){
            $num = $enrollment_id;
        }
        return $num;         
    }
    public function countForRollNumberSA($enrollment_id)
    {
        $enrollmentId = (int)$enrollment_id;
        $count = $num = 0;

        while($enrollmentId != 0){
            $enrollmentId = (int)($enrollmentId / 10);
            $count++;
        }
        if($count == 1){
            $num = '00'.$enrollment_id;
        }
        else if($count == 2){
            $num = '0'.$enrollment_id;
        }
        else if($count == 3){
            $num = $enrollment_id;
        }
        return $num;         
    }
    public function generateEnrollments(Request $request, $id)
    {
//        dd('ss');
		//dd($request->all());
        if(!$request->backlog){
            $valid= $request->validate([
                // 'subject_id' => 'required',
                'zero_fee' => 'required',
                'annual_income' => 'required_if:zero_fee,yes',
                'income' => 'required_if:zero_fee,yes',
                'income_certificate' => 'required_if:zero_fee,yes',
                'income_certificate_number' => 'required_if:zero_fee,yes',
                //'disability_certificate' => 'required',
                //'caste_certificate' => 'required',
                'aadhar_card' => 'required',
                //'any_other' => 'required',
                
             ]);
             if(!$valid)
             {
                  return back()->withInput();
             }
        }
		//dd($request->all());
        $application = Application::find($id);
        if(!$request->backlog){
            $application->update(['zero_fee' => $request->zero_fee,'annual_income'=>$request->annual_income,'is_father_income'=>$request->income,'income_certificate_number'=>$request->income_certificate_number]);
        }
		//dd($request->aadhar_card);
		
        if(!$request->backlog){
            if($request->caste_certificate){
                $application->addMediaFromRequest('caste_certificate')->toMediaCollection('caste_certificate');
            }
            if($request->aadhar_card){
                $application->addMediaFromRequest('aadhar_card')->toMediaCollection('aadharcards');
            }
            
            if($request->disability_certificate){
                $application->addMediaFromRequest('disability_certificate')->toMediaCollection('disability_certificate');
            }
            
            if($request->income_certificate){
                $application->addMediaFromRequest('income_certificate')->toMediaCollection('income_certificate');
            }
            
            if($request->any_other){
                $application->addMediaFromRequest('any_other')->toMediaCollection('any_other');
            }
        }
        

        $enrollment = Enrollment::where('user_id', $application->user_id)
            ->where('course_id', $application->course_id)
            ->where('course_preferences', $application->course_preferences)
            ->where('academic_session', '=', $application->academic_session)
            ->first();

        if(!$request->backlog){
            if ($enrollment) {
                return back()->with('error', 'This Application has already enrolled for this session and course');
            }
        }
        
        
 
    
        $coursePreference = $request->course_preference;

        $enrollment = new Enrollment();
        $enrollment->user_id = $application->user_id;
        $enrollment->application_id = $application->id;
        $enrollment->course_id = $application->course_id;
       
        $enrollment->category_id = $application->category_id;
        $enrollment->academic_session = $application->academic_session;
        $enrollment->course_preferences = $coursePreference;
        
      
        if ($request->enrollment_no) {
            $enrollment->enrollment_no = $request->enrollment_no;
        }
        if ($request->is_lateral) {
            $enrollment->is_lateral = $request->is_lateral;
        }
        if ($request->roll_number) {
            $enrollment->roll_number = $request->roll_number;
        }
		
     
		// $studentSemesterFee=StudentSemesterFee::where('enrollment_no',$application->application_no)->first();
		// $studentsubject=StudentSubject::where('enrollment_number',$application->application_no)->get();
		//dd($studentsubject);
		
		//dd($studentsubject);
		$enrollment->save();	
        $student = Student::where('user_id', $enrollment->user_id)
            ->first();
        if($request->backlog){
            $student = null;
        }


        if (!$student) {
            $student = new Student();
            $student->fill($application->toArray());
            $student->status = 'active';
        }

        $student->enrollment_no = $enrollment->enrollment_no;
        $student->roll_number = $enrollment->roll_number;
        if($request->course_type){
            $student->course_type = $request->course_type;
        }
        if($request->mode_of_admission){
            $student->mode_of_admission = $request->mode_of_admission;
        }
        $student->save();

        $enrollment->student_id = $student->id;
        $enrollment->save();
		
        $application->status = 'Enrolled';
        $application->save();
			
        // $data = $request->all();
        // if (!empty($data['subject_id'])) {
        //     foreach ($data['subject_id'] as $key => $value) {
        //         $enrollmentSubject = new EnrollmentSubject();
        //         $enrollmentSubject->enrollment_id = $enrollment->id;
        //         $enrollmentSubject->subject_id  = $value;
        //         $enrollmentSubject->save();
        //     }
        // }
		// if($studentSemesterFee){
		// 	$studentSemesterFee->update(['enrollment_no' => $request->enrollment_no]);
		// }if(count($studentsubject)>0){
		// 	foreach($studentsubject as $key => $value )
		// 	$studentsubject[$key]->update(['enrollment_number' => $request->enrollment_no,'roll_number'=>$request->roll_number,'session'=>$application->academic_session]);
		// }
		
        // $enrollment->generateICard();
        // $student->generateNewPassowrd();
		
        if($request->backlog){
            return $student->id;
        }
		
        return redirect('admin/admission');
    }

    public function admissionExport(Request $request)
    {
        return Excel::download(new AdmissionExport($request), 'Admission.xlsx');
    }
	public function generatesemester(Request $request)
	{
		//dd($request->all());
		$id=$request->id;
		$application = Application::find($id);
		//dd($application);
		$enrollment = Enrollment::where('user_id', $application->user_id)
            ->where('course_id', $application->course_id)
            ->where('academic_session', '=', $application->academic_session)
            ->first();
			
		$semesterfee=StudentSemesterFee::where('enrollment_no',$enrollment->enrollment_no)->latest()->first();
		//dd($enrollment);
		if($semesterfee){
			return back()->with('message','Semester Created Already ');
		}
		$student=Icard::where('enrolment_number',$enrollment->enrollment_no)->first();
		$course=Course::where('name',$student->program)->first();
		$category=Category::where('id',$course->category_id)->first();
		$semester =Semester::where('course_id',$course->id)->first();
		$coursefees =CourseFee::where('course_id',$course->id)->get();
		$total_non_disabled_fees =CourseFee::where('course_id',$course->id)->sum('non_disabled_fees');
		$total_disabled_fees =CourseFee::where('course_id',$course->id)->sum('disabled_fees');
		//dd($coursefees);
		$subjectList =Subject::where(['course_id'=>$course->id,'semester_id'=>$semester->id])->get();
		//dd($category);
		$disability_category=$application->disability_category;
		//dd($disability_category);
        return view('student.semester.addfee', [
            'page_title' => "Pay Semester Fees",
            'sub_title' => "Semester Fee",
			'student'=>$student,
			'course'=>$course,
			'category'=>$category,
			'semester'=>$semester,
			'subjectList'=>$subjectList,
			'coursefees'=>$coursefees,
			'disability_category'=>$disability_category,
			'total_non_disabled_fees'=>$total_non_disabled_fees,
			'total_disabled_fees'=>$total_disabled_fees,
			'enrollment'=>$enrollment,
			'id'=>$id,
			
        ]);
	}
	public function addsemesterFee(Request $request)
    {
		//dd($request->all());
        if(!$request->backlog){
            $validator = Validator::make($request->all(),[
                'subject'=>'required|array',
                'subject.*' => 'required',
                'receipt_date' => 'required',
                'receipt_number' => 'required',
                
            ]);
            if ($validator->fails()) {    
                return back()->withErrors($validator);
            }
        }
			
		$student_subjects = [];
		//dd($request->subjectname);
		
		//dd($student_subjects);
		
        $data = $request->all();
        if($request->backlog){
            $data['enrollmentSubject'] = $request->subject;
        }else{
            foreach($data['subject'] as $key => $value){
                $enrollmentSubject[]=$value;
            }
            $data['enrollmentSubject']=implode(" ",$enrollmentSubject);
        }
		//dd($data);
		$studentsemester=new StudentSemesterFee;
		$studentsemester->enrollment_no= $request->student_id;
		$studentsemester->course_id= $request->course_code;
		$studentsemester->semester_fee= $request->semester_fee;
		$studentsemester->program_id= $request->program;
		$studentsemester->semester_id= $request->semester;
		$studentsemester->subjects=$data['enrollmentSubject'];
		$studentsemester->receipt_number= $request->receipt_number;
		$studentsemester->receipt_date= $request->receipt_date;
		$studentsemester->status=1;
		$studentsemester->save();
		$studentsemester->id;
		
        $studentsemester_id = $studentsemester->id;
		
        if($request->backlog){
            $subjects = explode(" ",$request->subject);
        }else{
            $subjects = $request->subject;
        }
		foreach($subjects as $index=>$subject_row){
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

        if($request->backlog){
            return true;
        }

        return redirect()->route('get-admission')->with('message','First Semester Generated  Successfully');
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
        'status' =>1,
        
        
      ]);
    }

    public function admissionBulkSelection(Request $request){
        $selected_campus = Campuse::withoutTrashed()->find($request->campus_id);
        $selected_course = Course::withoutTrashed()->find($request->course_id);
        $selected_semester = Semester::withoutTrashed()->find($request->semester_id);
        $campuses = Campuse::withoutTrashed()->orderBy('name')->get();
        $courses = Course::withoutTrashed()->where('campus_id',$request->campus_id)->orderBy('name')->get();
        $semesters = Semester::withoutTrashed()->where('course_id',$request->course_id)->orderBy('id','asc')->get();
        $applications = Application::where('user_id','!=',0)
          ->where('payment_status',1)
          ->whereNotNull('roll_number')
          ->orderBy('roll_number','ASC')
          ->get();
      return view('admin.admission.admission-bulk-selection', compact(
          'campuses',
          'courses',
          'applications',
          'selected_campus',
          'selected_course',
          'selected_semester',
        ));
    }

    public function applicationCouncil(Request $request)
    {
      // dd($request->all());
        $campuses = Campuse::get();
        $courses = Course::get();
        $programs = Category::all();
        $academic_sessions = AcademicSession::all();
        $cast_category = Application::where('user_id','!=',0)
          ->distinct('category')
          ->pluck('category')
          ->toArray();

        $Application_query = Application::where('user_id','!=',0);
        
          $Application = $Application_query->where('payment_status',1)
          ->where('deleted_at',null);


        if($request->search) {
            $keyword = $request->search;
            $Application->where(function($q) use ($keyword){
                $q->where('first_Name', 'LIKE', '%'.$keyword.'%')
                ->orWhere('adhar_card_number', 'LIKE', '%'.$keyword.'%')
                ->orWhere('email','LIKE','%'.$keyword.'%')
                ->orWhere('mobile','LIKE','%'.$keyword.'%');
            });
        }
          if (!empty($request->council_user)) {
            $Application->where('is_flag',$request->council_user);
          }
          if (!empty($request->campus)) {
            $Application->where('campuse_id',$request->campus);
          }
          if (!empty($request->filter_program)) {
            $Application->where('category_id',$request->filter_program);
          }
          if (!empty($request->filter_course)) {
            $Application->where('course_id',$request->filter_course);
          }
          if (!empty($request->academic_session)) {
            $Application->where('academic_session',$request->academic_session);
          }
        $per_page = 10000;
        if($request->remove_pagination != 'all'){
          $per_page = 10;
        }
        $Application = $Application->paginate($per_page);
        foreach($Application as $Application_row){
          $Application_row->equivalent_percentage = ($Application_row->latestEducationDetails)?$Application_row->latestEducationDetails->equivalent_percentage:'';
        }
        $Application_sort = $Application;
        if($request->education_order == 'ASC'){
          $Application_sort = $Application->sortBy('equivalent_percentage');
        }
        if($request->education_order == 'DESC'){
          $Application_sort = $Application->sortByDesc('equivalent_percentage');
        }
        $current_page = 1;
        if($request->page){
            $current_page = $request->page;
        }
        return view('ums.admissions.admission_counselling', [
            'page_title' => "Campuse",
            'sub_title' => "records",
            'applications' => $Application,
            'Application_sort' => $Application_sort,
            'academic_sessions' => $academic_sessions,
            'campuses' => $campuses,
            'programs' => $programs,
            'courses' => $courses,
            'cast_category' => $cast_category,
            'per_page' => $per_page,
            'current_page' => $current_page,
        ]);
    }

    public function saveCouncil(Request $request){
      if($request->counceling ==null){
        return back()->with('error','Please select at least one student, before counseling');
      }
      $data = Application::whereIn('id',$request->counceling)->update(['enrollment_status' =>1]);
       return back()->with('success','Thanks for counseling');
    }

    
    public function counciledData(Request $request)
    {
      // dd($request->all());
      $campuses = Campuse::get();
      $courses = Course::get();
      $programs = Category::all();
      $academic_sessions = AcademicSession::all();
      $cast_category = Application::where('user_id','!=',0)
        ->distinct('category')
        ->pluck('category')
        ->toArray();

      $Application_query = Application::where('user_id','!=',0);
      
        $Application = $Application_query->where('enrollment_status',1)
        ->where('payment_status',1)
        ->where('deleted_at',null);


      if($request->search) {
          $keyword = $request->search;
          $Application->where(function($q) use ($keyword){
              $q->where('first_Name', 'LIKE', '%'.$keyword.'%')
              ->orWhere('adhar_card_number', 'LIKE', '%'.$keyword.'%')
              ->orWhere('email','LIKE','%'.$keyword.'%')
              ->orWhere('mobile','LIKE','%'.$keyword.'%');
          });
      }
        
        if (!empty($request->course)) {
          $Application->where('counselled_course_id',$request->course);
        }
        if (!empty($request->campus)) {
          $Application->where('campuse_id',$request->campus);
        }
        if (!empty($request->filter_course)) {
          $Application->where('counselled_course_id',$request->filter_course);
        }
        if (!empty($request->academic_session)) {
          $Application->where('academic_session',$request->academic_session);
        }
        
      $per_page = 10000;
      if($request->remove_pagination != 'all'){
        $per_page = 10;
      }
      $Application = $Application->paginate($per_page);
      foreach($Application as $Application_row){
        $Application_row->equivalent_percentage = ($Application_row->latestEducationDetails)?$Application_row->latestEducationDetails->equivalent_percentage:'';
      }
      $Application_sort = $Application;
      if($request->education_order == 'ASC'){
        $Application_sort = $Application->sortBy('equivalent_percentage');
      }
      if($request->education_order == 'DESC'){
        $Application_sort = $Application->sortByDesc('equivalent_percentage');
      }
      $current_page = 1;
      if($request->page){
          $current_page = $request->page;
      }

  


        return view('ums.admissions.council_data', [
            'page_title' => "Campuse",
            'sub_title' => "records",
            'applications' => $Application,
            'Application_sort' => $Application_sort,
            'campuses' => $campuses,
            'programs' => $programs,
            'courses' => $courses,
            'academic_sessions' => $academic_sessions,
            'cast_category' => $cast_category,
            'per_page' => $per_page,
            'current_page' => $current_page,
            
            
        ]);
    }

    public function enrolledStudent(Request $request)
    {
      // dd($request->all());
        $campuses = Campuse::get();
        $courses = Course::get();
        $programs = Category::all();
        $academic_sessions = AcademicSession::all();
        $cast_category = Application::where('user_id','!=',0)
          ->distinct('category')
          ->pluck('category')
          ->toArray();

        $Application_query = Application::where('user_id','!=',0);
        
          $Application = $Application_query->where('enrollment_status',2)
          ->where('payment_status',1)
          ->where('deleted_at',null);


        if($request->search) {
            $keyword = $request->search;
            $Application->where(function($q) use ($keyword){
                $q->where('first_Name', 'LIKE', '%'.$keyword.'%')
                ->orWhere('adhar_card_number', 'LIKE', '%'.$keyword.'%')
                ->orWhere('email','LIKE','%'.$keyword.'%')
                ->orWhere('mobile','LIKE','%'.$keyword.'%');
            });
        }
          
          if (!empty($request->course)) {
            $Application->where('counselled_course_id',$request->course);
          }
          if (!empty($request->campus)) {
            $Application->where('campuse_id',$request->campus);
          }
          if (!empty($request->filter_course)) {
            $Application->where('counselled_course_id',$request->filter_course);
          }
          if (!empty($request->academic_session)) {
            $Application->where('academic_session',$request->academic_session);
          }
          $Application->orderBy('first_Name','ASC');
          
        $per_page = 10000;
        if($request->remove_pagination != 'all'){
          $per_page = 10;
        }
        $Application = $Application->paginate($per_page);
        $current_page = 1;
        if($request->page){
            $current_page = $request->page;
        }
        return view('ums.admissions.enrolled_student', [
            'page_title' => "Campuse",
            'sub_title' => "records",
            'applications' => $Application,
            'campuses' => $campuses,
            'programs' => $programs,
            'courses' => $courses,
            'academic_sessions' => $academic_sessions,
            'cast_category' => $cast_category,
            'per_page' => $per_page,
            'current_page' => $current_page,
        ]);
    }

}
