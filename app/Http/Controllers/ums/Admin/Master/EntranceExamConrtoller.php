<?php

namespace App\Http\Controllers\ums\Admin\Master;


use App\Http\Controllers\ums\AdminController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\ums\Course;
use App\Models\ums\Category;
use App\Models\ums\Semester;
use App\Models\ums\Campuse;
use App\Models\ums\ExamCenter;
use App\Models\ums\AcademicSession;
use App\Models\ums\EntranceExamAdmitCard;
use App\Exports\SemesterExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
use Validator;


class EntranceExamConrtoller extends AdminController
{
     public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {   
        $entranceExam = EntranceExamAdmitCard::orderBy('id', 'DESC');
        if($request->search) {
            $keyword = $request->search;
            $entranceExam->where(function($q) use ($keyword){

                $q->where('name', 'LIKE', '%'.$keyword.'%');
            });
        }
        if(!empty($request->name)){
            
            $entranceExam->where('name','LIKE', '%'.$request->name.'%');
        }
		 if (!empty($request->course_id)) {
            $entranceExam->where('course_id',$request->course_id);
        }
        if (!empty($request->category_id)) {
            
            $entranceExam->where('program_id',$request->category_id);
        }
		if (!empty($request->campus)) {
			$course=Course::where('campus_id',$request->campus)->pluck('id')->toArray();
            //dd($course);
            $entranceExam->whereIn('course_id',$course);
        }
		       
        $entranceExam = $entranceExam->paginate(10);
        $category = Category::all();
        $course = Course::all();
        $campuse = Campuse::all();
        return view('ums.master.entrance_exam.entrance_exam', [
            'page_title' => "Semester",
            'sub_title' => "records",
            'entranceExamData' => $entranceExam,
			'categories' => $category,
            'courses' => $course,
			'campuselist' => $campuse,
        ]);
    }

    public function add(Request $request)
    {
		$category = Category::all();
        $course = Course::all();
        $campuse = Campuse::all();
        $sessions = AcademicSession::orderBy('id','DESC')->limit(1)->get();
        $examcenter = ExamCenter::all();
        return view('admin.master.entrance-exam-allow.add_entrance_exam_schedule', [
            'page_title' => "Add New",
            'sub_title' => "Semester",
            'categorylist' => $category,
            'courselist' => $course,
			'campuselist' => $campuse,
            'examcenter' => $examcenter,
            'sessions' => $sessions,
        ]);
    }

    public function addEntranceExam(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'campuse_id' => 'required',
            'program_id' => 'required',
            'course_id' => 'required',
            'entrance_exam_date' => 'required',
            'reporting_time' => 'required',
            'examination_time' => 'required',
            'end_time' => 'required',
            'center_id' => 'required',
            'session' => 'required',
         ]);

        if ($validator->fails())
         {
                return back()->withErrors($validator)->withInput();
        }
        EntranceExamAdmitCard::create([
        'campuse_id'=>$request->campuse_id,
        'program_id'=>$request->program_id,
        'course_id'=>$request->course_id,
        'entrance_exam_date'=>$request->entrance_exam_date,
        'reporting_time'=>$request->reporting_time,
        'examination_time'=>$request->examination_time,
        'end_time'=>$request->end_time,
        'center_id'=>$request->center_id,
        'session'=>$request->session,
        ]);
        return redirect()->route('get-entrance-exam')->with('message','Added Successfully');
    }

    public function editSemester(Request $request)
    { 
		//dd($request->all());
        $request->validate([
            'course_id' => 'required',
            'category_id' => 'required',
            'semester_name' => 'required',
            'semester_number' => 'required',
        ]);
        $update_category = Semester::where('id',$request->semester_id)->update(['program_id' => $request->category_id,'course_id'=>$request->course_id,'name'=>$request->semester_name, 'semester_number'=>$request->semester_number ]);
		//dd($update_category);
        return redirect()->route('get-semester')->with('success','Updated Successfully');
        
    }

    public function editsemesters(Request $request, $slug)
    {
		
        $selectedSemester = Semester::Where('id', $slug)->first();
        $categorylist  = Category::all();
        $courseList = Course::all();
		$campuse = Campuse::all();
		//dd($selectedSemester);
        return view('admin.master.semester.editsemester',[
            'page_title' => $selectedSemester->name,
            'sub_title' => "Edit Information",
            'selected_semester' => $selectedSemester,
             'categorylist' => $categorylist,
             'courseList' => $courseList,
			 'campuselist' => $campuse,

        ]);
    }

    public function softDelete(Request $request,$slug) {
        
        EntranceExamAdmitCard::where('id', $slug)->delete();
        return back()->with('success','Deleted Successfully');
        
    }
    public function semesterExport(Request $request)
    {
        return Excel::download(new SemesterExport($request), 'Semester.xlsx');
    } 
}
