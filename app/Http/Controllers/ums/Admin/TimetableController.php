<?php

namespace App\Http\Controllers\Admin;

use Auth;

use App\Models\faculty;
use App\Models\Course;
use App\Models\Period;
use App\Models\Subject;

use App\Models\Semester;
use App\Models\Timetable;
use Illuminate\Http\Request;
use App\Exports\PeriodExport;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\AdminController;

class TimetableController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function fetchSemester(Request $request)
    {
       //dd($request->course_id);
        $data['semester'] = Semester::where("course_id",$request->course_id)->get(["name", "id"]);
        return response()->json($data);
    }

    public function fetchSubject(Request $request)
    {
      //  dd($request->semester_id);
        $data['subject'] = Subject::where("semester_id",$request->semester_id)->get(["name", "id"]);
        return response()->json($data);
    }

    public function index(Request $request)
    {   
        $timetables = Timetable::with(['course','period','semester','subject','subject'])->orderBy('id', 'DESC');
//        dd($timetables);
        if($request->search) {
            $keyword = $request->search;
            $timetables->where(function($q) use ($keyword){

                $q->where('name', 'LIKE', '%'.$keyword.'%');
            });
        }
        if(!empty($request->name)){
            
            $timetables->where('name','LIKE', '%'.$request->name.'%');
        }
         $timetables = $timetables->paginate(10);
         //dd($timetables);
        return view('admin.timetable.index', [
            'page_title' => "Timetable",
            'sub_title' => "records",
            'all_timetable' => $timetables
        ]);
        
    }

    public function add(Request $request)
    {
        $periods = Period::all();
        $courses = Course::all();
        $facultys = faculty::all();
    //    dd($facultys);
        return view('admin.timetable.addtimetable', [
            'page_title' => "Add New",
            'sub_title' => "Timetable",
        ])->withPeriods($periods)->withCourses($courses)->withFacultys($facultys);
    }

    public function addTimetable(Request $request)
    {
        $request->validate([
            'timetable_status' => 'required',
            'period_id' => 'required',
            'day' => 'required',
            'course_id' => 'required',
            'semester_id' => 'required',
            'subject_id' => 'required',
            'faculty_id' => 'required',
            'room_no' => 'required',
        ]);
        $data = $request->all();
       // dd($data);
  
        $timetable = new Timetable;
        $timetable->period_id = $request->period_id;
        $timetable->day = $request->day;
        $timetable->course_id = $request->course_id;
        $timetable->semester_id = $request->semester_id;
        $timetable->subject_id = $request->subject_id;
        $timetable->subject_id = $request->subject_id;
        $timetable->faculty_id = $request->faculty_id;
        $timetable->room_no = $request->room_no;
        $timetable->save();

     //   $timetable = $this->create($data);
        return redirect()->route('get-timetables')->with('success','Added Successfully.');
    }

    public function create(array $data)
    {
         dd($data);
        
      return Timetable::create([
        'period_id' => $data['period_id'],
        'day' => $data['day'],
        'course_id' => $data['course_id'],
        'semester_id' => $data['semester_id'],
        'subject_id' => $data['subject_id'],
        'faculty_id' => $data['faculty_id'],
        'room_no' => $data['room_no'],
		'status' => $data['timetable_status'] == 'active'?1:0,
      ]);
    }

    public function editTimetable(Request $request)
    {
        $request->validate([
            'timetable_status' => 'required',
            'period_id' => 'required',
            'day' => 'required',
            'course_id' => 'required',
            'semester_id' => 'required',
            'subject_id' => 'required',
            'faculty_id' => 'required',
            'room_no' => 'required',
        ]);
        $status = $request['timetable_status'] == 'active'?1:0;
        $update_edit = Timetable::where('id', $request->timetable_id)->update([ 'period_id' => $request->period_id,
        'day' => $request->day,
        'course_id' => $request->course_id,
        'semester_id' => $request->semester_id,
        'subject_id' => $request->subject_id,
        'faculty_id' => $request->faculty_id,
        'room_no' => $request->room_no, 'status' => $status]);
        return redirect()->route('get-timetables')->with('success','Update Successfully.');
        
    }


    public function edittimetables(Request $request, $slug)
    {
        $selectedtimetable = Timetable::Where('id', $slug)->first();
            //dd('.');

            $periods = Period::all();
            $courses = Course::all();
            $facultys = faculty::all();
            $semesters = Semester::all();
        return view('admin.timetable.edittimetable', [
            'page_title' => $selectedtimetable->name,
            'sub_title' => "Edit Information",
            'selected_timetable' => $selectedtimetable
        ])->withPeriods($periods)->withCourses($courses)->withFacultys($facultys)->withSemesters($semesters);
    }


    public function show()
    {
        return view('admin.ProductPeriod.view');
    }

    public function edit($id)
    {
        $productPeriod = ProductPeriod::find($id);
        $parents = ProductPeriod::whereNull('parent_id')->get();


        return view(
            'admin.timetable.edit',
            array(
                'parents' => $parents,
                'productPeriod' => $productPeriod
            )
        );
    }

    public function softDelete(Request $request,$slug) {
        
        Timetable::where('id', $slug)->delete();
        return redirect()->route('get-timetables')->with('success','Deleted Successfully.');
        
    }
    public function periodExport(Request $request)
    {
        return Excel::download(new PeriodExport($request), 'Timetable.xlsx');
    } 
}

