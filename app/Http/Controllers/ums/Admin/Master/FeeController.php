<?php

<<<<<<< HEAD
namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\CourseSession;
use App\Models\Course;
use App\Models\AcademicSession;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use Auth;
use DB;
=======
namespace App\Http\Controllers\ums\Admin\Master;

use App\Http\Controllers\ums\AdminController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\ums\CourseSession;
use App\Models\ums\Course;
use App\Models\ums\AcademicSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
use App\Exports\FeeExport;

class FeeController extends AdminController
{
     public function __construct()
    {
        parent::__construct();
    }
	
	public function index(Request $request)
    {   
        $coursemapping = CourseSession::orderBy('id', 'DESC');
        $courses=Course::all();
        if($request->search) {
            $keyword = $request->search;
            $coursemapping->where(function($q) use ($keyword){

                $q->where('name', 'LIKE', '%'.$keyword.'%');
            });
        }
        if(!empty($request->course)){
            
            $coursemapping->where('course_id',$request->course);
        }
        
       $coursemapping = $coursemapping->paginate(10);
        //dd($courseMapping);
<<<<<<< HEAD
        return view('admin.master.fee.index', [
=======
        return view('ums.master.fee_list.fees_list', [
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
            'page_title' => "Fee",
            'sub_title' => "records",
            'all_fee' => $coursemapping,
            'courses' => $courses
        ]);
    }

    public function add(Request $request)
    {
        $course=Course::get();
		$session=AcademicSession::get();
<<<<<<< HEAD
        return view('admin.master.fee.addfee', [
=======
        
        return view('ums.master.fee_list.add_fee_list', [
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
            'page_title' => "Add New",
            'sub_title' => "Fee",
			'courses'=>$course,
			'sessions'=>$session
        ]);
<<<<<<< HEAD
    }

=======

        // echo "hello";
    }



>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
    public function addCourseSession(Request $request)
    {
 
		$validator = Validator::make($request->all(),[
        'course_id' => 'required',
		'academic_session' => 'required',
		'seat'	 => 'required',
		'basic_eligibility' => 'required',	
		'mode_of_admission'	 => 'required',
		'course_duration'	 => 'required',
		'tuition_fee_for_divyang_per_sem' => 'required',	
		'tuition_fee_for_other_per_sem' => 'required',	
		'payable_fee_for_divyang_per_sem' => 'required',	
		'payable_fee_for_other_per_sem' => 'required',
        ]);
		if ($validator->fails()) {    
			return back()->withErrors($validator)->withInput($request->all());
		}
        $data = $request->all();
<<<<<<< HEAD
        $fee = $this->create($data);
        return redirect()->route('get-fees')->with('message','Fee Details Submitted Successfully');
    }

=======
        // dd($data);
        $fee = $this->create($data);
        return redirect('fees_list')->with('message','Fee Details Submitted Successfully');
    }



>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
    public function create(array $data)
    {
		
      return CourseSession::create([
		'course_id'=>$data['course_id'],
		'academic_session'=>$data['academic_session'],
		'seat'=>$data['seat'],
		'basic_eligibility'=>$data['basic_eligibility'],	
		'mode_of_admission'=>$data['mode_of_admission'],
		'course_duration'=>$data['course_duration'],
		'tuition_fee_for_divyang_per_sem'=>$data['tuition_fee_for_divyang_per_sem'],	
		'tuition_fee_for_other_per_sem'=>$data['tuition_fee_for_other_per_sem'],	
		'payable_fee_for_divyang_per_sem'=> $data['payable_fee_for_divyang_per_sem'],	
		'payable_fee_for_other_per_sem'=> $data['payable_fee_for_other_per_sem'],
       ]);
    }

<<<<<<< HEAD
    public function editCoursesession(Request $request)
    {
=======
    public function editCoursesession(Request $request )
    {

>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
       $validator = Validator::make($request->all(),[
        'course_id' => 'required',
		'academic_session' => 'required'	,
		'seat'	 => 'required',
		'basic_eligibility' => 'required',	
		'mode_of_admission'	 => 'required',
		'course_duration'	 => 'required',
		'tuition_fee_for_divyang_per_sem' => 'required',	
		'tuition_fee_for_other_per_sem' => 'required',	
		'payable_fee_for_divyang_per_sem' => 'required',	
		'payable_fee_for_other_per_sem' => 'required',
        ]);
		if ($validator->fails()) {    
			return back()->withErrors($validator);
		}
<<<<<<< HEAD
		//dd($request->all());
=======
		// dd($request->fee_id);
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
        //$status = $request['fee_status'] == 'active'?1:0;
        $update_category = CourseSession::where('id', $request->fee_id)->update([
		'course_id'=>$request->course_id,
		'academic_session'=>$request->academic_session,
		'seat'=>$request->seat,
		'basic_eligibility'=>$request->basic_eligibility,	
		'mode_of_admission'=>$request->mode_of_admission,
		'course_duration'=>$request->course_duration,
		'tuition_fee_for_divyang_per_sem'=>$request->tuition_fee_for_divyang_per_sem,	
		'tuition_fee_for_other_per_sem'=>$request->tuition_fee_for_other_per_sem,	
		'payable_fee_for_divyang_per_sem'=> $request->payable_fee_for_divyang_per_sem,	
		'payable_fee_for_other_per_sem'=>$request->payable_fee_for_other_per_sem,
       ]);
<<<<<<< HEAD
        return redirect()->route('get-fees')->with('message','Fee Updated Successfully');
        
    }


=======
        return redirect('fees_list')->with('message','Fee Updated Successfully');
    }




>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
    public function editcoursesessions(Request $request, $slug)
    {
		$course=Course::get();
		$session=AcademicSession::get();
<<<<<<< HEAD
		$selectedCourseSession = CourseSession::where('id', $slug)->first();
        //dd($selectedCourseSession);
		return view('admin.master.fee.editfee', [
=======
        $coursemapping = CourseSession::orderBy('id', 'DESC');

		$selectedCourseSession = CourseSession::where('id', $slug)->first();
        // dd($selectedCourseSession);
		return view('ums.master.fee_list.fee_list_edit', [
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
            'page_title' => $selectedCourseSession->student_id,
            'sub_title' => "Edit Information",
            'selected_fee' => $selectedCourseSession,
			'courses'=>$course,
<<<<<<< HEAD
			'sessions'=>$session
=======
			'sessions'=>$session,
            'all_fee'=> $coursemapping
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
        ]);
    }

    public function softDelete(Request $request,$slug) {
        
        CourseSession::where('id', $slug)->delete();
<<<<<<< HEAD
        return redirect()->route('get-fees');
=======
        return redirect('fees_list');
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
        
    }
    public function feeExport(Request $request)
    {
        return Excel::download(new FeeExport($request), 'Fee.xlsx');
    }


  
}
