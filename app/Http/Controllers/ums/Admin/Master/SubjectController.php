<?php

namespace App\Http\Controllers\ums\Admin\Master;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\ums\AdminController;
use Validator;
use App\Models\ums\Subject;
use App\Models\ums\Category;
use App\Models\ums\Course;
use App\Models\ums\Semester;
use App\Models\ums\Campuse;
use App\Exports\ums\SubjectExport;
use App\Models\ums\Stream;

use App\Imports\ums\SubjectBulkUploadImport;
use App\Models\ums\Result;
use Maatwebsite\ums\Excel\Facades\Excel;

class SubjectController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {   
        $subjects = Subject::with(['course', 'category', 'semester'])->orderBy('id', 'DESC');
//			dd($subjects->get());
        if($request->search) {
            $keyword = $request->search;
            $subjects->where(function($q) use ($keyword){

                $q->where('sub_code', 'LIKE', '%'.$keyword.'%');
            });
        }
        if(!empty($request->name)){
            
            $subjects->where('name','LIKE', '%'.$request->name.'%');
        }
        if (!empty($request->course_id)) {
            $subjects->where('course_id',$request->course_id);
        }
        if (!empty($request->category_id)) {
            
            $subjects->where('program_id',$request->category_id);
        }
		if (!empty($request->campus)) {
			$course=Course::where('campus_id',$request->campus)->pluck('id')->toArray();
            //dd($course);
            $subjects->whereIn('course_id',$course);
        }
		if(!empty($request->semester)) {
			$semester_ids = Semester::where('name',$request->semester)->pluck('id')->toArray();
            $subjects->whereIn('semester_id',$semester_ids);
        }
        $subjects = $subjects->paginate(10);
        $category = Category::all();
        $course = Course::all();
        $campuse = Campuse::all();
		$semester = Semester::select('name')->distinct()->get();
		//dd($semester);
        return view('ums.master.subject_list.subject_list', [
            'page_title' => "Subject",
            'sub_title' => "records",
            'all_subject' => $subjects,
			'categories' => $category,
            'courses' => $course,
			'campuselist' => $campuse,
			'semesterlist' => $semester,
        ]);
    }

    public function add(Request $request)
    {
         $program = Category::all();
         $course = Course::all();
		 $campuse = Campuse::all();
        return view('admin.master.subject.addsubject', [
            'page_title' => "Add New",
            'sub_title' => "Subject",
			'campuselist' => $campuse,
			'programs'=>$program
			]);
    }

    public function addSubject(Request $request)
    {
        $validator= Validator::make($request->all(),[
			'program'=>'required',
			'course'=>'required',
			'semester'=>'required',
			'stream_id'=>'required',
            'name' => 'required',
            'sub_code' => 'required|unique:subjects,course_id',
            'back_fees' => 'required',
            'subject_type' => 'required',
            'type' => 'required',
            'maximum_mark' => 'required',
            'minimum_mark' => 'required',
			
        ]);
		if ($validator->fails()) {    
			return back()->withErrors($validator)->withInput();
		}
        $data = $request->all();
		//dd($data);
        $subject = $this->create($data);
		if($subject==true){
        return redirect('admin/master/subject/')->with('message','Subject Added Succesfully');
		}
    }

    public function create(array $data)
    {
		//dd($data);
      return Subject::create([
		'program_id'=>$data['program'],
		'course_id'=>$data['course'],
		'semester_id'=>$data['semester'],
		'stream_id'=>$data['stream_id'],
		'sub_code' => $data['sub_code'],
		'name' => $data['name'],
		'back_fees' => $data['back_fees'],
		'subject_type'=>$data['subject_type'],
		'type'=>$data['type'],
		'credit'=>$data['credit'],
		'internal_maximum_mark'=>$data['internal_maximum_mark'],
		'maximum_mark'=>$data['maximum_mark'],
		'minimum_mark'=>$data['minimum_mark'],
		'batch'=>$data['batch'],
        'created_by' => 1,
        //'created_by' => Auth::guard('admin')->user()->id,
        'status' => $data['subject_status'] == 'active'?1:0
      ]);
    }

    public function editSubject(Request $request)
    {
        $request->validate([
            'name' => 'required', 
            'batch' => 'required', 
            'sub_code' => 'required', 
        ]);
        $status = $request['subject_status'] == 'active'?1:0;
        $update_category = Subject::where('id', $request->subject_id)->update(['program_id'=>$request->program,
		'course_id'=>$request->course,
		'semester_id'=>$request->semester,
		'stream_id'=>$request->stream_id,
		'sub_code'=>$request->sub_code,
		'back_fees'=>$request->back_fees,
		'name' => $request->name,
		'subject_type' => $request->subject_type,
		'type' => $request->type,
		'credit' => $request->credit,
		'maximum_mark' => $request->maximum_mark,
		'maximum_mark' => $request->maximum_mark,
		'internal_maximum_mark' => $request->internal_maximum_mark,
		'batch' => $request->batch,
		'status' => $status, 
		'updated_by' => 1]);
        return redirect('admin/master/subject/')->with('success','Updated Successfully');
    }

    public function subjectSequenceUpdate(Request $request)
    {
        $request->validate([
            'position.*' => 'required', 
        ]);

        foreach($request->position as $index=>$position){
            $sub_code = $request->sub_code[$index];
            Subject::where('semester_id',$request->semester_id)
                ->where('sub_code',$sub_code)
                ->update(['position'=>$position]);
        }
        return 'true';
    }

    

    public function marksheetPositionUpdate(Request $request)
    {
        $request->validate([
            'subject_position.*' => 'required', 
            'subject_code.*' => 'required', 
            'batch_year' => 'required', 
        ]);

        $batch = substr($request->batch_year,2,2);
        foreach($request->subject_position as $index=>$subject_position){
            $subject_code = $request->subject_code[$index];
            Result::where('semester',$request->semester_id)
                ->where('subject_code',$subject_code)
                ->where('roll_no','LIKE',$batch.'%')
                ->update(['subject_position'=>$subject_position]);
        }
        return 'true';
    }

    public function marksheetSubjectNameUpdate(Request $request)
    {
        $request->validate([
            'semester_id' => 'required', 
            'subject_code' => 'required', 
            'subject_name' => 'required', 
            'batch' => 'required', 
        ]);
        $batch = batchPrefixByBatch($request->batch);
        Result::where('subject_code',$request->subject_code)
        ->where('semester',$request->semester_id)
        ->where('roll_no','LIKE',$batch.'%')
        ->update(['subject_name'=>$request->subject_name]);
        return 'true';
    }

    


    public function editsubjects(Request $request, $slug)
    {
		$program = Category::all();
        $selectedSubject = Subject::Where('id', $slug)->first();
        if(!$selectedSubject){
            return back();
        }
		$campuse = Campuse::all();
        $course = Course::where('campus_id',$selectedSubject->course->campus_id)->get();
        $semester = Semester::where('course_id',$selectedSubject->course_id)->get();
        $streams = Stream::where('course_id',$selectedSubject->course_id)->get();
        return view('admin.master.subject.editsubject', [
            'page_title' => 'Edit Subject',
         	'courseList'        => $course,
       	    'semesterlist'        => $semester,
       	    'streams'        => $streams,
            'sub_title' => "Edit Information",
            'selected_subject' => $selectedSubject,
			'campuselist' => $campuse,
            'programs' => $program
        ]);
    }

    public function softDelete(Request $request,$slug) {
        
        Subject::where('id', $slug)->delete();
        return back()->with('success','Deleted Successfully');
        
    }
    public function subjectExport(Request $request)
    {
        return Excel::download(new SubjectExport($request), 'Subject.xlsx');
    }
		
		
		public function get_programm(Request $request)
	{
		// dd($request->all());
		$html='<option value="">--Select Course--</option>';
        $query= Course::where(['campus_id' => $request->campuse_id, 'category_id' => $request->program])->get();
		foreach($query as $sc){
			$html.='<option value="'.$sc->id.'">'.$sc->name.'</option>';
		}
		return $html;
	}
	public function get_Semester(Request $request)
	{
		// dd($request->all());
		$html='<option value="">--Select Semester--</option>';
		$query= Semester::where(['program_id' => $request->program_id,'course_id' => $request->course_id])->get();
		foreach($query as $sc){
			$html.='<option value="'.$sc->id.'">'.$sc->name.'</option>';
		}
		return $html;
	}
	public function get_stream(Request $request)
	{
		// dd($request->all());
		$html='<option value="">--Select Semester--</option>';
		$query= Stream::where(['course_id' => $request->course_id])->get();
		foreach($query as $sc){
			$html.='<option value="'.$sc->id.'">'.$sc->name.'</option>';
		}
		return $html;
	}

    public function subjectDragDrop(Request $request){
        $where_array = $request->only('course_id','semester_id');
        $compulsory_subjects = Subject::where($where_array)->where('type','compulsory')->get();
        $type_null_subjects_union = Subject::where($where_array)->whereNull('type');
        $optional_subjects = Subject::where($where_array)->where('type','optional')->union($type_null_subjects_union)->get();
        $deleted_subjects = Subject::where($where_array)->onlyTrashed()->get();
        $semester = Semester::find($request->semester_id);
        return view('admin.master.subject.subject-drag-drop',compact('compulsory_subjects','optional_subjects','deleted_subjects','semester'));
    }
    public function subject_type_update(Request $request)
    {
        $request->validate([
            'subject_id' => 'required', 
            'type' => 'required', 
        ]);

        $subject = Subject::where('id',$request->subject_id)->withTrashed()->first();
        if($request->type=='deleted'){
            $subject->delete();
        }else{
            $subject->type = $request->type;
            $subject->deleted_at = null;
            $subject->save();
        }
        return 'true';
    }

    public function subjectBulk(Request $request){
        $campuses = Campuse::all();
        $courses = Course::where('campus_id',$request->campus_id)->get();
        $semesters = Semester::where('course_id',$request->course_id)->get();
        $subjects = Subject::where('course_id',$request->course_id)
        ->where('semester_id',$request->semester_id)
        ->orderBy('position')
        ->get();
        return view('admin.master.subject.subject-bulk',compact('campuses','courses','semesters','subjects'));
    }

    public function subjectBulkSave(Request $request)
    {
    	$request->validate([
            'subject_file' => 'required',
        ]);
        if($request->hasFile('subject_file')){
			Excel::import(new SubjectBulkUploadImport, $request->file('subject_file'));
		}
        return back()->with('success','Records Saved!');
    }

    public function subject_settings(Request $request){
        $campuses = Campuse::all();
        $courses = Course::where('campus_id',$request->campus_id)->get();
        $semesters = Semester::where('course_id',$request->course_id)->get();
        $subjects = Subject::where('course_id',$request->course_id)
        ->where('semester_id',$request->semester_id)
        ->orderBy('position')
        ->get();
        return view('admin.master.subject.subject_settings',compact('campuses','courses','semesters','subjects'));
    }

    public function subject_settingsSave(Request $request)
    {
    	$request->validate([
            'subject_file' => 'required',
        ]);
        if($request->hasFile('subject_file')){
			Excel::import(new SubjectBulkUploadImport, $request->file('subject_file'));
		}
        return back()->with('success','Records Saved!');
    }

}