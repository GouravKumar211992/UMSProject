<?php

<<<<<<< HEAD
namespace App\Http\Controllers\Admin;

use Auth;

use App\Models\faculty;
use App\Models\Course;
use App\Models\Period;
use App\Models\Subject;

use App\Models\Semester;
use App\Models\Timetable;
=======
namespace App\Http\Controllers\ums\Admin;

use Auth;

use App\Models\ums\faculty;
use App\Models\ums\Course;
use App\Models\ums\Period;
use App\Models\ums\Subject;

use App\Models\ums\Semester;
use App\Models\ums\Timetable;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
use Illuminate\Http\Request;
use App\Exports\PeriodExport;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
<<<<<<< HEAD
use App\Http\Controllers\AdminController;
=======
use App\Http\Controllers\UMS\AdminController;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7

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
<<<<<<< HEAD
        $timetables = Timetable::with(['course','period','semester','subject','subject'])->orderBy('id', 'DESC');
//        dd($timetables);
=======
        $timetables = Timetable::with(['course','period','semester','subject','subject'])->orderBy('id', 'DESC')->paginate(10);
    
    //   dd($timetables);
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
        if($request->search) {
            $keyword = $request->search;
            $timetables->where(function($q) use ($keyword){

                $q->where('name', 'LIKE', '%'.$keyword.'%');
            });
        }
        if(!empty($request->name)){
            
            $timetables->where('name','LIKE', '%'.$request->name.'%');
        }
<<<<<<< HEAD
         $timetables = $timetables->paginate(10);
         //dd($timetables);
        return view('admin.timetable.index', [
=======
        //  $timetables = $timetables->paginate(10);
        //  dd($timetables);
         
        return view('ums.master.faculty.time_table', [
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
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
<<<<<<< HEAD
    //    dd($facultys);
        return view('admin.timetable.addtimetable', [
            'page_title' => "Add New",
            'sub_title' => "Timetable",
=======
        $semesters= semester::all();
        $subjects = Subject::all();

    //    dd($facultys);
        return view('ums.master.faculty.time_table_add', [
            'page_title' => "Add New",
            'sub_title' => "Timetable",
            'periods' => $periods,
            'courses' => $courses,
            'facultys' => $facultys,
           'semesters' => $semesters,
           'subjects' => $subjects,
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
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

<<<<<<< HEAD
=======
    // public function editTimetable(Request $request)
    // {
    //     $request->validate([
    //         'timetable_status' => 'required',
    //         'period_id' => 'required',
    //         'day' => 'required',
    //         'course_id' => 'required',
    //         'semester_id' => 'required',
    //         'subject_id' => 'required',
    //         'faculty_id' => 'required',
    //         'room_no' => 'required',
    //     ]);
    //     $status = $request['timetable_status'] == 'active'?1:0;
    //     $update_edit = Timetable::where('id', $request->timetable_id)->update([ 'period_id' => $request->period_id,
    //     'day' => $request->day,
    //     'course_id' => $request->course_id,
    //     'semester_id' => $request->semester_id,
    //     'subject_id' => $request->subject_id,
    //     'faculty_id' => $request->faculty_id,
    //     'room_no' => $request->room_no, 'status' => $status]);
    //     return redirect()->route('get-timetables')->with('success','Update Successfully.');
        
    // }

>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
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
<<<<<<< HEAD
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


=======
            'room_no' => 'required'
        ]);
    
        $status = $request->timetable_status == 'active' ? 1 : 0;
    
        $update_edit = Timetable::where('id', $request->timetable_id)->update([
            'period_id' => $request->period_id,
            'day' => $request->day,
            'course_id' => $request->course_id,
            'semester_id' => $request->semester_id,
            'subject_id' => $request->subject_id,
            'faculty_id' => $request->faculty_id,
            'room_no' => $request->room_no,
            'status' => $status
        ]);
    
        return redirect()->route('get-timetables')->with('success', 'Update Successfully.');
    }
    // public function edittimetables(Request $request, $slug)
    // {
    //     $selectedtimetable = Timetable::Where('id', $slug)->first();
    //         //dd('.');

    //         $periods = Period::all();
    //         $courses = Course::all();
    //         $facultys = faculty::all();
    //         $semesters = Semester::all();
    //     return view('ums.master.faculty.time_table_edit', [
    //         'page_title' => $selectedtimetable->name,
    //         'sub_title' => "Edit Information",
    //         'selected_timetable' => $selectedtimetable
    //     ])->withPeriods($periods)->withCourses($courses)->withFacultys($facultys)->withSemesters($semesters);
    // }

    public function edittimetables($slug)
    {
        $selectedtimetable = Timetable::Where('id', $slug)->first();
        $periods = Period::all();
        $courses = Course::all();
        $facultys = Faculty::all();
        $semesters = Semester::all();
        $subjects = Subject::all();
    
        return view('ums.master.faculty.time_table_edit', [
            'selected_timetable' => $selectedtimetable,
            'periods' => $periods,
            'courses' => $courses,
            'facultys' => $facultys,
            'semesters' => $semesters,
            'subjects' => $subjects
           
        ]);
    }
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
    public function show()
    {
        return view('admin.ProductPeriod.view');
    }

    public function edit($id)
    {
        $productPeriod = ProductPeriod::find($id);
        $parents = ProductPeriod::whereNull('parent_id')->get();


        return view(
<<<<<<< HEAD
            'admin.timetable.edit',
=======
            'ums.master.faculty.time_table_edit',
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
            array(
                'parents' => $parents,
                'productPeriod' => $productPeriod
            )
        );
    }
<<<<<<< HEAD
=======
   
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7

    public function softDelete(Request $request,$slug) {
        
        Timetable::where('id', $slug)->delete();
        return redirect()->route('get-timetables')->with('success','Deleted Successfully.');
        
    }
    public function periodExport(Request $request)
    {
        return Excel::download(new PeriodExport($request), 'Timetable.xlsx');
    } 
<<<<<<< HEAD
=======
    

>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
}

