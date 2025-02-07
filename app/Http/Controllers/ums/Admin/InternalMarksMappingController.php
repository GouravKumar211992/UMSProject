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
use App\Exports\InternalMarksMappingsExport;


use App\User;
<<<<<<< HEAD
use App\Models\Application;
use App\Models\Course;
use App\Models\CourseFee;
use App\Models\Category;
use App\Models\Classe;
use App\Models\AcademicSession;
use App\Models\Faculty;
use App\Models\Campuse;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\Stream;
use App\Models\InternalMarksMapping;
=======
use App\Models\ums\Application;
use App\Models\ums\Course;
use App\Models\ums\CourseFee;
use App\Models\ums\Category;
use App\Models\ums\Classe;
use App\Models\ums\AcademicSession;
use App\Models\ums\Faculty;
use App\Models\ums\Campuse;
use App\Models\ums\Subject;
use App\Models\ums\Semester;
use App\Models\ums\Stream;
use App\Models\ums\InternalMarksMapping;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
use App\Imports\FacultySubjectBulkUploadImport;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use Auth;


class InternalMarksMappingController extends AdminController
{
     public function __construct()
    {
        parent::__construct();
    }
	
	public function index(Request $request)
    { 
    	
    	// dd($request->all());
        $internals = InternalMarksMapping::orderBy('id', 'DESC');
		//dd($internals[0]->subjects);
        if ($request->search) {
            $keyword = $request->search;
            $internals->where(function ($q) use ($keyword) {

                $q->where('sub_code', 'LIKE', '%' . $keyword . '%');
                    
            });
        }
		
        if(!empty($request->session)){
            
            $internals->where('session','LIKE', '%'.$request->session.'%');
        }
        if(!empty($request->subject)){
            
            $internals->where('sub_code','LIKE', '%'.$request->subject.'%');
        }
		 if (!empty($request->faculty)) {
            $internals->where('faculty_id',$request->faculty);
        }
        if (!empty($request->course)) {
        	$internals->where('course_id',$request->course);
        }
        if (!empty($request->program)) {
            
            $internals->where('program_id',$request->program);
        }
		if (!empty($request->campuse_id)) {
          	$internals->where('campuse_id',$request->campuse_id);
          }
        if(!empty($request->semester)) {
            $internals->where('semester_id',$request->semester);
        }
        // dd($internals->toSql());
		//dd($internals->get()[1117]->subject->name);
        $internals = $internals->paginate(10);
		//dd($internals[0]);
		$campuseName=Campuse::where('deleted_at', null)->get();
		$facultys=Faculty::all();
		$courses=Course::all();
		$classes=Classe::all();
		$programs = Category::all();

		$subjects=Subject::all();
		//dd($subjects);

		$sessions=AcademicSession::all();
		$semester = Semester::select('name')->distinct()->get();
        
<<<<<<< HEAD
        return view('admin.internal.index', [
=======
        return view('ums.facultymapingsystem.faculty_mapping', [
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
            'page_title' => "Internal Mark Mapping",
            'sub_title' => "records",
            'internals'  => $internals,
            'campuseName'  => $campuseName,
            'facultys'  => $facultys,
            'subjects'  => $subjects,
            'classes'  => $classes,
            'courses'  => $courses,
            'programs'  => $programs,
            'sessions'  => $sessions,
			 'semesters'  => $semester
        ]);
    }
	public function add()
	{
		$facultys=Faculty::all();
		$campuses=Campuse::all();
		$subjects=Subject::all();
		$programs = Category::all();
		$sessions=AcademicSession::all();
		
		return view('admin.internal.add',[
            'page_title' => "Internal Mark Mapping",
            'sub_title' => "Add",
            'facultys'  => $facultys,
            'campuses'  => $campuses,
            'subjects'  => $subjects,
            'sessions'  => $sessions,
            'programs'  => $programs
           
			
        ]);
	}
	public function add_mapping(Request $request)
	{
		//dd($request->all());
		 $valid=$request->validate([
            'program' => 'required',
            'course' => 'required',
            'semester' => 'required',
            'subject' => 'required',
            'faculty' => 'required',
            'branch' => 'required',
            'session' => 'required',
            'campuse_id' => 'required',
            'permissions' => 'required',
        ]);
		if(!$valid){
			return back()->withInput();
		}
		
		if(!$request->faculty){
			$Faculty= new Faculty;
			$Faculty->name=$request->subject;
			$Faculty->user_name = $request->subject;
			$Faculty->password = 'faculty@123';
			$Faculty->email=$request->subject;
			$Faculty->save();
		}

		$mapping=new InternalMarksMapping;
		$mapping->program_id=$request->program;
		$mapping->course_id=$request->course;
		
		$mapping->semester_id=$request->semester;
		$mapping->sub_code=$request->subject;
		if($request->faculty){
			$mapping->faculty_id=$request->faculty;
		}else{
			$mapping->faculty_id=$Faculty->id;
		}
		$mapping->class_id=$request->branch;
		$mapping->session=$request->session;
		$mapping->campuse_id=$request->campuse_id;
		if($request->permissions=='all'){
			$mapping->permissions = 0;
		}else{
			$mapping->permissions = $request->permissions;
		}
		$mapping->save();
		return redirect()->route('internal-list')->with('message','New Mapping Added');
	}

	public function get_Subject(Request $request)
	{
		//dd($request->all());
		$html='<option value="">--Select Subject--</option>';
		$query= Subject::where(['program_id'=> $request->program,'course_id'=>$request->course,'semester_id'=>$request->semester])->get();
		foreach($query as $sc){
			$html.='<option value="'.$sc->sub_code.'">'.'('.$sc->sub_code.') '.$sc->name.'('.$sc->subject_type.')'.'</option>';
		}
		return $html;
	}
	
	
	public function get_Semester(Request $request)
	{
		//dd($request->all());
		$html='<option value="">--Select Semester--</option>';
		$query= Semester::where(['program_id' => $request->program,'course_id' => $request->course])->get();
		foreach($query as $sc){
			$html.='<option value="'.$sc->id.'">'.$sc->name.'</option>';
		}
		$html_branch='<option value="">--Select Stream--</option>';
		$query= Stream::where(['category_id'=> $request->program,'course_id'=>$request->course])->get();
		foreach($query as $sc){
			$html_branch.='<option value="'.$sc->id.'">'.$sc->name.'</option>';
		}
		$data['semester']= $html;
		$data['branch']= $html_branch;
		return response()->json($data);
	}
	
	 public function softDelete(Request $request,$slug) {
        
        InternalMarksMapping::where('id', $slug)->delete();
        return redirect()->route('internal-list')->with('message','Mapping Deleted Succesfully');
        
    }

     public function editShow(Request $request,$slug)
	
	{ 
		$selected_internal=InternalMarksMapping::where('id',$slug)->first();
//		dd($selected_internal);
		 $category = Category::all();
         $course = Course::all();
		$facultys=Faculty::all();
		$campuses=Campuse::all();
		$subjects=Subject::where('course_id',$selected_internal->course_id)
							->where('semester_id',$selected_internal->semester_id)
							->orderBy('name','asc')
							->get();
		//dd($subjects);
		$classes=Stream::all();
         $sessions=AcademicSession::all();
         $semester = Semester::get();
       return view('admin.internal.edit',[
       	'selected_internal'=>$selected_internal,
       	'categorylist'      => $category,
       	'courseList'        => $course,
       	'facultys'        => $facultys,
       	'campuses'        => $campuses,
       	'subjectlist'        => $subjects,
       	'sessions'        => $sessions,
       	'semesterlist'        => $semester,
       	'branches'        => $classes,

       ]);	
	}
	 public function update(Request $request)
    { 
		//dd($request->all());
        $request->validate([
            'program' => 'required',
            'course' => 'required',
            'branch' => 'required',
            'semester' => 'required',
            'subject' => 'required',
            'faculty' => 'required',
            'session' => 'required',
            'campuse_id' => 'required',
            'permissions' => 'required',
        ]);
		
		$mapping = InternalMarksMapping::where('id',$request->internal_id)->first();
		$mapping->program_id=$request->program;
		$mapping->course_id=$request->course;
		
		$mapping->semester_id=$request->semester;
		$mapping->sub_code=$request->subject;
		if($request->faculty){
			$mapping->faculty_id=$request->faculty;
		}else{
			$mapping->faculty_id=$Faculty->id;
		}
		$mapping->class_id=$request->branch;
		$mapping->session=$request->session;
		$mapping->campuse_id=$request->campuse_id;
		if($request->permissions=='all'){
			$mapping->permissions = 0;
		}else{
			$mapping->permissions = $request->permissions;
		}
		$mapping->save();

        return back()->with('success','Updated Successfully');
        
    }
       public function internalMarksMappingExport(Request $request)
    {
        return Excel::download(new InternalMarksMappingsExport($request), 'InternalMarksMapping.xlsx');
    } 
    public function facultySubjectMappingSave(Request $request)
    {
    	$request->validate([
            'bulk_file' => 'required',
        ]);
        if($request->hasFile('bulk_file')){
			Excel::import(new FacultySubjectBulkUploadImport, $request->file('bulk_file'));
		}
        return back()->with('success','Records Saved!');
    }


}
