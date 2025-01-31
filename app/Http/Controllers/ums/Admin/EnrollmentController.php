<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\AdminController;

use App\Admin;
use App\Models\Student;
use App\Models\Application;
use App\Models\Course;
use App\Models\Category;
use App\Models\PermanentAddress;
use App\Models\UploadDocuments;
use App\Models\Enrollment;
use App\Models\EnrollmentSubject;
use App\Models\CourseSubject;
use App\Models\Semester;
use App\Exports\EnrollmentExport;
use Maatwebsite\Excel\Facades\Excel;

class EnrollmentController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {   

        $enrollments = Enrollment::with(['categories', 'course'])
        ->leftJoin('students', 'students.enrollment_no', '=', 'enrollments.enrollment_no')->orderBy('enrollments.id', 'DESC');

        if($request->search) {
            $keyword = $request->search;
            $enrollments->where(function($q) use ($keyword){

                $q->where('enrollments.enrollment_no', 'LIKE', '%'.$keyword.'%')
				->orwhere('enrollments.roll_number', 'LIKE', '%'.$keyword.'%');

            });
        }
        if(!empty($request->enrollment_no)){
            
            $enrollments->where('enrollments.enrollment_no',$request->enrollment_no);
        }
        if (!empty($request->email)) {
            $enrollments->where('students.email','LIKE', '%'.$request->email.'%');
        }
        if (!empty($request->first_Name)) {
            $enrollments->where('students.first_Name','LIKE', '%'.$request->first_Name.'%');
        }
        if (!empty($request->mobile)) {
            
            $enrollments->where('students.mobile','LIKE', '%'.$request->mobile.'%');
        }
		 if (!empty($request->course)) {
        	$enrollments->where('course_id',$request->course);
        }
        if (!empty($request->program)) {
            $enrollments->where('enrollments.category_id',$request->program);
        }
        $enrollments = $enrollments->paginate(10);

        $courses=Course::all();
		$programs = Category::all();
        return view('admin.enrollment.index', [
            'enrollments' => $enrollments,
			'courses'  => $courses,
            'programs'  => $programs
        ]);
    }
    public function view($id)
    {
        $applications = Application::with([
                'user', 
                'categories', 
                'course', 
                'addresses', 
                'educations', 
                'payments',
            ])
            ->where('id',$id)
            ->first();
        $enrollments = Enrollment::with(['categories', 'course'])->where('id','=',$id)->first();
        $enrollmentsubjects = EnrollmentSubject::with(['subject'])->where('enrollment_id','=',$id)->get();
        $subjectList = CourseSubject::with(['subject', 'course'])->where('course_id','=',$enrollments->course_id)->get();
        return view('admin.enrollment.view', [
            'page_title' => "Enrollment",
            'sub_title' => "records",
            'enrollments' => $enrollments,
            'enrollmentsubjects'=> $enrollmentsubjects,
            'subjectList'=> $subjectList,
            'applications' => $applications
        ]);

    }
    public function softDelete(Request $request,$slug) {
        
        EnrollmentSubject::where('id', $slug)->delete();
        return redirect()->intended('/admin/enrollment/view/'.$slug);
        
    }
    public function addEnrollmentSubject(Request $request,$id)
    {
        $request->validate([
            'subject_id' => 'required',
        ]);
        $data = $request->all();
        if(!empty($data['subject_id'])){   
            foreach ($data['subject_id'] as $key => $value) {
                $enrollmentSubject = new EnrollmentSubject();
                $enrollmentSubject->enrollment_id = $id;
                $enrollmentSubject->subject_id  = $value;
                $enrollmentSubject->save();
            }
        }
        return redirect()->intended('/admin/enrollment/view/'.$id);
    }
    public function enrollmentExport(Request $request)
    {
        return Excel::download(new EnrollmentExport($request), 'Enrollment_Student.xlsx');
    } 
}