<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

use App\Models\Course;
use App\Models\Student;
use App\Models\ExamFee;
use App\Models\Subject;
use App\Models\Campuse;
use App\Models\ExamSchedule;
use App\Models\Enrollment;
use App\Models\Application;
use App\Models\StudentSemesterFee;
use App\Models\Semester;
use App\Models\Icard;
use App\Models\Stream;
use App\Models\MbbsExamForm;
use Validator;

class ExamFeeController extends AdminController
{
	 public function __construct()
    {
        parent::__construct();
    }
	public function index(Request $request)
    {
        $examfees = ExamFee::orderBy('id', 'DESC');
        if($request->search) {
            $keyword = $request->search;
            $examfees->where(function($q) use ($keyword){

                $q->where('roll_no', 'LIKE', '%'.$keyword.'%');
            });
        }
		 dd($request->name);
        if(!empty($request->name)){
             dd($request->name);
            $examfees->where('roll_no','LIKE', '%'.$request->name.'%');
        }
         if (!empty($request->course_id)) {
            $examfees->where('course_id',$request->course_id);
        }
        if (!empty($request->category_id)) {
            
            $examfees->where('program_id',$request->category_id);
        }
        if (!empty($request->campus)) {
            $course=Course::where('campus_id',$request->campus)->pluck('id')->toArray();
            //dd($course);
            $examfees->whereIn('course_id',$course);
        }
        if(!empty($request->semester)) {
            $semester_ids = Semester::where('name',$request->semester)->pluck('id')->toArray();
            $examfees->whereIn('semester_id',$semester_ids);
        }
       $examfees = $examfees->paginate(10);
    
        $category = Category::all();
        $course = Course::all();
        $campuse = Campuse::all();
        $semester = Semester::select('name')->distinct()->get();
        
        dd($campuse);

        return view('admin.master.examfee.index', [
            'page_title' => "ExamFee",
            'sub_title' => "records",
            'all_fee' => $examfees,
            'categories' => $category,
            'courses' => $course,
            'campuselist' => $campuse,
            'semesterlist' => $semester,
        ]);
    }
	 public function add(Request $request)
    {
        
        return view('admin.master.examfee.addfee', [
            'page_title' => "Add New",
            'sub_title' => "Exam Fee"
        ]);
    }
	
    
    public function approve(Request $request ,$slug)
    {
        $data=ExamFee::where('id', $slug)->update(['fee_status' => 1]);
		return back()->with('message','Student Exam Fee Approved Successfully');
    }
	public function student_data(Request $request)
	{
		$query=Enrollment::where('enrollment_no',$request->student_id)->first();
		$data=Course::where('id',$query->course_id)->first();
		//dd($data);
		$fee=StudentSemesterFee::where('enrollment_no',$request->student_id);
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

    public function view_exam_form(Request $request,$slug)
    { //  dd('');
        $data['application']= $data['icard']=$data['stream']=$data['semester']=$data['[subjects']=null;
    	$id= ExamFee::where('id',$slug)->orderBy('id','DESC')->first();
    	//dd($data);
    	$en=Enrollment::where('enrollment_no',$id->enrollment_no)->first();
    	if($en){
            $data['application']=Application::where('id',$en->application_id)->first();
            //dd($application);
            $data['icard']=Icard::where('roll_no',$en->roll_number)->first();
            //dd($icard);
            $data['stream']=Stream::where('course_id',$en->course_id)->first();
            //dd($stream);
            $data['semester']=Semester::where('course_id',$data['stream']->course_id)->first();
            //dd($semester);
            $data['subjects']=Subject::where('semester_id',$data['semester']->semester_number)->get();
        }
        else{
            $data['mbbs']=MbbsExamForm::where('exam_fee_id',$slug)->first();
            $data['subjects']=MbbsExamForm::where('exam_fee_id',$slug)->get();
            //$data=null;
        }
        return view('admin.master.examfee.examfee-view',['data'=>$data,'id'=>$id]);
    } 
	
}
