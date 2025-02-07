<?php

namespace App\Http\Controllers\ums\Admin;
use App\Models\ums\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\ums\AdminController;


use App\Models\Application;
use App\Models\ums\StudentAllFromOldAgency;
use App\Exports\StudentExport;
use Illuminate\Support\Facades\Auth;


use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\StudentT;

class StudentController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $students = array();
        $students = Student::orderBy('id', 'DESC');
  
        if ($request->search) {
            $keyword = $request->search;
    
            
            $students->where(function($q) use ($keyword) {
                $q->where('first_Name', 'LIKE', '%' . $keyword . '%')
                  ->orWhere('email', 'LIKE', '%' . $keyword . '%')
                  ->orWhere('mobile', 'LIKE', '%' . $keyword . '%')
                  ->orWhere('roll_number', 'LIKE', '%' . $keyword . '%');
            });
        }
        
        
        $students = $students->limit(50)->get();
        // dd($students);
    
       
        return view('ums.usermanagement.student.index', [
            'students' => $students
        ]);
    }
    
    // public function index(Request $request)
    // {   
       
    //     $students = Student::orderBy('id', 'DESC');
    //     if($request->search) {
    //         $keyword = $request->search;
            
    //         $students->where(function($q) use ($keyword){

    //             $q->where('first_Name', 'LIKE', '%'.$keyword.'%')
    //                 ->orWhere('email', 'LIKE', '%'.$keyword.'%')
    //                 ->orWhere('mobile', 'LIKE', '%'.$keyword.'%')
    //                 ->orWhere('roll_number', 'LIKE', '%'.$keyword.'%');
    //         });
    //         $students = $students->get();
    //         dd($students);
    //     }
        
    //     return view('ums.usermanagement.student_list.index', [
    //         'students' => $students
    //     ]);
    // }

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
    public function ChangeEmail(Request $request, $id)
    {
        $students = Student::where('id','=',$id)->first();
         return view('admin.student.update_email', [
            'students' => $students,
        ]);
    }

    public function updateEmail(Request $request)
    {
        $students = Student::find($request->id);
        if($students){
            $StudentAllFromOldAgency = StudentAllFromOldAgency::where('roll_no',$students->roll_number)->first();
            $input = $request->except('_token','id');
            $input = array_filter($input);
            unset($input['date_of_birth']);
           // unset($input['enrollment_no']);
            //unset($input['first_name']);
            //unset($input['gender']);
            //unset($input['father_first_name']);
            //unset($input['mother_first_name']);
            //unset($input['email']);
            unset($input['mobile']);
            foreach($input as $index=>$value){
                if($index=='date_of_birth'){
                    $value = date('Y-m-d',strtotime($value));
                }

                if($index=='first_name'){
                    $students->first_Name = $value;
                }else{
                    $students->$index = $value;
                }
                if($StudentAllFromOldAgency && $index!='password'){
                    $StudentAllFromOldAgency->$index = $value;
                }
            }
            $students->save();
            if($StudentAllFromOldAgency){
                $StudentAllFromOldAgency->save();
            }
        }else{
            return back()->with('error','Student Not Found');         
        }

        return redirect('students')->with('success','Email Changed Successfully');         
    }

    public function studentExport(Request $request)
    {
        return Excel::download(new StudentExport($request), 'Student.xlsx');
    } 

    public function studentReport()
    {
         $students['first_Name'] = Student::get();
         return view('/report/studentreport',$students);
    }

    public function studentHindiName(Request $request)
    {
        $students = Student::select('*')
            ->whereNull('hindi_name')
            ->whereNotNull('roll_number')
            ->get();
        return view('ums.usermanagement.student.student_hindi_name', [
            'students' => $students
        ]);
    }
    public function updateHindiName(Request $request)
    {
        $student = Student::where('roll_number',$request->roll_number)->update(['hindi_name' => $request->hindi_name]);
        if($student > 0){
            return '1';
        }else{
            return '0';
        }
    }

    public function studentLoginRedirect(Request $request){
        // dd($request->all());
        $roll_no = $request->roll_no;
        $user = Student::where('roll_number',$roll_no)->first();
		if($user){
            $user->exam_portal = 0;
            $user->save();
			Auth::guard('student')->login($user);
            if($request->exam_id){
                return redirect('/master/examfee/view/'.$request->exam_id);
            }
		}
        return back()->with('error','Invalid Student');
    }

}