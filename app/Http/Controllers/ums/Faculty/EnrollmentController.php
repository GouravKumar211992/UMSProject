<?php

namespace App\Http\Controllers\Faculty;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\AdminController;

use App\Faculty;
use App\Models\Student;
use App\Models\Application;
use App\Models\Course;
use App\Models\Category;
use App\Models\PermanentAddress;
use App\Models\UploadDocuments;
use App\Models\Enrollment;
use App\Models\EnrollmentSubject;
use App\Models\CourseSubject;
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

        $applications = Enrollment::with(['categories', 'course'])
        ->leftJoin('students', 'students.enrollment_no', '=', 'enrollments.enrollment_no')->orderBy('enrollments.id', 'DESC');

        if($request->search) {
            $keyword = $request->search;
            $applications->where(function($q) use ($keyword){

                $q->where('enrollments.enrollment_no', 'LIKE', '%'.$keyword.'%');

            });
        }
        if(!empty($request->enrollment_no)){
            
            $applications->where('enrollments.enrollment_no',$request->enrollment_no);
        }
        if (!empty($request->email)) {
            $applications->where('students.email','LIKE', '%'.$request->email.'%');
        }
        if (!empty($request->first_Name)) {
            $applications->where('students.first_Name','LIKE', '%'.$request->first_Name.'%');
        }
        if (!empty($request->mobile)) {
            
            $applications->where('students.mobile','LIKE', '%'.$request->mobile.'%');
        }
        $applications = $applications->paginate(10);

        return view('admin.enrollment.index', [
            'enrollments' => $applications
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