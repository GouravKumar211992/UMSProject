<?php

<<<<<<< HEAD
namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\AdminController;
=======
namespace App\Http\Controllers\ums\Admin\Master;

use App\Http\Controllers\ums\AdminController;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\Course;
use App\Models\Category;
use App\Models\Semester;
use App\Models\Campuse;
use App\Exports\SemesterExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
use Validator;


class SemesterController extends AdminController
{
     public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {   
        $semester = Semester::orderBy('id', 'DESC');
        if($request->search) {
            $keyword = $request->search;
            $semester->where(function($q) use ($keyword){

                $q->where('name', 'LIKE', '%'.$keyword.'%');
            });
        }
        if(!empty($request->name)){
            
            $semester->where('name','LIKE', '%'.$request->name.'%');
        }
		 if (!empty($request->course_id)) {
            $semester->where('course_id',$request->course_id);
        }
        if (!empty($request->category_id)) {
            
            $semester->where('program_id',$request->category_id);
        }
		if (!empty($request->campus)) {
			$course=Course::where('campus_id',$request->campus)->pluck('id')->toArray();
            //dd($course);
            $semester->whereIn('course_id',$course);
        }
		       
        $semester = $semester->paginate(10);
        $category = Category::all();
        $course = Course::all();
        $campuse = Campuse::all();
<<<<<<< HEAD
        return view('admin.master.semester.index', [
=======
        return view('ums.master.semester_list.semester_list', [
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
            'page_title' => "Semester",
            'sub_title' => "records",
            'all_semester' => $semester,
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
<<<<<<< HEAD
        return view('admin.master.semester.addsemester', [
=======
        return view('ums.master.semester_list.semester_list_add', [
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
            'page_title' => "Add New",
            'sub_title' => "Semester",
            'categorylist' => $category,
            'courselist' => $course,
			'campuselist' => $campuse,
        ]);
    }

    public function addSemester(Request $request)
    {
        $validator = Validator::make($request->all(), [
             'category_id' => 'required',
            'course_id' => 'required',
            'name' => 'required|max:25',
            'semester_number' => 'required',
         ]);

        if ($validator->fails())
         {
                return back()->withErrors($validator)->withInput();
        }
				
        $data = $request->all();
        $semester = $this->create($data);
<<<<<<< HEAD
        return redirect()->route('get-semester')->with('message','Semester Added Successfully');
=======
        return redirect()->route('semester_list')->with('message','Semester Added Successfully');
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
    }

    public function create(array $data)
    {
      return Semester::create([
	  'program_id'=>$data['category_id'],
	  'course_id'=>$data['course_id'],
        'name' => $data['name'],
        'semester_number' => $data['semester_number'],
      ]);
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
<<<<<<< HEAD
        return redirect()->route('get-semester')->with('success','Updated Successfully');
=======
        return redirect()->route('semester_list')->with('success','Updated Successfully');
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
        
    }

    public function editsemesters(Request $request, $slug)
    {
		
        $selectedSemester = Semester::Where('id', $slug)->first();
        $categorylist  = Category::all();
        $courseList = Course::all();
		$campuse = Campuse::all();
		//dd($selectedSemester);
<<<<<<< HEAD
        return view('admin.master.semester.editsemester',[
            'page_title' => $selectedSemester->name,
=======
        return view('ums.master.semester_list.semester_list_edit',[
            
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
            'sub_title' => "Edit Information",
            'selected_semester' => $selectedSemester,
             'categorylist' => $categorylist,
             'courseList' => $courseList,
			 'campuselist' => $campuse,

        ]);
    }

    public function softDelete(Request $request,$slug) {
        
        Semester::where('id', $slug)->delete();
<<<<<<< HEAD
        return back()->with('success','Deleted Successfully');
=======
        return redirect()->route('semester_list')->with('success','delete Successfully');
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
        
    }
    public function semesterExport(Request $request)
    {
        return Excel::download(new SemesterExport($request), 'Semester.xlsx');
    } 
}
