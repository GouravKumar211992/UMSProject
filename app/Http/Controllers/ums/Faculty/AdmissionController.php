<?php

namespace App\Http\Controllers\Faculty;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\AdminController;

use App\User;
use App\Models\Application;
use App\Models\CourseType;
use App\Models\EducationDetails;
use App\Models\StudentDetails;
use App\Models\PermanentAddress;
use App\Models\UploadDocuments;
use App\Models\PersonalInformations;
use App\Models\PaymentDetails;
use App\Models\Course;
use App\Models\Category;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\Enrollment;
use App\Models\EnrollmentSubject;
use App\Exports\AdmissionExport;
use App\Models\Campuse;
use App\Models\CourseSubject;
use Maatwebsite\Excel\Facades\Excel;

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
            $enrollment_id = Enrollment::latest()->first()->id;
        }
        $last_digit = $this->countdigits($enrollment_id);

        $enrollment_no = $roll_number = '';
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
        $subjectList = CourseSubject::with(['subject', 'course'])->where('course_id', '=', $applications->course->id)->get();
        $courseTypes = CourseType::all();
       // $subject = Subject::where('course_id', '=', $applications->course->id)->first();
		$semester = Semester::where('course_id',$applications->course->id)->first();
        $subjectList = [];
        if($semester){
            $subjectList = Subject::where('semester_id', '=', $semester->id)->get();
        }
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
        $request->validate([
            'subject_id' => 'required',
        ]);
        $application = Application::find($id);
        $enrollment = Enrollment::where('user_id', $application->user_id)
            ->where('course_id', $application->course_id)
            ->where('academic_session', '=', $application->academic_session)
            ->first();

        if ($enrollment) {
            return back()->with('error', 'This Application has already enrolled for this session and course');
        }
        $enrollment = new Enrollment();
        $enrollment->user_id = $application->user_id;
        $enrollment->application_id = $application->id;
        $enrollment->course_id = $application->course_id;
        $enrollment->category_id = $application->category_id;
        $enrollment->academic_session = $application->academic_session;

        if ($request->enrollment_no) {
            $enrollment->enrollment_no = $request->enrollment_no;
        }
        if ($request->is_lateral) {
            $enrollment->is_lateral = $request->is_lateral;
        }
        if ($request->roll_number) {
            $enrollment->roll_number = $request->roll_number;
        }
        $enrollment->save();
        $student = Student::where('user_id', $enrollment->user_id)
            ->first();


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

        $data = $request->all();
        if (!empty($data['subject_id'])) {
            foreach ($data['subject_id'] as $key => $value) {
                $enrollmentSubject = new EnrollmentSubject();
                $enrollmentSubject->enrollment_id = $enrollment->id;
                $enrollmentSubject->subject_id  = $value;
                $enrollmentSubject->save();
            }
        }

        
        $enrollment->generateICard();
        $student->generateNewPassowrd();

        return redirect()->intended('/admin/enrollment');
    }

    public function admissionExport(Request $request)
    {
        return Excel::download(new AdmissionExport($request), 'Admission.xlsx');
    }
}
