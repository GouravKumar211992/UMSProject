<?php

namespace App\Http\Controllers\ums\Admin\Master;

use App\Http\Controllers\ums\AdminController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\ums\StudentSemesterFee;
use App\Models\ums\Semester;
use App\Models\ums\Enrollment;
use App\Models\ums\Course;
use App\Models\ums\ExamForm;
use App\Models\ums\ExamFee;
use App\Exports\FeeExport;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use Auth;
use DB;


class SemesterFeeController extends AdminController
{
     public function __construct()
    {
        parent::__construct();
    }
	
	public function index(Request $request)
    {
        
        $semesterfees = StudentSemesterFee::orderBy('id', 'DESC');;
        if($request->search) {
            $keyword = $request->search;
            $semesterfees->where(function($q) use ($keyword){

                $q->where('name', 'LIKE', '%'.$keyword.'%');
            });
        }
        if(!empty($request->name)){
            
            $semesterfees->where('name','LIKE', '%'.$request->name.'%');
        }
        if (!empty($request->course_id)) {
            $semesterfees->where('course_id',$request->course_id);
        }
        $semesterfees = StudentSemesterFee::with('course', 'semester')->paginate(10);

       $course = Course::all();
      

            return view('ums.studentfees.semester_fee', [
            'page_title' => "SemesterFeeFee",
            'sub_title' => "records",
            'all_fee' => $semesterfees,
            'courses' => $course
        ]);
    }

    public function add(Request $request)
    {
        
        return view('ums.studentfees.add_semesterfee', [
            'page_title' => "Add New",
            'sub_title' => "Fee"
        ]);
    }

    public function addSemesterFee(Request $request)
    {
 
		$validator = Validator::make($request->all(),[
            'course_code' => 'required',
            'student_id' => 'required',
            'semester' => 'required',
            'semester_fee'=>'required',
			'receipt_number'=>'required',
			'receipt_date'=>'required'
        ]);
		if ($validator->fails()) {    
			return back()->withErrors($validator);
		}
        $data = $request->all();
        $fee = $this->update($data);
        return redirect()->route('get-semesterfees')->with('message','Semester Fee Submitted Successfully');
    }

    public function update(array $data)
    {
      return StudentSemesterFee::where('student_id',$data['student_id'])->update([
        'semester_fee'=>$data['semester_fee'],
        'receipt_number'=>$data['receipt_number'],
        'receipt_date'=>$data['receipt_date'],
        'status'=>1       
      ]);
    }

    public function editFee(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:fees',
        ]);
        $status = $request['fee_status'] == 'active'?1:0;
        $update_category = Fee::where('id', $request->fee_id)->update(['name' => $request->fee_name, 'status' => $status, 'updated_by' => 1]);
        return redirect()->route('get-semesterfees');
        
    }


    public function editfees(Request $request, $slug)
    {
        $selectedFee = StudentSemesterFee::where('id', $slug)->first();
        return view('ums.studentfees.edit_semesterfee', [
            'page_title' => $selectedFee->student_id,
            'sub_title' => "Edit Information",
            'selected_fee' => $selectedFee
        ]);
    }

    public function softDelete(Request $request,$slug) {
        
        Fee::where('id', $slug)->delete();
        return redirect()->route('get-fees');
        
    }
    public function feeExport(Request $request)
    {
        return Excel::download(new FeeExport($request), 'Fee.xlsx');
    }
	public function approve(Request $request ,$slug)
    {
        $data=StudentSemesterFee::where('id', $slug)->update(['status' => 1]);
		return back()->with('message','Student Semester Fee Approved Successfully');
    }
	
	public function student_data(Request $request)
	{
		$query=Enrollment::where('enrollment_no',$request->student_id)->first();
		$data=Course::where('id',$query->course_id)->first();
		//dd($data);
		$fee=StudentSemesterFee::where('student_id',$request->student_id);
		return $data->name;
	}
	public function course_data(Request $request)
	{
		//dd($request->all());
		$query=Course::where('name',$request->course_id)->first();
		//dd($query->id);
		$data=Semester::where('course_id',$query->id)->get();
		//dd($data);
		$html='<option value="">--Select Semester--</option>';
		foreach($data as $sc){
			$html.='<option value='.$sc->id.'>'.$sc->name.'</option>';
		}
		return $html;
	
		
	}
   
}
