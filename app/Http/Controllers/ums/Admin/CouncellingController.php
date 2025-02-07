<?php

<<<<<<< HEAD
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\AdminController;
=======
namespace App\Http\Controllers\ums\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\ums\AdminController;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7

use App\Imports\CouncellingImport;
use Maatwebsite\Excel\Facades\Excel;

<<<<<<< HEAD
use App\Models\BulkCouncelling;
use App\Models\Application;
use App\Models\Student;
use App\Models\Enrollment;

use App\Http\Traits\ApplicationTrait;
use App\Models\ExamFee;
=======
use App\Models\ums\BulkCouncelling;
use App\Models\ums\Application;
use App\Models\ums\Student;
use App\Models\ums\Enrollment;

use App\Http\Traits\ApplicationTrait;
use App\Models\ums\ExamFee;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7

class CouncellingController extends AdminController
{
    use ApplicationTrait;

    public function __construct()
    {
        parent::__construct();
    }

    public function bulkCouncelling(Request $request){
        $applications = BulkCouncelling::get();
<<<<<<< HEAD
        return view('admin.admission.bulk-councelling',compact('applications'));
=======
        return view('ums.admissions.bulk_counselling',compact('applications'));
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
    }

    public function bulkCouncellingSave(Request $request)
    {
    	$request->validate([
            'councelling_file' => 'required',
        ]);
        if($request->hasFile('councelling_file')){
			Excel::import(new CouncellingImport, $request->file('councelling_file'));
		}
        return back()->with('success','Records Saved!');
    }


    public function bulkEnrollmentSubmit(Request $request){

        $course_id = $request->course_id;
        $academic_session = $request->academic_session;
        $applications = Application::where('enrollment_status',1)
        // ->where('course_id',$course_id)
        ->where('counselled_course_id',$course_id)
        
        ->where('academic_session',$academic_session)
        ->where(function ($query){
            $query->where('dsmnru_student',null)
            ->orWhere('dsmnru_student','No')
            ->orWhere('dsmnru_student','Yes');
            
        })
        ->orderBy('first_Name','ASC')
        ->get();
    
        $index_count = 0;
        foreach($applications as $index=>$application){
            ++$index_count;
           

        
            if($application->dsmnru_student=='Yes' && $application->enrollment_number !='' ){
                $enrollment_no = strtoupper($application->enrollment_number);
            }else
            {
                $enrollment_no = createEnrollment($application->campuse_id,$application->counselled_course_id);
            }
          

         
          
            $roll_no = createRollNo($application->campuse_id,$application->counselled_course_id);
            $this->insertEnrollmentTable($application,$enrollment_no,$roll_no);
            $this->insertStudentTable($application,$enrollment_no,$roll_no);
            
            $application->enrollment_status = 2;
           
            $application->save();
            echo ' '.$index;
            // dd($application);
          
        }
        return back()->with('success','Enrollemnt Done for '.$index_count.' Students');
    }


    public function cancelEnrolledStudents(Request $request){
    	$request->validate([
            'email' => 'required',
            'roll_number' => 'required',
            'academic_session' => 'required',
            'course_id' => 'required',
        ]);
        $exams = ExamFee::withTrashed()->whereIn('roll_no',$request->roll_number)->get();
        if($exams->count()>0){
            dd('Some Roll Numbers are available in Exam Table',$exams);
        }
        Student::whereIn('roll_number',$request->roll_number)->forceDelete();
        Enrollment::whereIn('roll_number',$request->roll_number)->forceDelete();
        foreach($request->email as $email_single){
            Application::where('email',$email_single)
            ->where('academic_session',$request->academic_session)
            ->where('counselled_course_id',$request->course_id)
            ->update(['enrollment_status'=>1]);
        }
        return back()->with('success','Enrollment Cancelled Successfully.');
    }

    
}