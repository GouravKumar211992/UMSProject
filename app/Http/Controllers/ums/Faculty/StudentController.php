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
use App\Exports\StudentExport;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {   
        $students = Student::orderBy('id', 'DESC');
        if($request->search) {
            $keyword = $request->search;
            $students->where(function($q) use ($keyword){

                $q->where('first_Name', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('last_Name', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('email', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('mobile', 'LIKE', '%'.$keyword.'%');
            });
        }
        if(!empty($request->name)){
            
            $students->where('first_Name', 'LIKE', '%'.$request->name.'%');
            $students->orWhere('last_Name', 'LIKE', '%'.$request->name.'%');
        }
        if (!empty($request->email)) {

            $students->where('email', 'LIKE', '%'.$request->email.'%');
            
        }
        if (!empty($request->mobile)) {
            
            $students->where('mobile', 'LIKE', '%'.$request->mobile.'%');
        }
        
         $students = $students->paginate(10);
        return view('admin.student.index', [
            'students' => $students
        ]);
    }
    public function icard(Request $request, $id)
    {
        $students = Student::where('id','=',$id)->first();
        $applications = Application::with(['user', 
                'categories', 
                'course', 
                'addresses', 
                ])->where('user_id','=',$students->user_id)->first();
       
         return view('admin.student.icard', [
            'students' => $students,
            'applications'=>$applications,
        ]);
    }

    public function studentExport(Request $request)
    {
        return Excel::download(new StudentExport($request), 'Student.xlsx');
    } 

}